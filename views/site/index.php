<?php
use app\models\Constants;
use app\models\Utils;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
<style>
    .row-center {
        display: flex;
        justify-content: center;
    }

    .flex-box-right {
        display: flex;
        flex-direction: column;
        align-items: end;
        align-self: center;
    }

    .flex-box-left {
        display: flex;
        flex-direction: column;
        align-items: start;
        align-self: center;
    }

    .flex-box-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        align-self: center;
    }
</style>
<div class="site-index">

    <?php
    if (Yii::$app->user->isGuest) {
        echo $this->render('_guest', []);
    } else {
        if (Utils::hasPermission(Constants::ROLE_ADMIN)) {
            echo $this->render('_admin', []);
        } elseif (Utils::hasPermission(Constants::ROLE_USER)) {
            echo $this->render('_user', []);
        }
    }
    ?>
</div>