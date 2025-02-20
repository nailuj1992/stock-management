<?php

use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Product $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
            echo Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                $class = "btn btn-danger";
                $question = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                $class = "btn btn-warning";
                $question = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
            }
            echo Html::a($label, ['activate', 'product_id' => $model->product_id], [
                'class' => $class,
                'data' => [
                    'confirm' => $question,
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '#',
                'value' => $model->product_id,
            ],
            'code',
            'name',
            'description',
            [
                'label' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_HAS_EXISTENCES),
                'value' => $model->getFullExistences(),
            ],
            [
                'label' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_TAX_RATE),
                'value' => isset($model->tax_rate) ? $model->tax_rate . '%' : '-',
            ],
            [
                'label' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_DISCOUNT_RATE),
                'value' => isset($model->discount_rate) ? $model->discount_rate . '%' : '-',
            ],
            [
                'label' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_MINIMUM_STOCK),
                'value' => $model->minimum_stock,
            ],
            [
                'label' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_SUGGESTED_VALUE),
                'value' => isset($model->sugested_value) ? '$' . $model->sugested_value : '-',
            ],
            [
                'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>