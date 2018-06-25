<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "tags".
 *
 * @property int $id 自增ID
 * @property string $tag_name 标签名称
 * @property int $article_num 关联文章数
 */
class TagModel extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_num'], 'integer'],
            [['tag_name'], 'string', 'max' => 255],
            [['tag_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tag_name' => 'Tag Name',
            'article_num' => 'Article Num',
        ];
    }
}
