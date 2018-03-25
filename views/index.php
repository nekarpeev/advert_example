<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Advert */
/* @var $advertToCategoryModel app\models\AdvertToCategory */
/* @array $advertCategories app\models\AdvertCategory list */

$this->title = 'Мои объявления';
?>
<div class="advert-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $this->title ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <p>
                <?php $form = ActiveForm::begin(['action' => 'load-page', 'id' => 'advert_category_change_from']); ?>
                <?php echo $form->field($advertToCategoryModel, 'category_id')->dropdownList($advertCategories) ?>
                <?php $form = ActiveForm::end(); ?>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <?= \app\widgets\DataTable::widget([
                    'columns' => [
                        [
                            'label' => 'Категория',
                            'type'  => 'text',
                        ],
                        [
                            'title' => 'title',
                            'type'  => 'text',
                        ],
                        [
                            'title' => 'intro',
                            'type'  => 'text',
                        ],
                        [
                            'label' => 'Организация',
                            'type'  => 'text',
                        ],
                        [
                            'title' => 'status',
                            'type'  => 'text',
                        ],

                    ],
                    'model'   => new \app\models\Advert(),
                    'data'    => $data,
                    'options' => [
                        'class' => 'table table-striped table-bordered table-hover',
                        'id'    => 'tender-ajaxcall5'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
