<?php

use app\models\TextConstants;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\entities\Document $model */

$this->title = Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_TITLE_UPDATE, [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_DOCUMENTS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'document_id' => $model->document_id]];
$this->params['breadcrumbs'][] = Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE);
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
