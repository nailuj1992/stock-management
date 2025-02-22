<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use app\models\TextConstants;
use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        <?= Yii::t(TextConstants::APP, TextConstants::MESSAGE_ERROR_WEB_SERVER_REQUEST) ?>
    </p>
    <p>
        <?= Yii::t(TextConstants::APP, TextConstants::MESSAGE_ERROR_CONTACT_US) ?>
    </p>

</div>