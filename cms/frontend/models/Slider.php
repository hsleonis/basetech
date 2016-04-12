<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;
use frontend\models\SliderImage;

/**
 * This is the model class for table "slider".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Slider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public function getSlider_rel()
    {
        return $this->hasMany(SliderImage::className(), ['slider_id' => 'id']);
    }

    public static function get_slider_1($slider_id){
        $data = Slider::find()->where(['id'=>$slider_id])->one();
        
        $options = [];
        $datas = [];

        $slider_images = SliderImage::find()->where(['slider_id' => $data->id])->all();
        
            
        
        if(!empty($slider_images)){
            $i=0;
            foreach ($slider_images as $key => $value) {
				$options[$i]['id'] = $value->id;
                $options[$i]['page_title'] = $value->image;
                $options[$i]['page_slug'] = $value->short_title;
                $options[$i]['short_desc'] = $value->short_desc;
                
                if(!empty($value->url)){
                    $options[$i]['url'] = $value->url;
                }else{
                    $options[$i]['url'] = Yii::$app->urlManager->createAbsoluteUrl('/').'slider_images/'.$value->image;
                }
                
                $options[$i]['sort_order'] = $value->sort_order;
                
                $i++;
            }
        }
        

        return $options;
        
    }
}
