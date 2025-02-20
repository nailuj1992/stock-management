<?php

use app\models\TextConstants;
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
            echo Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'document_id' => $model->document_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                $class = "btn btn-danger";
                $question = Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                $class = "btn btn-warning";
                $question = Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
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
                'label' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_INTENDED_FOR),
                'value' => $model->getFullAction(),
            ],
            [
                'label' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_APPLY_FOR),
                'value' => $model->getFullApply(),
            ],
            [
                'label' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_TAXES),
                'value' => $model->getFullTaxes(),
            ],
            [
                'label' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_EXPIRATION),
                'value' => $model->getFullExpiration(),
            ],
            [
                'label' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_OTHER_TRANSACTION),
                'value' => $model->getFullOtherTransaction(),
            ],
            [
                'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>