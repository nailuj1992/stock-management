<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Supplier $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_SUPPLIERS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
            echo Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                $class = "btn btn-danger";
                $question = Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                $class = "btn btn-warning";
                $question = Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
            }
            echo Html::a($label, ['activate', 'supplier_id' => $model->supplier_id], [
                'class' => $class,
                'data' => [
                    'confirm' => $question,
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?php
    $city = City::findOne(['city_id' => $model->city]);
    $state = State::findOne(['state_id' => $city->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '#',
                'value' => $model->supplier_id,
            ],
            'code',
            'name',
            'email:email',
            'phone',
            'address',
            [
                'label' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
                'value' => $city->name,
            ],
            [
                'label' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                'value' => $state->name,
            ],
            [
                'label' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                'value' => $country->name,
            ],
            [
                'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>