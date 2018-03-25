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
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-default panel-primary">
        <div class="panel-heading">
            <?= Yii::t('app/tender', 'Содержание') ?>
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
                <h4><?php echo ($model->getOrgObject()) ? $model->getOrgObject()->law_name : '' ?></h4>
            </div>
            <div class="col-md-12">
                <?= $form->field($advertToCategoryModel, 'category_id')->dropdownList($advertCategories) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'intro')->textarea(['rows' => 2])->widget(\yii\redactor\widgets\Redactor::className(), [
                    'clientOptions' => ['lang' => 'ru']
                ]); ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'description')->textarea(['rows' => 8])->widget(\yii\redactor\widgets\Redactor::className(), [
                    'clientOptions' => ['lang' => 'ru']
                ]); ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'status')->dropdownList([
                    '1' => 'Активно',
                    '0' => 'В архиве',
                ], [
                        'options' => [
                            '1' => ['Selected' => true]
                        ]]
                ) ?>
            </div>
            <div class="col-md-12">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
