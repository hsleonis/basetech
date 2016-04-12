<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use kartik\file\FileInput;

    $this->title = 'Company Profile';
    
?>


<?= FileInput::widget([
    'model' => $file_modal,
    'attribute' => 'file_name',
    'options'=>[
        'multiple'=>false
    ],
    'pluginOptions' => [
        'uploadUrl' => Url::to(['/page/upload_companyprofile']),
        'uploadExtraData' => [
            'id' => '1',
            'cat_id' => 'Nature'
        ],
        'maxFileCount' => 10,
        'allowedFileExtensions' => ['pdf', 'doc','docx'],

    ],
    'pluginEvents' => [
        'fileuploaded'=>'function(event, data, previewId, index){
            $(".uploaded_images").append(data.response.view);
            $(".sortable").sortable();
        }',
        'filebatchuploadcomplete' => 'function(event, files, extra){
            $(".fileinput-remove-button").click();
            $(".sortable").sortable();
        }',

    ]
]);
?>