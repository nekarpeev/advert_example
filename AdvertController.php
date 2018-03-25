<?php

namespace app\controllers;


use Yii;
use app\components\LoadPageForAdvert;
use app\models\User;
use app\models\Advert;
use app\models\AdvertCategory;
use app\models\AdvertToCategory;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\components\Mailer;

/**
 * AdvertController implements the CRUD actions for Advert model.
 */
class AdvertController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs'     => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createDate'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updateDate'],
                ],
                'value'      => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * List adverts by company.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->userCan('advert.view');

        $advertToCategoryModel = new AdvertToCategory();
        $advertCategories = AdvertCategory::getList();

        return $this->render('index', [
            'data'                  => [],
            'advertCategories'      => $advertCategories,
            'advertToCategoryModel' => $advertToCategoryModel,
        ]);
    }

    public function actionLoadPage()
    {
        $loadPageForAdvert = new LoadPageForAdvert;

        $params = \Yii::$app->request->get();

        $loadPageForAdvert->categoryId = $params['category_id'];
        $loadPageForAdvert->columns = $params['columns'];
        $loadPageForAdvert->order = $params['order'];
        $loadPageForAdvert->search = $params['search'];
        $loadPageForAdvert->searchValue = $params['search']['value'];
        $loadPageForAdvert->type = isset($params['type']) ? $params['type'] : 'all';

        $loadPageForAdvert->data_col = [
            '1' => 'title',
            '2' => 'intro',
            '3' => 'org.law_name',
        ];

        $loadPageForAdvert->getConditionByType();
        $loadPageForAdvert->getOrder();
        $loadPageForAdvert->getConditionAfterSearch();

        $data = Advert::find();
        $data = $loadPageForAdvert->prepareRequest($data);

        $out = $loadPageForAdvert->getFilteredData($data);

        echo json_encode([
            'draw'            => $params['draw'],
            'recordsTotal'    => count($out),
            'condition'       => $loadPageForAdvert->condition,
            'recordsFiltered' => count($out),
            'data'            => $out,
        ]);

        \Yii::$app->end();
    }

    /**
     * List all adverts.
     * @return mixed
     */
    public function actionList()
    {
        $this->userCan('advert.view');

        $advertToCategoryModel = new AdvertToCategory();
        $advertCategories = AdvertCategory::getList();

        return $this->render('list', [
            'data'                  => [],
            'advertCategories'      => $advertCategories,
            'advertToCategoryModel' => $advertToCategoryModel,
        ]);
    }

    /**
     *  adverts in archive.
     * @return mixed
     */
    public function actionArchive()
    {
        $this->userCan('advert.view');

        return $this->render('archive', [
            'data' => [],
        ]);
    }

    /**
     * Displays a single Advert model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->userCan('advert.view');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Advert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->userCan('advert.create');

        $model = new Advert();
        $advertToCategoryModel = new AdvertToCategory();

        $advertCategories = AdvertCategory::getList();

        if ($model->load(Yii::$app->request->post()) && $advertToCategoryModel->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->identity->getId();
            $model->save();

            $advertToCategoryModel->advert_id = $model->id;
            $advertToCategoryModel->save();


            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model'                 => $model,
            'advertCategories'      => $advertCategories,
            'advertToCategoryModel' => $advertToCategoryModel,
        ]);
    }

    /**
     * Updates an existing Advert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->userCan('advert.update', ['item' => $model]);

        $advertToCategoryModel = AdvertToCategory::findModel($id);
        $advertCategories = AdvertCategory::getList();

        if ($model->load(Yii::$app->request->post()) && $advertToCategoryModel->load(Yii::$app->request->post())) {
            $model->save();
            $advertToCategoryModel->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model'                 => $model,
            'advertCategories'      => $advertCategories,
            'advertToCategoryModel' => $advertToCategoryModel,
        ]);
    }

    /**
     * Deletes an existing Advert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id = null)
    {
        $model = $this->findModel($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        $this->userCan('advert.update', ['item' => $model]);

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Advert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionNotify($id)
    {
        $model = $this->findModel($id);
        $user = User::findOne(['id' => $model->user_id]);
        if (!$user) return false;

        $result = Mailer::mailAdvertIsInterested($model, $user);

        if ($result === true) {
            Yii::$app->session->setFlash('success', 'Уведомление отправленно!');
        } else {
            Yii::$app->session->setFlash('error', 'Произошла ошибка при отправке уведомления');
        }

        return $this->redirect(Url::to(['advert/view', 'id' => $id]));
    }
}
