<?php
/**
 * Created by PhpStorm.
 * User: Nikita
 * Date: 24.03.2018
 * Time: 22:53
 */

namespace app\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class LoadPageForAdvert
{
    public $categoryId;
    public $columns = [];
    public $order;
    public $search = [];
    public $searchValue;
    public $type;
    public $condition = [];
    public $data_col = [];
    public $orderBy;

    public function getConditionByType()
    {
        if (!empty($this->type) && $this->type == 'all') {
            $this->condition['main'] = ['status' => 1];
        } elseif ($this->type == 'my-advert') {
            $this->condition['main'] = ['user_id' => Yii::$app->user->identity->getId(), 'status' => 1];
        } elseif ($this->type == 'archive') {
            $this->condition['main'] = ['user_id' => Yii::$app->user->identity->getId(), 'status' => 0];
        }
    }

    public function getOrder()
    {

        for ($i = 0, $ien = count($this->order); $i < $ien; $i++) {
            $columnIdx = intval($this->order[$i]['column']);
            $requestColumn = $this->columns[$columnIdx];

            if ($requestColumn['orderable'] == 'true') {
                $dir = $this->order[$i]['dir'] === 'asc' ?
                    'ASC' :
                    'DESC';

                if ($this->data_col[$columnIdx]) {
                    $this->orderBy[$this->data_col[$columnIdx]] = $dir;
                } else {
                    $this->orderBy = ['title' => 'desc'];
                }
            }
        }
    }

    public function getConditionAfterSearch()
    {
        if (isset($this->search) && $this->searchValue != '') {
            $str = $this->searchValue;

            $this->condition['title'] = "title LIKE '%" . $str . "%'";
            $this->condition['intro'] = "intro LIKE '%" . $str . "%'";
            $this->condition['org.law_name'] = "org.law_name LIKE '%" . $str . "%'";
            $this->condition['search'] = true;
        }
    }

    public function prepareRequest($data)
    {
        if (isset($this->categoryId) && $this->categoryId != '') {
            $data = $data->joinWith(['advertToCategoryRelation'])->where(['advert_to_category.category_id' => $this->categoryId]);
        }

        foreach ($this->orderBy as $col => $ord) {
            $data = $data->addOrderBy($col . " " . $ord);
        }

        if ($this->condition['search'] === true) {
            if ($this->type == 'all') {
                $data = $data->joinWith(['advertToProfileRelation', 'profileToOrgRelation']);
                $data = $data->andWhere(['and', $this->condition['main'], ['or', $this->condition['title'], $this->condition['intro'], $this->condition['org.law_name']]])->all();
            } else {
                $data = $data->andWhere(['and', $this->condition['main'], ['or', $this->condition['title'], $this->condition['intro']]])->all();
            }

        } else {
            $data = $data->andWhere($this->condition['main'])->all();
        }
        return $data;
    }

    public function getFilteredData($data)
    {
        $out = [];

        foreach ($data as $item) {
            $row = [];
            /** @var Advert $item */
            $row[] = $item->getCategory()->name;
            $row[] = Html::a($item->title, Url::toRoute(['advert/view', 'id' => $item->id]));
            $row[] = $item->intro;
            $row[] = ($item->user !== null) ? $item->user->getProfileName() : 'Не установлено';
            $row[] = $item->getStatus();

            $out[] = $row;
        }

        return $out;
    }
}