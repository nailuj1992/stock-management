<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Supplier $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
            echo Html::a(Yii::t('app', 'Update'), ['update', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-primary']);

            if ($model->isActive()) {
                $label = Yii::t('app', 'Deactivate');
                $class = "btn btn-danger";
                $question = Yii::t('app', "Are you sure you want to deactivate the supplier {code} - {name}?", ['code' => $model->code, 'name' => $model->name]);
            } elseif ($model->isInactive()) {
                $label = Yii::t('app', 'Activate');
                $class = "btn btn-warning";
                $question = Yii::t('app', "Are you sure you want to activate the supplier {code} - {name}?", ['code' => $model->code, 'name' => $model->name]);
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
                'label' => Yii::t('app', 'City'),
                'value' => $city->name,
            ],
            [
                'label' => Yii::t('app', 'State'),
                'value' => $state->name,
            ],
            [
                'label' => Yii::t('app', 'Country'),
                'value' => $country->name,
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>