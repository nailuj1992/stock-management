<?php
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;

?>
<div class="jumbotron text-center bg-transparent mt-5 mb-5">
    <h1 class="display-4"><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_ADMIN_WELCOME_APP) ?></h1>

    <p class="lead"><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_ADMIN_MESSAGE_WELCOME_APP) ?></p>
</div>

<div class="body-content">

    <?php if (Utils::isActiveUser()) { ?>
        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_COUNTRIES_TITLE) ?></h2>

                <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_COUNTRIES_PARAGRAPH) ?></p>

                <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/country/'], ['class' => 'btn btn-outline-info']) ?>
                </p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_STATES_TITLE) ?></h2>

                <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_STATES_PARAGRAPH) ?></p>

                <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/state/'], ['class' => 'btn btn-outline-info']) ?>
                </p>
                </p>
            </div>
            <div class="col-lg-4">
                <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_CITIES_TITLE) ?></h2>

                <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_CITIES_PARAGRAPH) ?></p>

                <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/city/'], ['class' => 'btn btn-outline-info']) ?>
                </p>
            </div>
        </div>
    <?php } else {
        if (Utils::isInactiveUser()) {
            ?>
            <div class="alert alert-danger text-center">
                <?= nl2br(Html::encode(Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_ACTIVATE_ACCOUNT))) ?>
            </div>
            <?php
        } else {
            ?>
            <p class="lead">
                <?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_USER_STATUS, ['status' => Yii::$app->user->identity->getFullStatus()]) ?>
            </p>
            <?php
        }
    } ?>
</div>