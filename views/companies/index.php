<?php

use app\models\entities\ApplicationCompany;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProviderListUserNotBelong */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <span><?= Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TEXT) ?></span>
        <p>
            <?= Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_CREATE), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_POSITION),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullRole();
                    },
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_YOUR_STATUS),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullStatus();
                    },
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_STATUS),
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
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_SELECT);
                            $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_CONFIRMATION_SELECT, ['name' => $model->company->name]);
                            return Html::a($label, ['select', 'company_id' => $model->company_id], [
                                'class' => 'btn btn-primary btn-xs',
                                'data' => [
                                    'confirm' => $question,
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    'view' => function ($url, $model, $key) {
                            return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-primary btn-xs']);
                        },
                    'update' => function ($url, $model, $key) {
                            return Utils::isOwnerOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-secondary btn-xs']) : '';
                        },
                    'list-users' => function ($url, $model, $key) {
                            return Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_LIST_USERS), ['list-users', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-info btn-xs']) : '';
                        },
                    'create-user' => function ($url, $model, $key) {
                            return Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_CREATE_USER), ['create-user', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-success btn-xs']) : '';
                        },
                ]
            ],
        ],
    ]); ?>

    <h2><?= Html::encode(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_OTHER_TITLE)) ?></h2>

    <div>
        <span><?= Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_OTHER_TEXT) ?></span>
        <p>
            <?= Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_SHOW_APPLICATIONS), ['/application/list'], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_CODE),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->code;
                }
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->name;
                }
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->phone;
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
                'template' => '{list-applications} {create-application}',
                'buttons' => [
                    'list-applications' => function ($url, $model, $key) {
                        return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['application/', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-primary btn-xs']);
                    },
                    'create-application' => function ($url, $model, $key) {
                        $user_id = \Yii::$app->user->identity->user_id;
                        if (count(ApplicationCompany::getPendingApplicationsForCompany($model->company_id, $user_id)) == 0) {
                            return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_CREATE), ['application/create', 'company_id' => $model->company_id], ['class' => 'btn btn-outline-success btn-xs']);
                        }
                    },
                ]
            ],
        ],
    ]); ?>


</div>