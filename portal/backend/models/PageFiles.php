<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "page_files".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $file
 * @property string $title
 */
class PageFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'file', 'title', 'ext'], 'required'],
            [['page_id', 'is_featured', 'is_yearly'], 'integer'],
            [['file', 'title', 'ext', 'heading'], 'string', 'max' => 255],
            [['desc'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'file' => 'File',
            'title' => 'Title',
            'heading' => 'Heading',
            'desc' => 'Short Desc',
            'is_featured' => 'Is Featured',
            'is_yearly' => 'Is Yearly',
        ];
    }
}
