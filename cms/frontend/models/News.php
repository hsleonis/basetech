<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $desc
 * @property string $ext_url
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'slug', 'desc', 'ext_url', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status'], 'required'],
            [['desc', 'ext_url'], 'string'],
            [['created_by', 'updated_by', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'slug'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'desc' => 'Desc',
            'ext_url' => 'Ext Url',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }
    
    public static function get_news_1(){
                
        $options = [];
        $datas = [];

        $news_data_r = News::find()->where(['status' => 1])->all();
        
            
        
        if(!empty($news_data_r)){
            $i=0;
            foreach ($news_data_r as $key => $value) {
				$options[$i]['id'] = $value->id;
                $options[$i]['title'] = $value->title;
                $options[$i]['slug'] = $value->slug;
                $options[$i]['desc'] = $value->desc;
                $options[$i]['ext_url'] = $value->ext_url;
                
                $i++;
            }
        }
        

        return $options;
        
    }
    
}
