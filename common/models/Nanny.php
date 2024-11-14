<?php

namespace common\models;

use common\components\helpers\UserUrl;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%nanny}}".
 *
 * @property int $id
 * @property string|null $child_name
 * @property string|null $child_profession
 * @property string|null $picture
 * @property string|null $image_vk
 * @property string|null $image_fb
 * @property string|null $token
 * @property int $created_at       Дата создания
 * @property int $updated_at       Дата изменения
 * @property string $name
 * @property string $email
 * @property int $rule_accepted
 * @property int $share_vk
 * @property int $share_fb
 * @property int $winner_status
 * @property int $moderation_status
 * @property int $vk
 * @property int $fb
 * @property int $share_time
 */
#[Schema(properties: [
    new Property(property: 'child_name', type: 'string'),
    new Property(property: 'child_profession', type: 'string'),
    new Property(property: 'picture', type: 'string'),
    new Property(property: 'image_vk', type: 'string'),
    new Property(property: 'image_fb', type: 'string'),
    new Property(property: 'winner_status', type: 'int'),
])]
class Nanny extends AppActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::class,
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%nanny}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['child_name', 'child_profession', 'picture', 'image_vk', 'image_fb', 'token'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    final public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'child_name' => Yii::t('app', 'Child Name'),
            'child_profession' => Yii::t('app', 'Child Profession'),
            'picture' => Yii::t('app', 'Picture'),
            'image_vk' => Yii::t('app', 'Image Vk'),
            'image_fb' => Yii::t('app', 'Image Fb'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function fields(): array
    {
        return [
            'child_name',
            'child_profession',
            'picture' => fn() => UserUrl::toAbsolute($this->picture),
            'image_vk' => fn() => UserUrl::toAbsolute($this->image_vk),
            'image_fb' => fn() => UserUrl::toAbsolute($this->image_fb),
            'winner_status',
        ];
    }
}
