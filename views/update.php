<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advert */

$this->title = 'Редактирование объявления: ' . $model->title;
?>
<div class="advert-update">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>



    <?= $this->render('_form', [
        'model'                 => $model,
        'advertCategories'      => $advertCategories,
        'advertToCategoryModel' => $advertToCategoryModel,
    ]) ?>

</div>
