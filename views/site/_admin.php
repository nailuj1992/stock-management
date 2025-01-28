<?php
use yii\helpers\Html;

?>
<div class="jumbotron text-center bg-transparent mt-5 mb-5">
    <h1 class="display-4"><?= Yii::t('app', 'Administrator Control Panel') ?></h1>

    <p class="lead"><?= Yii::t('app', 'Be careful of what you are going to do.') ?></p>
</div>

<div class="body-content">

    <div class="row">
        <div class="col-lg-4 mb-3">
            <h2><?= Yii::t('app', 'Countries') ?></h2>

            <p><?= Yii::t('app', 'Panel to manage the list of countries.') ?></p>

            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/country/'], ['class' => 'btn btn-outline-info']) ?>
            </p>
        </div>
        <div class="col-lg-4 mb-3">
            <h2><?= Yii::t('app', 'States') ?></h2>

            <p><?= Yii::t('app', 'Panel to manage the list of states.') ?></p>

            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/state/'], ['class' => 'btn btn-outline-info']) ?>
            </p>
            </p>
        </div>
        <div class="col-lg-4">
            <h2><?= Yii::t('app', 'Cities') ?></h2>

            <p><?= Yii::t('app', 'Panel to manage the list of cities.') ?></p>

            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/city/'], ['class' => 'btn btn-outline-info']) ?>
            </p>
        </div>
    </div>
</div>