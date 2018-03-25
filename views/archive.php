<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Архив';
?>
<div class="advert-index">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
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
