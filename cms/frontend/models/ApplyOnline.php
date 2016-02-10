<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


class ApplyOnline extends Model
{
    public $name;
    public $address;
    public $email;
    public $department;
    public $qualification;
    public $job;
    public $message;
    public $cv;

    

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            ['email', 'email'],
	    [['name', 'qualification'],'string']
        ];
    }


}
