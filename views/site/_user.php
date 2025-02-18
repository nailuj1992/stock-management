<?php
use app\models\entities\ApplicationCompany;
use app\models\entities\Company;
use app\models\TextConstants;
use app\models\Utils;
use app\models\entities\UserCompany;
use yii\helpers\Html;

?>
<div class="jumbotron text-center bg-transparent mt-5 mb-5">
    <h1 class="display-4">
        <?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_WELCOME_APP, ['name' => Yii::$app->user->identity->name]) ?>
    </h1>

    <?php
    if (Utils::isActiveUser()) {
        echo '<p class="lead">' . Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_WELCOME_APP) . '</p>';
        if (count(UserCompany::getCompaniesForUser()) == 0) {
            echo '<p>' . Html::a(Yii::t(TextConstants::APP, TextConstants::INDEX_CREATE_COMPANY), ['/companies/create'], ['class' => 'btn btn-lg btn-success']) . '</p>';
            if (count(ApplicationCompany::getPendingApplicationsForUser()) == 0) {
                ?>
                <span>
                    <?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_ENTER_COMPANY_1) . ' ' . Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_ENTER_COMPANY_2), ['/application/list'], ['class' => '']) ?>
                </span>
                <?php
            }
        }
        if (count(ApplicationCompany::getPendingApplicationsForUser()) > 0) {
            ?>
            <span>
                <?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_PENDING_APPLICATIONS_1) . ' ' . Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_MESSAGE_PENDING_APPLICATIONS_2), ['/application/list'], ['class' => '']) ?>
            </span>
            <?php
        }
    } else {
        if (Utils::isInactiveUser()) {
            ?>
            <div class="alert alert-danger">
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
    }
    ?>
</div>

<div class="body-content">
    <?php
    if (Utils::isActiveUser()) {
        ?>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_COMPANIES_TITLE) ?></h2>

                <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_COMPANIES_PARAGRAPH_1) ?></p>
                <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_COMPANIES_PARAGRAPH_2) ?></p>

                <p>
                    <?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/companies/'], ['class' => 'btn btn-outline-info']) ?>
                </p>
            </div>
            <?php
            if (count(UserCompany::getCompaniesForUser()) > 0 && Utils::hasCompanySelected()) {
                $company_id = Utils::getCompanySelected();
                $company = Company::findCompany($company_id);
                ?>
                <div class="col-lg-6 mb-3">
                    <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_SELECTED_COMPANY) ?></h2>
                    <h3><?= $company->name ?></h3>
                    <h6><?= Yii::t('app', 'NIT') ?>: <?= $company->code ?></h6>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        if (count(UserCompany::getCompaniesForUser()) > 0) {
            if (!Utils::hasCompanySelected()) {
                ?>
                <div class="alert alert-danger">
                    <?= nl2br(Html::encode(Yii::t(TextConstants::APP, TextConstants::MESSAGE_SELECT_COMPANY))) ?>
                </div>
                <?php
            } else {
                $company_id = Utils::getCompanySelected();
                if (Utils::belongsToCompany($company_id)) {
                    ?>
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_DOCUMENTS_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_DOCUMENTS_PARAGRAPH) ?></p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/document/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_PRODUCTS_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_PRODUCTS_PARAGRAPH) ?></p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/product/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_WAREHOUSES_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_WAREHOUSES_PARAGRAPH) ?></p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/warehouse/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_SUPPLIERS_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_SUPPLIERS_PARAGRAPH) ?></p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/supplier/'], ['class' => 'btn btn-outline-info']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_TRANSACTIONS_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_TRANSACTIONS_PARAGRAPH) ?>
                            </p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/transaction/'], ['class' => 'btn btn-outline-info']) ?>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_EXISTENCES_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_EXISTENCES_PARAGRAPH) ?>
                            </p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/transaction/existences'], ['class' => 'btn btn-outline-info']) ?>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <h2><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_KARDEX_TITLE) ?></h2>

                            <p><?= Yii::t(TextConstants::INDEX, TextConstants::INDEX_KARDEX_PARAGRAPH) ?>
                            </p>

                            <p><?= Html::a(Yii::t(TextConstants::INDEX, TextConstants::INDEX_CONTINUE, ['symbol' => '&raquo;']), ['/transaction/kardex'], ['class' => 'btn btn-outline-info']) ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
    ?>
</div>