<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "relation_article_tags".
 *
 * @property int $id 自增ID
 * @property int $article_id 文章ID
 * @property int $tag_id 标签ID
 */
class RelationArticleTagModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relation_article_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'tag_id'], 'integer'],
            [['article_id', 'tag_id'], 'unique', 'targetAttribute' => ['article_id', 'tag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
