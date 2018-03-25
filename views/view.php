<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advert */

$this->title = $model->title;
?>
<div class="advert-view">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Просмотр объявления: <?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <div class="row">
        <?= $this->render('_view-form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
