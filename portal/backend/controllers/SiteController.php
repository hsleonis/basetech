<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use yii\filters\VerbFilter;

use backend\models\ActivityLog;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($action){
        if(Yii::$app->params['setup.status']!="Installed"){
            return $this->redirect(['/setup/index']);
        }
        
        $this->getView()->theme = Yii::createObject([
            'class' => '\yii\base\Theme',
            'pathMap' => ['@app/views' => '@app/web/themes/'.Yii::$app->params['backend.theme']],
            'baseUrl' => '@web/themes/'.Yii::$app->params['backend.theme'],
        ]);

        return parent::beforeAction($action);
    }


    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    public function actionSetup(){
        echo 'Setup Wizard';
    }

    public function actionTimeline(){
        $model = (new \yii\db\Query())
                ->select(['*'])
                ->from('activity_log')
                ->orderBy('id desc')
                ->all(); 
        
        return $this->render('timeline',['model'=>$model]);

    }

    public function actionSearch(){
         
        if (Yii::$app->request->isAjax) {
            $term = $_POST['term'];

            $pages = (new \yii\db\Query())
                ->select(['id', 'page_title', 'updated_at'])
                ->from('page')
                ->where(['like','page_title',$term])
                ->all(); 


            $product_categories = (new \yii\db\Query())
                ->select(['id', 'cat_title', 'updated_at'])
                ->from('product_category')
                ->where(['like','cat_title',$term])
                ->all();

            $products = (new \yii\db\Query())
                ->select(['id', 'title', 'updated_at'])
                ->from('product')
                ->where(['like','title',$term])
                ->all();
            

            $view = $this->renderAjax('search_view', [
                                'pages' => $pages,
                                'product_categories' => $product_categories,
                                'products' => $products
                            ]);

            $response = '';
            $response['view'] = $view;

            return json_encode($response);
        }
    }

    public function actionAccesscontrol(){
        return $this->render('accesscontrol');
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
                'class' => 'yii\captcha\CaptchaAction'
            ],
        ];
    }

    public function actionIndex()
    {
        

        return $this->render('index');
    }

    public function actionLock_screen($previous){
        $this->layout='login_layout';

        if(isset(Yii::$app->user->identity->username)){
            // save current username    
            $username = Yii::$app->user->identity->username;
            $image = \Yii::$app->session->get('user.image');
            // force logout     
            Yii::$app->user->logout();
            // render form lockscreen
            $model = new LoginForm(); 
            $model->username = $username;    //set default value 
            return $this->render('lockScreen', [
                'model' => $model,
                'previous' => $previous,
                'image' => $image
            ]);  
        }
        else{
            return $this->redirect(['login']);
        }
    }

    public function actionLogin($previous="")
    {
        $this->layout='login_layout';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $timestamp = date("Y-m-d h:i:s");
            \Yii::$app->session->set('user.username',$model->username);
            \Yii::$app->session->set('user.last_access',$timestamp);
            \Yii::$app->session->set('user.image',$model->User->image);

            $user = User::find()->where(['id' => $model->User->id])->one();
            $user->last_access = $timestamp;
            $user->save();

            if(!empty($previous)){
                return $this->redirect($previous);
            }
            else{
                return $this->goBack();
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
