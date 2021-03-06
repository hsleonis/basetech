<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Html;


use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use frontend\models\Menu;
use frontend\models\MenuPageRels;
use frontend\models\Page;
use frontend\models\Enquiry;
use frontend\models\ApplyOnline;

//somrat added
use frontend\models\ProductSpecification;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public function beforeAction($action){
        
        $this->getView()->theme = Yii::createObject([
            'class' => '\yii\base\Theme',
            'pathMap' => ['@app/views' => '@app/web/themes/'.Yii::$app->params['frontend.theme']],
            'baseUrl' => '@web/themes/'.Yii::$app->params['frontend.theme'],
        ]);

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','enquiry','apply_online'],
                'rules' => [
                    [
                        'actions' => ['signup','enquiry','apply_online'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://192.168.1.55:8080'],
                    'Access-Control-Request-Method' => ['GET'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],

            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


        
    
    public function actionSearch_data(){
        $data =  json_decode(utf8_encode(file_get_contents("php://input")), false);
        $term = $data->term;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $response = [];
        /*$pages = (new \yii\db\Query())
                ->select(['id', 'page_title', 'page_desc'])
                ->from('page')
                ->where(['like','page_desc',$term])
                ->all(); */
        $pages = Page::find()->select(['id', 'page_title', 'page_desc','page_slug'])
                            ->where(['like','page_desc',$term])
                            ->all();

        $i=0;
        foreach ($pages as $key => $value) {

            $value->page_desc = str_replace(array("\r\n", "\r", "\n"), "", $value->page_desc);
            $value->page_desc = trim(strip_tags($value->page_desc));


            $response[$i]['page_title'] = $value->page_title;
            $response[$i]['page_url'] = Page::get_parent_pages_backward($value->id,$value->page_slug);
            $response[$i]['page_result'] = substr_count($value->page_desc, $term);
            
            $response[$i]['page_thumb'] = '';
            if(!empty($value->thumbimages)){
                $response[$i]['page_thumb'] = Yii::$app->urlManagerBackEnd->createAbsoluteUrl('/').'uploads/'.$value->thumbimages[0]->image;
            }
            
            $x = explode('.', $value->page_desc);
            
            $response[$i]['page_desc'] = '';
            foreach ($x as $sentence) {
                if(substr_count($sentence, $term) > 0){
                    $response[$i]['page_desc'] = str_replace($term, '<span class="highlight">'.$term.'</span>', $sentence).'.';
                    break;
                }
            }

            $i++;
        }

        return $response;
    }

public function actionEnquiry(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	$response = [];

	$data =  json_decode(utf8_encode(file_get_contents("php://input")), false);
	
	$model = new Enquiry();
	$model->name = $data->name;
	$model->email = $data->email;
	$model->message = $data->message;
	$valid = $model->validate();
	if($valid){
		$message = Yii::$app->mailer->compose();

		$message->setFrom('shimul@dcastalia.com');
		$message->setTo('info@base-technologies.net');
		$message->setSubject('Base technology Inquiry');
		$message->setHtmlBody($model->message);
		if($message->send()){
			$response['result'] = 1;
			$response['msg'] = 'Message sent successfully.';
		}else{
			$response['result'] = 'Error';
			$response['msg'] = 'Sorry couldn\'t send your message. Please try again later.';
		}
	}else{
		$response['result'] = 0;
		$response['msg'] = Html::errorSummary($model);
	}
	

	return $response;
}

public function actionApply_online(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	$response = [];

	$data =  json_decode(utf8_encode(file_get_contents("php://input")), false);
	
	$model = new ApplyOnline();
	$model->name = $data->name;
	$model->address = $data->address;
	$model->email = $data->email;
	$model->department = $data->department;
	$model->qualification = $data->qualification;
	$model->job = $data->job;
	$model->message = $data->message;
	$model->cv = $data->cv;

	$valid = $model->validate();
	if($valid){
		$message = Yii::$app->mailer->compose();
		
		$body = 'Name: '.$data->name.'<br/>';
		$body .= 'Address: '.$data->address.'<br/>';
		$body .= 'email: '.$data->email.'<br/>';
		$body .= 'department: '.$data->department.'<br/>';
		$body .= 'qualification: '.$data->qualification.'<br/>';
		$body .= 'job: '.$data->job.'<br/>';
		$body .= 'message: '.$data->message.'<br/>';

		$message->setFrom('shimul@dcastalia.com');
		$message->setTo('hr@base-technologies.net
');
		$message->setSubject('Base technology Online Application');
		$message->setHtmlBody($body);
		if($model->cv != ''){
        		$message->attach($model->cv);		
		}
		if($message->send()){
			$response['result'] = 1;
			$response['msg'] = 'Appplication submitted successfully.';
		}else{
			$response['result'] = 'Error';
			$response['msg'] = 'Sorry couldn\'t send your Application. Please try again later.';
		}
	}else{
		$response['result'] = 0;
		$response['msg'] = Html::errorSummary($model);
	}
	

	return $response;
}


}
