<?php

use common\components\helpers\UserUrl;
use common\models\NannySearch;
use yii\bootstrap5\Html;

/**
 * @var $this  yii\web\View
 * @var $model common\models\Nanny
 */

$this->title = Yii::t('app', 'Create Nanny');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Nannies'),
    'url' => UserUrl::setFilters(NannySearch::class)
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nanny-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model, 'isCreate' => true]) ?>

</div>
