<?php
namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\base\Model;
use app\models\ArticleModel;
use frontend\models\TagForm;
use common\models\RelationArticleTagModel;

class ArticleForm extends Model
{
     public $id;
     public $title;
     public $content;
     public $label_img;
     public $cat_id;
     public $tags;
     
     public $_lastError = "";
     
     const SCENARIOS_CREATE = 'create';
     const SCENARIOS_UPDATE = 'update';

     const EVENT_AFTER_CREATE = 'eventAfterCreate';
     const EVENT_AFTER_UPDATE = 'eventAfterUpdate';

     /**
      * 场景设置
      */
     public function scenarios()
     {
        $scenarios = [
            self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
            self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tags']
        ];

        return array_merge(parent::scenarios(), $scenarios);
     }

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

     /**
      * 创建文章
      */
     public function create()
     {
         // 事务
         $transaction = Yii::$app->db->beginTransaction();
         try {
            $model = new ArticleModel();
            $model->setAttributes($this->attributes);
            $model->summary = $this->_getSummary();
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;
            $model->is_valid = ArticleModel::IS_VALID;
            $model->created_at = time();
            $model->updated_at = time();
            if (!$model->save()) {
                throw new \Exception('文章保存失败！');
            }
            $this->id = $model->id;

            $data = array_merge($this->getAttributes(), $model->getAttributes());
            // 调用事件
            $this->_eventAfterCreate($data);

            $transaction->commit();
            return true;
         } catch (\Exception $e) {
            $transaction->rollBack();
            $this->$_lastError = $e->getMessage();
            return false;
         }
         
     }

    public function _getSummary($s = 0, $e = 90, $char = 'utf-8')
    {
        if (empty($this->content)) 
            return null;

        return (mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)), $s, $e, $char));
    }

    /**
     * 文章创建完成后调用的事件
     * 
     */
    public function _eventAfterCreate($data)
    {
        // 添加事件
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);
        // 触发事件
        $this->trigger(self::EVENT_AFTER_CREATE);
    }
    
    public function _eventAddTag($event)
    {
        $tag = new TagForm();
        $tag->tags = $event->data['tags'];
        $tagids = $tag->saveTags();

        // 删除原先的关联关系
        RelationArticleTagModel::deleteAll(['article_id' => $event->data['id']]);

        //批量保存文章和标签的关联关系
        if (!empty($tagids)) {
            foreach ($tagids as $k => $id) {
                $row[$k]['article_id'] = $this->id;
                $row[$k]['tag_id'] = $id;
            }

            //批量插入
            $res = (new Query())->createCommand()
                ->batchInsert(RelationArticleTagModel::tableName(), ['article_id', 'tag_id'], $row)
                ->execute();

            if (!$res) {
                throw new \Exception('关联关系保存失败！');
            }
        }

    }

    
}