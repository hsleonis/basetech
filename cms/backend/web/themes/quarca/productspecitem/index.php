<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSpecItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Spec Items';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pane">
    <div class="product-spec-item-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Create Product Spec Item', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php \yii\widgets\Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        <?php \yii\widgets\Pjax::end(); ?>

    </div>
</div>