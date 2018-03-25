<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advert */
/* @var $advertToCategoryModel app\models\AdvertToCategory */
/* @array $advertCategories app\models\AdvertCategory list */

$this->title = 'Создание объявления';
?>
<div class="advert-create">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <div class="row">
        <?= $this->render('_form', [
            'model'                 => $model,
            'advertCategories'      => $advertCategories,
            'advertToCategoryModel' => $advertToCategoryModel,
        ]) ?>
    </div>
</div>

