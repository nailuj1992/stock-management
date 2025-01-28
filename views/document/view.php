<?php

use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Document $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="document-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
            echo Html::a(Yii::t('app', 'Update'), ['update', 'document_id' => $model->document_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t('app', 'Deactivate');
                $class = "btn btn-danger";
                $question = Yii::t('app', "Are you sure you want to deactivate the document {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t('app', 'Activate');
                $class = "btn btn-warning";
                $question = Yii::t('app', "Are you sure you want to activate the document {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
            }
            echo Html::a($label, ['activate', 'document_id' => $model->document_id], [
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
                'value' => $model->document_id,
            ],
            'code',
            'name',
            [
                'label' => Yii::t('app', 'Intended For'),
                'value' => $model->getFullAction(),
            ],
            [
                'label' => Yii::t('app', 'Apply For'),
                'value' => $model->getFullApply(),
            ],
            [
                'label' => Yii::t('app', 'Has Taxes'),
                'value' => $model->getFullTaxes(),
            ],
            [
                'label' => Yii::t('app', 'Has Expiration'),
                'value' => $model->getFullExpiration(),
            ],
            [
                'label' => Yii::t('app', 'Applies over other transaction?'),
                'value' => $model->getFullOtherTransaction(),
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>