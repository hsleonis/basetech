<?php

namespace backend\controllers;

use Yii;
use backend\models\Page;
use backend\models\PageSearch;
use backend\models\PageSelfRels;
use backend\models\PageImageRel;
use backend\models\PageTypeRel;
use backend\models\MenuPageRels;
use backend\models\PageTagsRel;
use backend\models\Post;
use backend\models\PageFiles;
use backend\models\PageCategoryRel;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile; 
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

use backend\controllers\MyGlobalClass;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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

    
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'upload_file','delete_uploaded_file',
                        'list', 'list_view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionUpload_files(){
        if( Yii::$app->request->isAjax ){
            $file_model = new PageFiles();

            $file_model_file = UploadedFile::getInstance($file_model, 'file');
            $time=time();
            $userId = \Yii::$app->user->identity->id;
            $file_name = $time.'_'.$userId.'_'.trim($file_model_file->baseName) . '.' . $file_model_file->extension;

            $file_model_file->saveAs('page_files/' . $file_name);

            $file_model->page_id = $_POST['id'];
            $file_model->file = $file_name;
            $file_model->title = $file_model_file->baseName;
            $file_model->ext = $file_model_file->extension;

            if($file_model->save()){
                $response = [];

                $view = $this->renderAjax('_file_upload', [
                                /*'url' => Url::base().'/product_files/' . $file_name,
                                'basename' => $time.'_'.$userId.'_'.$file_model_file->baseName,
                                'id' => $file_model->id,*/
                                'model'=>$file_model
                            ]);


                $response['view'] = $view;

                return json_encode($response);
            }else{
                $response['error'] = $file_model->getErrors();
                $response['id'] = $_POST['id'];
                return json_encode($response);
            }

        }
    }

    public function actionDelete_uploaded_files(){
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];

            $file_model = PageFiles::find()->where(['id'=>$id])->one();
            $name = $file_model->file;

            $response = [];
            if(!empty($file_model) && $file_model->delete()){
                unlink(\Yii::getAlias('@webroot').'/page_files/'.$name);

                $response['files'] = ['msg'=> 'File deleted successfully'];

            }else{
                $response['files'] = ['msg'=> 'File not found in database'];
            }

            return json_encode($response);
            
        }
    }

    public function actionSave_page_sort_order(){
        if( Yii::$app->request->isAjax ){
            $data = $_POST['data'];

            if(!empty($data)){
                $i=1;
                foreach ($data as $key) {
                    $page = Page::find()->where(['id'=>$key])->one();

                    if(!empty($page)){
                        $page->sort_order = $i;
                        $page->save();
                    }
                    $i++;
                }

                $response['result'] = 'success';
                $response['msg'] = 'Sort Order successfully saved.';
                /*$response['post_list'] = $this->renderAjax('list_of_post', [
                                                'product_id' => $product
                                            ]);*/
            }
            else{
                $response['result'] = 'error';
                $response['msg'] = 'Error saving sort order.';
            }

            return json_encode($response);
        }
    }
    

    public function actionArchive_list(){
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        }else{
            $id = 0;
        }
        $pages = Page::find()->where(['page.is_archive'=>1])->all();

        return $this->render('archive_list',[
                'pages'=>$pages
            ]);
    }
    public function actionArchive($id){
        if (Yii::$app->request->isAjax) {

            $model = $this->findModel($id);

            $response = [];
            if(!empty($model)){
                $model->is_archive = 1;
                $model->category = 'ok';
                if($model->save()){

                    Page::archive_all_child($model->id);
                    
                    $response['files'] = ['msg'=> 'Page Archived successfully','id'=> $id];
                }else{
                    var_dump($model->getErrors());
                }

            }else{
                $response['files'] = ['msg'=> 'Error saving data.'];
            }

            return json_encode($response);
            
        }
    }

    public function actionRestore_page($id){
        if (Yii::$app->request->isAjax) {

            $model = $this->findModel($id);
            

            $response = [];
            if(!empty($model)){
                $model->is_archive = 0;
                $model->category = 'ok';

                if($model->save()){

                    /*Page::restore_all_child($model->id);*/
                    
                    $response['files'] = ['msg'=> 'Page Restored successfully','id'=> $id];
                }

            }else{
                $response['files'] = ['msg'=> 'Error saving data.'];
            }

            return json_encode($response);
            
        }
    }


    public function actionSave_image_info(){
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];

            $PageImageRel_model = PageImageRel::find()->where(['id'=>$id])->one();
            

            $response = [];
            if(!empty($PageImageRel_model)){
                $PageImageRel_model->short_title = $_POST['title'];
                $PageImageRel_model->short_desc = '';
                $PageImageRel_model->is_banner = '';
                $PageImageRel_model->is_gallery = ''; 

                if($PageImageRel_model->save()){
                    $response['files'] = ['msg'=> 'Data saved successfully'];
                }

            }else{
                $response['files'] = ['msg'=> 'Error saving data.'];
            }

            return json_encode($response);
            
        }
    }



    public function actionSave_file_info(){
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];

            $PageFileRel_model = PageFiles::find()->where(['id'=>$id])->one();
            

            $response = [];
            if(!empty($PageFileRel_model)){
                $PageFileRel_model->heading = $_POST['heading'];
                $PageFileRel_model->desc = '';
                $PageFileRel_model->is_featured = '';
                $PageFileRel_model->is_yearly = ''; 

                if($PageFileRel_model->save()){
                    $response['files'] = ['msg'=> 'Data saved successfully'];
                }

            }else{
                $response['files'] = ['msg'=> 'Error saving data.'];
            }

            return json_encode($response);
            
        }
    }

    public function actionSave_page_image_sort_order(){
        if( Yii::$app->request->isAjax ){
            $data = $_POST['data'];
            $page = $_POST['page'];

            if(!empty($data)){
                $i=1;
                foreach ($data as $key) {
                    $PageImageRel = PageImageRel::find()->where(['page_id'=>$page,'id'=>$key])->one();

                    if(!empty($PageImageRel)){
                        $PageImageRel->sort_order = $i;
                        $PageImageRel->save();
                    }
                    $i++;
                }

                $response['result'] = 'success';
                $response['msg'] = 'Sort Order successfully saved.';

            }
            else{
                $response['result'] = 'error';
                $response['msg'] = 'Error saving sort order.';
            }

            return json_encode($response);
        }
    }



    public function actionList($id=0){
        $model = Page::find()->where(['id'=>$id])->one();
        $pages_q = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$id,'page.is_archive'=>0])->orderBy('sort_order asc')->all();

        $pages = array();
        if(!empty($pages_q)){
            foreach ($pages_q as $key) {
                $child_count = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$key->id,'page.is_archive'=>0])->count();
                $key->child_count = $child_count;

                array_push($pages, $key);
            }
        }

        if($id==0){
            $breadcumb = '';
        }else{
            $breadcumb = array_reverse(Page::get_breadcumb($id));
        }
        

        
        return $this->render('page_list', ['model'=>$model, 'pages'=>$pages,'breadcumb'=>$breadcumb]);
    }

    public function actionList_view($id)
    {
        $child_pages_q = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$id,'page.is_archive'=>0])->all();

        $breadcumb = array_reverse(Page::get_breadcumb($id));


        $child_pages = array();
        if(!empty($child_pages_q)){
            foreach ($child_pages_q as $key) {
                $child_count = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$key->id,'page.is_archive'=>0])->count();
                $key->child_count = $child_count;

                array_push($child_pages, $key);
            }
        }


        return $this->render('list_view', [
            'model' => $this->findModel($id),
            'child_pages' => $child_pages,
            'breadcumb' => $breadcumb
        ]);
    }

    public function actionUpload_file(){
        if( Yii::$app->request->isAjax ){
            $model = new Page();
            $rel_model = new PageImageRel();
            $count = PageImageRel::find()->where(['page_id' => $_POST['id']])->count();

            $uploaded_image = UploadedFile::getInstance($rel_model, 'image');
            $time=time();

            $uploaded_image->saveAs('uploads/' . $time.$uploaded_image->baseName . '.' . $uploaded_image->extension);
            Image::thumbnail('@webroot/uploads/'.$time.$uploaded_image->baseName . '.' . $uploaded_image->extension, 200, 130)
                    ->save(Yii::getAlias('@webroot').'/uploads/thumb/'.$time.$uploaded_image->baseName . '.' . $uploaded_image->extension, ['quality' => 80]);

            $rel_model->page_id = $_POST['id'];
            $rel_model->image = $time.$uploaded_image->baseName . '.' . $uploaded_image->extension;
            $rel_model->sort_order = $count+1;

            if($rel_model->save()){
                $response = [];

                $this->layout = 'blank';
                $view = $this->renderAjax('_image_upload', [
                                'url' => Url::base().'/uploads/' . $time.$uploaded_image->baseName . '.' . $uploaded_image->extension,
                                'basename' => $time.$uploaded_image->baseName,
                                'id' => $rel_model->id,
                                'model' => $rel_model
                            ]);

                $response['files'][] = [
                    'name' => $time.$uploaded_image->name,
                    'type' => $uploaded_image->type,
                    'size' => $uploaded_image->size,
                    'url' => Url::base().'/uploads/' . $time.$uploaded_image->baseName . '.' . $uploaded_image->extension,
                    'deleteUrl' => Url::to(['delete_uploaded_file', 'file' => $uploaded_image->baseName . '.' . $uploaded_image->extension]),
                    'deleteType' => 'DELETE'
                ];

                /*$response['view'] = $view;*/
                $response['view'] = $view;
                $response['base'] = $time.$uploaded_image->baseName;

                return json_encode($response);
            }

        }
    }

    /*public function actionDelete_uploaded_file(){
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];
            unlink(\Yii::getAlias('@webroot').'/uploads/'.$name);

            $response = [];
            $response['files'][] = [
                    'name' => 'dddad.jpg',
                    'type' => 'JPEG',
                    'size' => 2000,
                    'url' => '',
                ];

            return json_encode($response);
        }
    }*/

    public function actionDelete_uploaded_file(){
        if (Yii::$app->request->isAjax) {
            $id = $_POST['id'];

            $image_rel_model = PageImageRel::find()->where(['id'=>$id])->one();
            $name = $image_rel_model->image;

            $response = [];
            if(!empty($image_rel_model) && $image_rel_model->delete()){
                unlink(\Yii::getAlias('@webroot').'/uploads/'.$name);

                
                $response['files'] = ['msg'=> 'File deleted successfully'];

            }else{
                $response['files'] = ['msg'=> 'Image File not found in database'];
            }

            return json_encode($response);
            
        }
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        $model= $this->findModel($id);
        $child_pages_q = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$id])->all();

        $breadcumb = array_reverse(Page::get_breadcumb($id));


        $child_pages = array();
        if(!empty($child_pages_q)){
            foreach ($child_pages_q as $key) {
                $child_count = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$key->id])->count();
                $key->child_count = $child_count;

                array_push($child_pages, $key);
            }
        }


        MyGlobalClass::log_activity('Visited page - ID: '.$model->id.' and page title: '.$model->page_title);
        return $this->render('view', [
            'model' => $model,
            'child_pages' => $child_pages,
            'breadcumb' => $breadcumb
        ]);
    }



    public function actionCreate()
    {
        $model = new Page();  
        $PageSelfRels_model = new PageSelfRels();
        $PageCategoryRel_model = new PageCategoryRel();
        
        $_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
        $_SESSION['KCFINDER']['uploadURL'] = Url::base()."/kcupload/"; // URL for the uploads folder
        $_SESSION['KCFINDER']['uploadDir'] = Yii::getAlias('@app')."/web/kcupload/"; // path to the uploads folder

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){

                $PageSelfRels_model->page_id = $model->id;
                $PageSelfRels_model->parent_page_id = array(0);

                $PageCategoryRel_model->page_id = $model->id;
                $PageCategoryRel_model->category_id = $model->category;
                $PageCategoryRel_model->save();

                if(Page::update_page_self_rel($PageSelfRels_model)){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{
                    var_dump($PageSelfRels_model->getErrors());
                }
            }
            else{
                var_dump($model->getErrors());
            }
            
        }
        return $this->render('create', [
                'model' => $model, 'PageSelfRels_model'=>$PageSelfRels_model
            ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $PageSelfRels_model_q = PageSelfRels::find()->where(['page_id'=>$id])->all();
        $Page_cat_rel = PageCategoryRel::find()->where(['page_id'=>$id])->one();
        $model->category = $Page_cat_rel->category_id;
        $PageImageRel_model = new PageImageRel();
        $PageCategoryRel_model = new PageCategoryRel();

        $posts = Post::find()->where(['page_id'=>$id])->all();

        $_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
        $_SESSION['KCFINDER']['uploadURL'] = Url::base()."/kcupload/"; // URL for the uploads folder
        $_SESSION['KCFINDER']['uploadDir'] = Yii::getAlias('@app')."/web/kcupload/"; // path to the uploads folder
        
        if(empty($PageSelfRels_model_q)){
            $PageSelfRels_model = new PageSelfRels();
        }else{
            $PageSelfRels_model = new PageSelfRels();
            $PageSelfRels_model->parent_page_id = ArrayHelper::getColumn($PageSelfRels_model_q, 'parent_page_id');
        }

        $image_rel_model = PageImageRel::find()->where(['page_id'=>$id])->orderBy('sort_order asc')->all();

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){

                $PageCategoryRel_model->deleteAll(['page_id' => $id]);
                $PageCategoryRel_model->page_id = $model->id;
                $PageCategoryRel_model->category_id = $model->category;
                $PageCategoryRel_model->save();

                $PageSelfRels_model->page_id = $model->id;
                if(empty($PageSelfRels_model->parent_page_id)){

                    PageSelfRels::deleteAll(['page_id' => $PageSelfRels_model->page_id]);

                    $PageSelfRels_model->parent_page_id = 0;
                    $PageSelfRels_model->save();
                }else{
                   
                    Page::update_page_self_rel($PageSelfRels_model);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                echo '<pre>';
                var_dump($_POST['Page']);
                var_dump($model->getErrors());
            }
            
        } 
        
        $breadcumb = array_reverse(Page::get_breadcumb($id));

        return $this->render('update', [
            'model' => $model, 'PageSelfRels_model'=>$PageSelfRels_model,'image_rel_model'=>$image_rel_model,
            'breadcumb' => $breadcumb, 'PageImageRel_model'=>$PageImageRel_model,
            'posts' => $posts,
        ]);
    }

    public function actionDelete_page($id){
        if (Yii::$app->request->isAjax) {

            $model = $this->findModel($id);

            PageSelfRels::updateAll(['parent_page_id' => 0], 'parent_page_id = '.$id);
            PageSelfRels::deleteAll(['page_id' => $id]);

            PageCategoryRel::deleteAll(['page_id' => $id]);

            $images = PageImageRel::findAll(['page_id' => $id]);
            if(!empty($images)){
                foreach ($images as $key => $value) {
                    unlink(\Yii::getAlias('@webroot').'/uploads/'.$value->image);
                }
            }
            PageImageRel::deleteAll(['page_id' => $id]);

            Post::deleteAll(['page_id' => $id]);

            $response = [];
            if($model->delete()){

                $response['files'] = ['msg'=> 'Page Deleted successfully','id'=> $id];

            }else{
                $response['files'] = ['msg'=> 'Error saving data.'];
            }

            return json_encode($response);

        }
    }

    
    public function actionDelete($id)
    {

        PageSelfRels::updateAll(['parent_page_id' => 0], 'parent_page_id = '.$id);
        PageSelfRels::deleteAll(['page_id' => $id]);

        $images = PageImageRel::findAll(['page_id' => $id]);
        if(!empty($images)){
           foreach ($images as $key => $value) {
                unlink(\Yii::getAlias('@webroot').'/uploads/'.$value->image);
            } 
        }
        PageImageRel::deleteAll(['page_id' => $id]);
        PageCategoryRel::deleteAll(['page_id' => $id]);


        Post::deleteAll(['page_id' => $id]);
        

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
