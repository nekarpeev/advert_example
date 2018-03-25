<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;

/* @var $this yii\web\View */
/* @var $model app\models\Advert */
/* @var $advertToCategoryModel app\models\AdvertToCategory */
/* @array $advertCategories app\models\AdvertCategory list */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="panel-body">
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <?= Yii::t('app/tender', 'Содержание') ?> <!-- TODO: translater -->
            <div class="pull-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-info btn-xs" data-toggle="collapse" data-target="#dl">
                        <i class="fa fa-chevron-up fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="dl" class="panel-body collapse in">
            <div class="col-md-12">
                <p><strong><?= $model->getAttributeLabel('category_id') ?></strong>: <?= $model->getCategory()->name ?>
                </p>
            </div>
            <div class="col-md-12">
                <p>
                    <strong><?= $model->getAttributeLabel('title') ?></strong>: <?= $model->title ? $model->title : '-' ?>
                </p>
            </div>
            <div class="col-md-12">
                <p>
                    <strong><?= $model->getAttributeLabel('intro') ?></strong>: <?= $model->intro ? $model->intro : '-' ?>
                </p>
            </div>
            <div class="col-md-12">
                <p>
                    <strong><?= $model->getAttributeLabel('description') ?></strong>: <?= $model->description ? $model->description : '-' ?>
                </p>
            </div>
            <div class="col-md-12">
                <p>
                    <strong><?= $model->getAttributeLabel('status') ?></strong>: <?= $model->getStatus() ? $model->getStatus() : '-' ?>
                </p>
            </div>
        </div>
    </div>
    <?php
    if ($model->isCurrentUser() === true) {
        echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-success']);
        echo ' ';
        echo Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger'
        ]);
    } else {
        echo Html::a('Связаться с продавцом', ['notify', 'id' => $model->id], ['class' => 'btn btn-success']);
    }


    \yii\bootstrap\Modal::begin([
        'id' => 'modal-alert',
        'header' => '<h4>'.Yii::t('app/dict','Внимание!').'</h4>'
    ]);
    echo "<div id='modalContent-alert'></div>";
    \yii\bootstrap\Modal::end();
    ?>
</div>
