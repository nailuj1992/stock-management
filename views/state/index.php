<?php

use app\models\entities\State;
use app\models\entities\Country;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'States');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="state-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create State'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'state_id',
            'code',
            'name',
            [
                'attribute' => Yii::t('app', 'Country'),
                'format' => 'raw',
                'value' => function ($model) {
                    $country = Country::findOne(['country_id' => $model->country_id]);
                        return $country->code . " - " . $country->name;
                    },
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, State $model) {
                        return Url::toRoute([$action, 'state_id' => $model->state_id]);
                    }
            ],
        ],
    ]); ?>


</div>
