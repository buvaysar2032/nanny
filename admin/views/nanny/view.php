<?php

use admin\components\widgets\detailView\Column;
use admin\modules\rbac\components\RbacHtml;
use common\components\helpers\UserUrl;
use common\models\NannySearch;
use yii\widgets\DetailView;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Nanny
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Nannies'),
    'url' => UserUrl::setFilters(NannySearch::class)
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nanny-view">

    <h1><?= RbacHtml::encode($this->title) ?></h1>

    <p>
        <?= RbacHtml::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= RbacHtml::a(
            Yii::t('app', 'Delete'),
            ['delete', 'id' => $model->id],
            [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post'
                ]
            ]
        ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            Column::widget(),
            Column::widget(['attr' => 'child_name']),
            Column::widget(['attr' => 'child_profession']),
            Column::widget(['attr' => 'picture']),
            Column::widget(['attr' => 'image_vk']),
            Column::widget(['attr' => 'image_fb']),
            Column::widget(['attr' => 'token']),
            Column::widget(['attr' => 'created_at', 'format' => 'datetime']),
            Column::widget(['attr' => 'updated_at', 'format' => 'datetime']),
        ]
    ]) ?>

</div>
