<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\AdvertCategory;
use app\models\Profile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "advert".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $status
 *
 * @property AdvertCategory $advertCat
 */
class Advert extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['description', 'title', 'intro'], 'string'],
            [['intro'], 'string', 'max' => Yii::$app->params['advertIntroLimit']],
            [['intro', 'title', 'status', 'user_id', 'status'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'Организация',
            'title'       => 'Заголовок',
            'intro'       => 'Краткое описание',
            'description' => 'Полное описание',
            'status'      => 'Статус',
            'category_id' => 'Категория',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * @return bool
     */
    public function isCurrentUser()
    {
        $currentUserId = Yii::$app->user->identity->getId();

        if ($currentUserId !== $this->user->id) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'user_id'])->one();
    }

    public function getOrgObject()
    {
        return $this->hasMany(Org::className(), ['id' => 'org_id'])->via('advertToProfileRelation')->one();
    }

    /**
     * Relation Profile to Org
     * @return \yii\db\ActiveQuery
     */
    public function getProfileToOrgRelation()
    {
        return $this->hasMany(Org::className(), ['id' => 'org_id'])->via('advertToProfileRelation');
    }

    /**
     * Relation Advert to Profile
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertToProfileRelation()
    {
        return $this->hasMany(Profile::className(), ['user_id' => 'user_id']);
    }

    /**
     * Relation Advert to Category
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertToCategoryRelation()
    {
        return $this->hasMany(AdvertToCategory::className(), ['advert_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasMany(AdvertCategory::className(), ['id' => 'category_id'])->via('advertToCategoryRelation')->one();
    }

    public function getStatus()
    {
        return ($this->status === self::STATUS_ACTIVE) ? 'Активно' : 'В архиве';
    }


}


