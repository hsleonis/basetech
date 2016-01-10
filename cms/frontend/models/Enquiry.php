<?php

namespace frontend\models;

use Yii;
use yii\base\Model;


class Enquiry extends Model
{
    public $name;
    public $email;
    public $message;

    

    public function rules()
    {
        return [
            [['name', 'email', 'message'], 'required'],
            ['email', 'email'],
        ];
    }


}
