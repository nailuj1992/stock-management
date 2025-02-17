<?php

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
            echo Html::a(Yii::t('app', 'Update'), ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t('app', 'Deactivate');
                $class = "btn btn-danger";
                $question = Yii::t('app', "Are you sure you want to deactivate the product {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t('app', 'Activate');
                $class = "btn btn-warning";
                $question = Yii::t('app', "Are you sure you want to activate the product {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
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
                'label' => Yii::t('app', 'Has Existences?'),
                'value' => $model->getFullExistences(),
            ],
            [
                'label' => Yii::t('app', 'Tax Rate'),
                'value' => isset($model->tax_rate) ? $model->tax_rate . '%' : '-',
            ],
            [
                'label' => Yii::t('app', 'Discount Rate'),
                'value' => isset($model->discount_rate) ? $model->discount_rate . '%' : '-',
            ],
            [
                'label' => Yii::t('app', 'Minimum Stock'),
                'value' => $model->minimum_stock,
            ],
            [
                'label' => Yii::t('app', 'Suggested Value'),
                'value' => isset($model->sugested_value) ? '$' . $model->sugested_value : '-',
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>