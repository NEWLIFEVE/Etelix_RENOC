<?php
/* @var $this UsersRenocController */
/* @var $model UsersRenoc */

$this->breadcrumbs=array(
	'Users Renocs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UsersRenoc', 'url'=>array('index')),
	array('label'=>'Create UsersRenoc', 'url'=>array('create')),
	array('label'=>'View UsersRenoc', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UsersRenoc', 'url'=>array('admin')),
);
?>

<h1>Update UsersRenoc <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>