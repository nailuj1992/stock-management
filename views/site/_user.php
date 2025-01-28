<?php
use app\models\Constants;
use app\models\entities\ApplicationCompany;
use app\models\entities\Company;
use app\models\Utils;
use app\models\entities\UserCompany;
use yii\helpers\Html;

?>
<div class="jumbotron text-center bg-transparent mt-5 mb-5">
    <h1 class="display-4"><?= Yii::t('app', 'Welcome back {name}', ['name' => Yii::$app->user->identity->name]) ?>!</h1>

    <?php
    if (Utils::isActiveUser()) {
        echo '<p class="lead">We are ready to begin our tasks.</p>';
        if (count(UserCompany::getCompaniesForUser()) == 0) {
            echo '<p>' . Html::a(Yii::t('app', 'Create your first company!'), ['/companies/create'], ['class' => 'btn btn-lg btn-success']) . '</p>';
            if (count(ApplicationCompany::getPendingApplicationsForUser()) == 0) {
                ?>
                <span>
                    <?= Yii::t('app', 'Or you can apply to enter into a company ') . Html::a(Yii::t('app', 'over here!'), ['/application/list'], ['class' => '']) ?>
                </span>
                <?php
            }
        }
        if (count(ApplicationCompany::getPendingApplicationsForUser()) > 0) {
            ?>
            <span>
                <?= Yii::t('app', 'It seems you have applied for any company. Check your status ') . Html::a(Yii::t('app', 'over here!'), ['/application/list'], ['class' => '']) ?>
            </span>
            <?php
        }
    } else {
        if (Utils::isInactiveUser()) {
            ?>
            <div class="alert alert-danger">
                <?= nl2br(Html::encode(Yii::t('app', Constants::MESSAGE_ACTIVATE_ACCOUNT))) ?>
            </div>
            <?php
        } else {
            ?>
            <p class="lead">
                <?= Yii::t('app', 'It seems that you are {status}', ['status' => Yii::$app->user->identity->getFullStatus()]) ?>
            </p>
            <?php
        }
    }
    ?>
</div>

<div class="body-content">
    <?php
    if (Utils::isActiveUser()) {
        $session = Yii::$app->session;
        ?>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <h2><?= Yii::t('app', 'Companies') ?></h2>

                <p><?= Yii::t('app', 'Here you can see the companies assigned to you.') ?></p>
                <p><?= Yii::t('app', 'You can select a company over here.') ?></p>

                <p>
                    <?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/companies/'], ['class' => 'btn btn-outline-info']) ?>
                </p>
            </div>
            <?php
            if (count(UserCompany::getCompaniesForUser()) > 0 && $session->has(Constants::SELECTED_COMPANY_ID)) {
                $company_id = $session->get(Constants::SELECTED_COMPANY_ID);
                $company = Company::findCompany($company_id);
                ?>
                <div class="col-lg-6 mb-3">
                    <h2><?= Yii::t('app', 'Selected Company:') ?></h2>
                    <h3><?= $company->name ?></h3>
                    <h6>NIT: <?= $company->code ?></h6>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        if (count(UserCompany::getCompaniesForUser()) > 0) {
            if (!$session->has(Constants::SELECTED_COMPANY_ID)) {
                ?>
                <div class="alert alert-danger">
                    <?= nl2br(Html::encode(Yii::t('app', Constants::MESSAGE_SELECT_COMPANY))) ?>
                </div>
                <?php
            } else {
                $company_id = $session->get(Constants::SELECTED_COMPANY_ID);
                if (Utils::belongsToCompany($company_id)) {
                    ?>
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t('app', 'Documents') ?></h2>

                            <p><?= Yii::t('app', 'Here you can see the documents in your company.') ?></p>

                            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/document/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t('app', 'Products') ?></h2>

                            <p><?= Yii::t('app', 'Here you can see the products in your company.') ?></p>

                            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/product/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t('app', 'Warehouses') ?></h2>

                            <p><?= Yii::t('app', 'Here you can see the warehouses in your company.') ?></p>

                            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/warehouse/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t('app', 'Suppliers') ?></h2>

                            <p><?= Yii::t('app', 'Here you can see the suppliers for your company.') ?></p>

                            <p><?= Html::a(Yii::t('app', 'Continue {symbol}', ['symbol' => '&raquo;']), ['/supplier/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
    ?>
</div>