<?php
use app\models\Constants;
use app\models\Utils;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;
?>
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
