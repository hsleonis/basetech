<?php

namespace frontend\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $page_title
 * @property string $page_slug
 * @property string $short_desc
 * @property string $meta_key
 * @property string $meta_desc
 * @property string $date
 * @property string $page_desc
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $is_archive
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    public $child_pages;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_title', 'page_slug', 'short_desc', 'meta_key', 'meta_desc', 'date', 'page_desc', 'status', 'created_at', 'created_by', 'updated_by', 'is_archive'], 'required'],
            [['date', 'created_at', 'updated_at', 'ext_url'], 'safe'],
            [['page_desc'], 'string'],
            [['created_by', 'updated_by', 'is_archive'], 'integer'],
            [['page_title', 'page_slug', 'short_desc', 'meta_key', 'meta_desc'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_title' => 'Page Title',
            'page_slug' => 'Page Slug',
            'short_desc' => 'Short Desc',
            'ext_url' => 'External Url',
            'meta_key' => 'Meta Key',
            'meta_desc' => 'Meta Desc',
            'date' => 'Date',
            'page_desc' => 'Page Desc',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_archive' => 'Is Archive',
        ];
    }

    public function getPage_rel()
    {
        return $this->hasMany(PageSelfRels::className(), ['page_id' => 'id']);
    }

    public function getPost()
    {
        return $this->hasMany(Post::className(), ['page_id' => 'id'])->orderBy('post.sort_order asc');
    }

    public function getImages()
    {
        return $this->hasMany(PageImageRel::className(), ['page_id' => 'id'])->orderBy('page_image_rel.sort_order asc');
    }
    
    public function getThumbimages()
    {
        return $this->hasMany(PageImageRel::className(), ['page_id' => 'id'])->andWhere(['is_banner'=>1])->orderBy('page_image_rel.sort_order asc');
    }

    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])->viaTable('page_tags_rel', ['page_id' => 'id']);
    }

    public function getTypes()
    {
        return $this->hasMany(PageTypeRel::className(), ['page_id' => 'id']);
    }






    public function findPage_by_slug($slug){
        $page = Page::find()->where(['page_slug'=>$slug,'is_archive'=>0])->one();
        return $page;
    }

    public function findPage_by_id($id){
        $page = Page::find()->where(['id'=>$id,'is_archive'=>0])->one();
        return $page;
    }

    public function findAllPage(){
        $page = Page::find()->where(['is_archive'=>0])->all();
        return $page;
    }

    public function findChild_pages_by_id($id){
        $child_pages = Page::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$id,'is_archive'=>0])->all();
        return $child_pages;
    }







    public static function get_child_pages($parent, $options){
        $datas = [];
        $child_pages = self::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$parent])->all();

        if(!empty($child_pages)){
            foreach ($child_pages as $key => $value) {

                $options[$value->page_slug]['page_data'] = $value;
                $options[$value->page_slug]['page_types'] = $value->types;
                $options[$value->page_slug]['page_tags'] = $value->tags;
                $options[$value->page_slug]['page_images'] = $value->images;
                $options[$value->page_slug]['page_banner'] = '';
                
                if(!empty($value->images))
                foreach ($value->images as $image) {
                    if($image->is_banner)
                        $options[$value->page_slug]['page_banner'] = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$image->image;
                        $image->image = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$image->image;
                }

                foreach ($value->post as $p) {
                    $options[$value->page_slug]['page_post'][$p->post_title]['data'] = $p;
                    $options[$value->page_slug]['page_post'][$p->post_title]['images'] = $p->images;
                }

                $options[$value->page_slug]['child_pages'] = self::get_child_pages($value->id, $datas);
                $datas = [];
            }
        }

        return $options;
    }

    public static function getHierarchy_page() {
        $options = [];
        $dot = '';
        $datas = [];

        $parent_pages = self::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>0])->all();
        
        if(!empty($parent_pages)){
            foreach ($parent_pages as $key => $value) {
                $options[$value->page_title]['page_data']['id'] = $value->id;
                $options[$value->page_title]['page_data']['page_title'] = $value->page_title;
                $options[$value->page_title]['page_data']['page_slug'] = $value->page_slug;
                $options[$value->page_title]['page_data']['short_desc'] = $value->short_desc;
                $options[$value->page_title]['page_data']['page_desc'] = $value->page_desc;
                /*$options[$value->page_title]['page_data']['sort_order'] = $value->sort_order;*/
				
				
                $options[$value->page_title]['page_types'] = $value->types;
                $options[$value->page_title]['page_tags'] = $value->tags;
                $options[$value->page_title]['page_images'] = $value->images;
                $options[$value->page_title]['sort_order'] = $value->sort_order;

                foreach ($value->post as $p) {
                    $options[$value->page_title]['page_post'][$p->post_title]['data'] = $p;
                    $options[$value->page_title]['page_post'][$p->post_title]['images'] = $p->images;
                }
                

                $options[$value->page_title]['child_pages'] = self::get_child_pages($value->id, $datas);
            }
        }
        

        return $options;
    }




    public static function get_child_pages_One_page($parent, $options){
        $datas = [];
        $child_pages = self::find()->joinWith('page_rel')->where(['page_self_rels.parent_page_id'=>$parent])->orderBy('sort_order asc')->all();
        $i = 0;
        if(!empty($child_pages)){
			
			$x=0;
			foreach ($child_pages as $key => $value) {

                $options['menu'][$x]['page_title'] = $value->page_title;
                $options['menu'][$x]['page_slug'] = $value->page_slug;
				$x++;
            }
			
            foreach ($child_pages as $key => $value) {
				$options[$value->page_slug]['page_data']['id'] = $value->id;
                $options[$value->page_slug]['page_data']['page_title'] = $value->page_title;
                $options[$value->page_slug]['page_data']['page_slug'] = $value->page_slug;
                $options[$value->page_slug]['page_data']['short_desc'] = $value->short_desc;
                $options[$value->page_slug]['page_data']['page_desc'] = $value->page_desc;
                //$options[$value->page_slug]['page_data']['sort_order'] = $value->sort_order;
				
                //$options[$value->page_slug]['page_types'] = $value->types;
                //$options[$value->page_slug]['page_tags'] = $value->tags;
                $options[$value->page_slug]['page_images'] = $value->images;
                $options[$value->page_slug]['page_banner'] = '';
                $options[$value->page_slug]['sort_order'] = $value->sort_order;
                
                if(!empty($value->images))
                foreach ($value->images as $image) {
                    if($image->is_banner)
                        $options[$value->page_slug]['page_banner'] = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$image->image;
                        $image->image = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$image->image;
                }
                
                foreach ($value->post as $p) {
                    $options[$value->page_slug]['page_post'][$p->post_title]['data'] = $p;
                    $options[$value->page_slug]['page_post'][$p->post_title]['images'] = $p->images;
                }

                $options[$value->page_slug]['child_pages'] = self::get_child_pages($value->id, $datas);
                $datas = [];
                $i++;
            }
        }

        return $options;
    }


    public static function getOne_page($slug) {
        $options = [];
        $datas = [];

        $parent_pages = self::find()->joinWith('page_rel')->where(['page.page_slug'=>$slug])->all();
        
        if(!empty($parent_pages)){
            foreach ($parent_pages as $key => $value) {
				$options['page_data']['id'] = $value->id;
                $options['page_data']['page_title'] = $value->page_title;
                $options['page_data']['page_slug'] = $value->page_slug;
                $options['page_data']['short_desc'] = $value->short_desc;
                $options['page_data']['page_desc'] = $value->page_desc;
                $options['page_data']['sort_order'] = $value->sort_order;
													   
                //$options['page_types'] = $value->types;
                //$options['page_tags'] = $value->tags;
				$x=0;
                $options['page_images'] = array();
                $options['page_banner'] = '';
                
                $tmp =array();
				if(!empty($value->images)){
					foreach($value->images as $key){
						$tmp['image'] = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$key->image;
						$tmp['sort_order'] = $key->sort_order;
						$tmp['is_gallery'] = $key->is_gallery;
						$tmp['is_banner'] = $key->is_banner;
						$tmp['short_desc'] = $key->short_desc;
						$tmp['short_title'] = $key->short_title;
                        array_push($options['page_images'],$tmp);
                        
                        if($key->is_banner)
                            $options['page_banner'] = Yii::$app->urlManager->createAbsoluteUrl('/').'uploads/'.$key->image;
                        
                        $x++;
					}
				}

                foreach ($value->post as $p) {
                    $options['page_post'][$p->id]['data'] = $p;
                    $options['page_post'][$p->id]['images'] = $p->images;
                }
                

                $options['child_pages'] = self::get_child_pages_One_page($value->id, $datas);
            }
        }
        

        return $options;
    }
    
    
    public static function get_parent_pages_backward_child($id,$page_slug,$options){
        $parent_page = PageSelfRels::find()->where(['page_id'=>$id])->one();
        
        if($parent_page->parent_page_id != 0){
            $data = self::find()->where(['id'=>$parent_page->parent_page_id])->one();
            $options = $data->page_slug.'/'.$options;
            
            $options = self::get_parent_pages_backward_child($data->id, $data->page_slug, $options);
        }else{
            $options = $options;
        }
        
        return $options;
    }
    
    public static function get_parent_pages_backward($id,$page_slug){
        $options = '';

        $parent_page = PageSelfRels::find()->where(['page_id'=>$id])->one();
        if($parent_page->parent_page_id != 0){
            $data = self::find()->where(['id'=>$parent_page->parent_page_id])->one();
            $options = $data->page_slug.'/'.$page_slug;
            
            $options = self::get_parent_pages_backward_child($data->id, $data->page_slug, $options);
        }else{
            $options = $page_slug;
        }
        
        
        return $options;
    }
}
