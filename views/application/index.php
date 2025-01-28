<?php

use app\models\entities\ApplicationCompany;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Applications');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $user_id = Yii::$app->user->identity->user_id;
        if (isset($company_id) && count(ApplicationCompany::getPendingApplicationsForCompany($company_id, $user_id)) == 0) {
            echo Html::a(Yii::t('app', 'Create Application'), ['create', 'company_id' => $company_id], ['class' => 'btn btn-success']);
        }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => Yii::t('app', 'Code'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->company->code;
            }
            ],
            [
                'attribute' => Yii::t('app', 'Name'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->company->name;
            }
            ],
            [
                'attribute' => Yii::t('app', 'Phone'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->company->phone;
            }
            ],
            [
                'attribute' => Yii::t('app', 'Status'),
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
                    return Html::a(Yii::t('app', 'View'), ['application/view', 'application_id' => $model->application_id], ['class' => 'btn btn-outline-primary btn-xs']);
                },
                ]
            ],
        ],
    ]); ?>


</div>