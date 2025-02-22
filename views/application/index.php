<?php

use app\models\entities\ApplicationCompany;
use app\models\TextConstants;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_APPLICATIONS_TITLE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $user_id = Yii::$app->user->identity->user_id;
        if (isset($company_id) && count(ApplicationCompany::getPendingApplicationsForCompany($company_id, $user_id)) == 0) {
            echo Html::a(Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_BUTTON_CREATE_APPLICATION), ['create', 'company_id' => $company_id], ['class' => 'btn btn-success']);
        }
        ?>
    </p>


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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['application/view', 'application_id' => $model->application_id], ['class' => 'btn btn-outline-primary btn-xs']);
                },
                ]
            ],
        ],
    ]); ?>


</div>