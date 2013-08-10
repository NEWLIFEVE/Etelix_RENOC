<?php
/* @var $this UsersRenocController */
/* @var $model UsersRenoc */

$this->breadcrumbs=array(
	'Users Renocs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UsersRenoc', 'url'=>array('index')),
	array('label'=>'Manage UsersRenoc', 'url'=>array('admin')),
);
?>

<h1>Create UsersRenoc</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>