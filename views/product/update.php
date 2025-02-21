<?php

use app\models\TextConstants;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\entities\Product $model */

$this->title = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_TITLE_UPDATE, [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_PRODUCTS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE);
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
