<?php

namespace backend\models;

use Yii;

class PageCategoryRel extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'page_category_rel';
    }


    public function rules()
    {
        return [
            [['page_id', 'category_id'], 'required'],
            [['page_id', 'category_id'], 'integer']
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'category_id' => 'Category ID',
        ];
    }

    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }
}
