<?php
namespace frontend\controllers;
/**
 * 文章表单类
 */

use Yii;
use common\models\CatModel;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\ArticleForm;
use frontend\controllers\base\BaseController;


class ArticleController extends BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'upload', 'ueditor'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create', 'upload', 'ueditor'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['get'],
                    'create' => ['get', 'post'],
                    'upload' => ['get', 'post'],
                    'ueditor' => ['get', 'post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'common\widgets\file_upload\UploadAction',
                'config' => [
                    'imagePathFormat' => '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
                ]
            ],
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 创建文章
     * @return string
     */
    public function actionCreate()
    {
        $model = new ArticleForm();
        // 定义场景
        $model->setScenario(ArticleForm::SCENARIOS_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->create()) {
                Yii::$app->section->setFlash('warning', $model->_lastError);
            } else {
                return $this->redirect(['post/view', 'id' => $model->id]);
            }
        }
        // 获取所有分类
        $cat = CatModel::getAllCats();
        return $this->render('create', ['model' => $model, 'cat' => $cat]);
    }
}