<?php

use app\models\TextConstants;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\entities\Product $model */

$this->title = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_BUTTON_CREATE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_PRODUCTS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
