<?php
        $this->registerJsFile($this->theme->baseUrl."/vendor/js/required.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]); 
        $this->registerJsFile($this->theme->baseUrl."/assets/js/quarca.js", ['depends' => [\yii\web\JqueryAsset::className()]]); 


        $this->registerJsFile($this->theme->baseUrl."/assets/js/init/init.dashboard-new.js", ['depends' => [\yii\web\JqueryAsset::className()]]);  

?>
<?php
/* @var $this yii\web\View */

$this->title = 'Dashboard';
use yii\helpers\Url;

?>

<div class="col-md-12" style="margin-top:15px;">
    <div class="row">
        
        <!--<div class="col-md-3">
            <a class="btn btn-sm btn-primary" style="width:100%;" href="<?php // Url::toRoute(['json/get_landing_section']); ?>">Create Landing Json</a>
        </div>-->

        <div class="col-md-3">
            <a class="btn btn-sm btn-primary" style="width:100%;" href="<?= Url::toRoute(['json/create_json_page']); ?>">Create Json</a>
        </div>

        <div class="col-md-3">
            <a class="btn btn-sm btn-primary" style="width:100%;" href="<?php Url::toRoute(['json/get_news']); ?>">Create News Json</a>
        </div>

        <div class="col-md-3">
            <a class="btn btn-sm btn-primary" style="width:100%;" href="<?php Url::toRoute(['json/get_landing_slider']); ?>">Create Home Slider Json</a>
        </div>

    </div>
</div>

