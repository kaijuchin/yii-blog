<?php
namespace frontend\controllers;
/**
 * 文章表单类
 */

use frontend\controllers\base\BaseController;
use frontend\models\ArticleForm;
use common\models\CatsModel;


class ArticleController extends BaseController
{
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
        // 获取所有分类
        $cat = CatsModel::getAllCats();
        return $this->render('create', ['model' => $model, 'cat' => $cat]);
    }
}