<?php
namespace backend\controllers;
/**
 * ���¿�����
 */
use Yii;
use backend\controllers\base\BaseController;

class ArticleController extends BaseController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}