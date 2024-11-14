<?php

use admin\components\GroupedActionColumn;
use admin\components\widgets\gridView\Column;
use admin\components\widgets\gridView\ColumnDate;
use admin\components\widgets\gridView\ColumnImage;
use admin\modules\rbac\components\RbacHtml;
use admin\widgets\sortableGridView\SortableGridView;
use kartik\grid\SerialColumn;
use yii\widgets\ListView;

/**
 * @var $this         yii\web\View
 * @var $searchModel  common\models\NannySearch
 * @var $dataProvider yii\data\ActiveDataProvider
 * @var $model        common\models\Nanny
 */

$this->title = Yii::t('app', 'Nannies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nanny-index">

    <h1><?= RbacHtml::encode($this->title) ?></h1>

    <div>
        <?=
            RbacHtml::a(Yii::t('app', 'Create Nanny'), ['create'], ['class' => 'btn btn-success']);
//           $this->render('_create_modal', ['model' => $model]);
        ?>
    </div>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'pjax' => true,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => SerialColumn::class],

            Column::widget(),
            Column::widget(['attr' => 'child_name']),
            Column::widget(['attr' => 'child_profession']),
            ColumnImage::widget(['attr' => 'picture']),
            Column::widget(['attr' => 'image_vk']),
//            Column::widget(['attr' => 'image_fb']),
//            Column::widget(['attr' => 'token']),
//            ColumnDate::widget(['attr' => 'created_at', 'searchModel' => $searchModel, 'editable' => false]),
//            ColumnDate::widget(['attr' => 'updated_at', 'searchModel' => $searchModel, 'editable' => false]),

            ['class' => GroupedActionColumn::class]
        ]
    ]) ?>
</div>
