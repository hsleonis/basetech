<?php
namespace backend\components;

use yii\helpers\Url;

class GlobalClass extends \yii\base\Component{
    public function init() {
        if(\Yii::$app->session['lang']){
		    \Yii::$app->language = \Yii::$app->session['lang'];
		}else{
            //\Yii::$app->language = 'bn';
        }
        parent::init();
    }

    public static function convert_number($number){
        $bn_digits=array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
        $output = str_replace(range(0, 9),$bn_digits, $number); 

        return $output;
    }


}