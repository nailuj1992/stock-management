<?php

use app\models\entities\ApplicationCompany;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProviderListUserNotBelong */

$this->title = Yii::t('app', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <span><?= Yii::t('app', 'These are the companies where you are included.') ?></span>
        <p>
            <?= Html::a(Yii::t('app', 'Create Company'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->company_id;
                    },
            ],
            'company.code',
            'company.name',
            'company.phone',
            [
                'attribute' => Yii::t('app', 'Position'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullRole();
                    },
            ],
            [
                'attribute' => Yii::t('app', 'Your status'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullStatus();
                    },
            ],
            [
                'attribute' => Yii::t('app', 'Company status'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->getFullStatus();
                    },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{select} {view} {update} {list-users} {create-user}',
                'buttons' => [
                    'select' => function ($url, $model, $key) {
                            if (Utils::hasCompanySelected() && Utils::getCompanySelected() == $model->company->company_id) {
                                return null;
                            }
                            $label = Yii::t('app', 'Select');
                            $question = Yii::t('app', "Are you sure you want to select the company {name}?", ['name' => $model->company->name]);
                            return Html::a($label, ['select', 'company_id' => $model->company_id], [
                                'class' => 'btn btn-outline-primary btn-xs',
                                'data' => [
                                    'confirm' => $question,
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    'view' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'View'), ['view', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-info btn-xs']);
                        },
                    'update' => function ($url, $model, $key) {
                            return Utils::isOwnerOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'Update'), ['update', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-secondary btn-xs']) : '';
                        },
                    'list-users' => function ($url, $model, $key) {
                            return Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'List Users'), ['list-users', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-info btn-xs']) : '';
                        },
                    'create-user' => function ($url, $model, $key) {
                            return Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'Create User'), ['create-user', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-success btn-xs']) : '';
                        },
                ]
            ],
        ],
    ]); ?>

    <h2><?= Html::encode("Other companies to apply") ?></h2>

    <div>
        <span>These are the companies where you can apply to enter.</span>
        <p>
            <?= Html::a('List Applications', ['/application/list'], ['class' => 'btn btn-primary']) ?>
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProviderListUserNotBelong,
        'columns' => [
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->company_id;
                },
            ],
            [
                'attribute' => 'Code',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->code;
                }
            ],
            [
                'attribute' => 'Name',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->name;
                }
            ],
            [
                'attribute' => 'Phone',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->phone;
                }
            ],
            [
                'attribute' => 'Company status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getFullStatus();
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{list-applications} {create-application}',
                'buttons' => [
                    'list-applications' => function ($url, $model, $key) {
                        return Html::a('List', ['application/', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-primary btn-xs']);
                    },
                    'create-application' => function ($url, $model, $key) {
                        $user_id = \Yii::$app->user->identity->user_id;
                        if (count(ApplicationCompany::getPendingApplicationsForCompany($model->company_id, $user_id)) == 0) {
                            return Html::a('Create', ['application/create', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-success btn-xs']);
                        }
                    },
                ]
            ],
        ],
    ]); ?>


</div>