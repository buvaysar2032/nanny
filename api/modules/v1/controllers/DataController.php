<?php

namespace api\modules\v1\controllers;

use api\behaviors\returnStatusBehavior\JsonSuccess;
use api\behaviors\returnStatusBehavior\RequestFormData;
use common\components\exceptions\ModelSaveException;
use common\components\helpers\UserUrl;
use common\enums\ModerationStatus;
use common\models\Nanny;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class DataController extends AppController
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'auth' => ['except' => ['create', 'update', 'index', 'share']]
        ]);
    }

    /**
     * @throws Exception
     */
    #[Post(
        path: '/data/create',
        operationId: 'nanny-create',
        description: 'Создает новую запись о няне с предоставленными данными.',
        summary: 'Создание новой няни',
        tags: ['data']
    )]
    #[RequestFormData(properties: [
        new Property(property: 'child_name', type: 'string'),
        new Property(property: 'child_profession', type: 'string'),
        new Property(property: 'picture', type: 'string'),
        new Property(property: 'image_vk', type: 'integer'),
        new Property(property: 'image_fb', type: 'string'),
    ])]
    #[JsonSuccess(content: [
        new Property(property: 'message', type: 'string', example: 'Ваша работа успешно отправлена'),
        new Property(property: 'token', type: 'string'),
    ])]
    public function actionCreate(): array
    {
        $request = Yii::$app->request->post();

        if (empty($request['child_name']) || empty($request['child_profession']) || empty($request['picture'])) {
            return $this->returnError('Все поля обязательны для заполнения.');
        }

        $nanny = new Nanny();
        $nanny->child_name = $request['child_name'];
        $nanny->child_profession = $request['child_profession'];

        $nanny->picture = $this->processImage($request['picture']);

        if (isset($request['image_vk'])) {
            $nanny->image_vk = $this->processImage($request['image_vk']);
        }

        if (isset($request['image_fb'])) {
            $nanny->image_fb = $this->processImage($request['image_fb']);
        }

        $nanny->token = Yii::$app->security->generateRandomString();

        if (!$nanny->save()) {
            throw new ModelSaveException($nanny);
        }

        return $this->returnSuccess([
            'message' => 'Ваша работа успешно отправлена',
            'token' => $nanny->token,
        ]);
    }

    /**
     * @throws Exception
     */
    public function processImage(string $imageData): string
    {
        list(, $data) = explode(';', $imageData); // data:image/jpeg;base64,... -> base64,...
        list(, $data) = explode(',', $data); // base64,..., где ... — это закодированные данные изображения.

        $data = base64_decode($data);

        $randomString = Yii::$app->security->generateRandomString();
        $filePath = Yii::getAlias('@uploads') . '/' . $randomString . '.jpg';

        file_put_contents($filePath, $data);

        return '/uploads/' . $randomString . '.jpg';
    }

    /**
     * @throws Exception
     */
    #[Post(
        path: '/data/update',
        operationId: 'nanny-update',
        description: 'Обновляет информацию о няне.',
        summary: 'Обновление информации о няне',
        tags: ['data']
    )]
    #[RequestFormData(properties: [
        new Property(property: 'token', type: 'string'),
        new Property(property: 'name', type: 'string'),
        new Property(property: 'email', type: 'integer'),
        new Property(property: 'rule_accepted', type: 'string'),
        new Property(property: 'to', type: 'string'),
    ])]
    #[JsonSuccess(content: [
        new Property(property: 'message', type: 'string', example: 'Ваша работа успешно обновлена'),
        new Property(property: 'token', type: 'string'),
        new Property(property: 'image_vk', type: 'string'),
        new Property(property: 'image_fb', type: 'string'),
        new Property(property: 'share_vk', type: 'string'),
        new Property(property: 'share_fb', type: 'string'),
    ])]
    public function actionUpdate(): array
    {
        $request = Yii::$app->request->post();

        if (empty($request['token'])) {
            return $this->returnError('Требуется токен.');
        }

        /** @var Nanny $nanny */
        $nanny = Nanny::find()->where(['token' => $request['token']])->one();

        if (!$nanny) {
            return $this->returnError('Токен не найден.');
        }

        if (empty($request['name']) || empty($request['email']) || empty($request['rule_accepted'])) {
            return $this->returnError('Все поля обязательны для заполнения.', '', 400);
        }

        $nanny->name = $request['name'];
        $nanny->email = $request['email'];
        $nanny->rule_accepted = (bool)$request['rule_accepted'];

        if (isset($request['to'])) {
            if ($request['to'] === 'vk') {
                $nanny->share_vk = true;
            } elseif ($request['to'] === 'fb') {
                $nanny->share_fb = true;
            }
        }

        if (!$nanny->save()) {
            throw new ModelSaveException($nanny);
        }

        return $this->returnSuccess([
            'message' => 'Ваша работа успешно обновлена',
            'token' => $nanny->token,
            'image_vk' => UserUrl::toAbsolute($nanny->image_vk),
            'image_fb' => UserUrl::toAbsolute($nanny->image_fb),
            'share_vk' => (bool)$nanny->share_vk,
            'share_fb' => (bool)$nanny->share_fb,
        ]);
    }

    #[Get(
        path: '/data/index',
        operationId: 'nanny-index',
        description: 'Возвращает список нянь',
        summary: 'Получение списка нянь',
        tags: ['data']
    )]
    #[JsonSuccess(content: [
        new Property(
            property: 'nannies', type: 'array',
            items: new Items(ref: '#/components/schemas/Nanny'),
        )
    ])]
    public function actionIndex(): array
    {
        /** @var Nanny $nannies */
        $nannies = Nanny::find()->where(['moderation_status' => ModerationStatus::Approved->value])->all();

        $works = [];

        foreach ($nannies as $nanny) {
            $works[] = [
                'child_name' => $nanny->child_name,
                'child_profession' => $nanny->child_profession,
                'picture' => UserUrl::toAbsolute($nanny->picture),
                'image_vk' => UserUrl::toAbsolute($nanny->image_vk),
                'image_fb' => UserUrl::toAbsolute($nanny->image_fb),
                'winner_status' => $nanny->winner_status,
            ];
        }

        return $this->returnSuccess([
            'works' => $works,
        ]);
    }

    /**
     * @throws \yii\db\Exception
     * @throws ModelSaveException
     */
    #[Post(
        path: '/data/share',
        operationId: 'nanny-share',
        description: 'Распространяет информацию о няне через VK, FB',
        summary: 'Распространение информации о няне',
        tags: ['data']
    )]
    #[RequestFormData(properties: [
        new Property(property: 'token', type: 'string'),
        new Property(property: 'to', type: 'string'),
    ])]
    #[JsonSuccess(content: [
        new Property(property: 'name', type: 'string'),
        new Property(property: 'email', type: 'string'),
        new Property(property: 'child_name', type: 'string'),
        new Property(property: 'child_profession', type: 'string'),
        new Property(property: 'picture', type: 'string'),
        new Property(property: 'winner_status', type: 'string'),
        new Property(property: 'vk', type: 'string'),
        new Property(property: 'fb', type: 'string'),
        new Property(property: 'share_time', type: 'string'),
        new Property(property: 'token', type: 'string'),
    ])]
    public function actionShare(): array
    {
        $request = Yii::$app->request->post();

        $token = $request['token'];
        $to = $request['to'];

        if (!$token || !$to) {
            return $this->returnError('Необходимо предоставить токен и социальную сеть.');
        }

        /** @var Nanny $nanny */
        $nanny = Nanny::find()->where(['token' => $token])->one();

        if (!$nanny) {
            return $this->returnError('Токен не найден.');
        }

        if ($to === 'vk') {
            $nanny->vk = 1;
        } elseif ($to === 'fb') {
            $nanny->fb = 1;
        }

        $nanny->share_time = time();

        if (!$nanny->save()) {
            throw new ModelSaveException($nanny);
        }

        return $this->returnSuccess([
            'name' => $nanny->name,
            'email' => $nanny->email,
            'child_name' => $nanny->child_name,
            'child_profession' => $nanny->child_profession,
            'picture' => $nanny->picture,
            'winner_status' => $nanny->winner_status,
            'vk' => $nanny->vk,
            'fb' => $nanny->fb,
            'share_time' => $nanny->share_time,
            'token' => $nanny->token,
        ]);
    }
}
