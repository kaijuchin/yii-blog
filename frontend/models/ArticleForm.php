<?php
namespace frontend\models;

use yii\base\Model;
class ArticleForm extends Model
{
     public $id;
     public $title;
     public $content;
     public $label_img;
     public $cat_id;
     public $tags;
     
     public $_lastError = "";
     
     public function rules()
     {
        return [
            [['id', 'title', 'content', 'cat_id'], 'required'],
            [['id', 'cat_id'], 'integer'],
            ['title', 'string', 'min' => 4, 'max' => 5],
        ];
     }
     
     public function attributeLabels()
     {
         return [
             'id' => '编号',
             'title' => '标题',
             'content' => '内容',
             'label_img' => '标签图',
             'tags' => '标签',
             'cat_id' => '分类'
         ];
     }
     
}