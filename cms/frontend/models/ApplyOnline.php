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
            [['name', 'address', 'email', 'department', 'qualification', 'job', 'message'], 'required'],
            ['email', 'email'],
	    [['name', 'qualification'],'string']
        ];
    }


}
