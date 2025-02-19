<?php

use app\models\TextConstants;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_SHOW_APPLICATIONS);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_CODE),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->code;
                    }
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->name;
                    }
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->phone;
                    }
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_STATUS),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullStatus();
                    },
            ],
            [
                'attribute' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_SENT_AT),
                'format' => ['date', 'php:d/m/Y H:i:s'],
                'value' => function ($model) {
                        return $model->created_at;
                    },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                            return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'application_id' => $model->application_id, 'show_list' => true], ['class' => 'btn btn-outline-primary btn-xs']);
                        },
                ]
            ],
        ],
    ]); ?>

</div>