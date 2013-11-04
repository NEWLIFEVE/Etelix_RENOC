<?php
/* @var $this UsersRenocController */
/* @var $model UsersRenoc */

$this->breadcrumbs=array(
	'Users Renocs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UsersRenoc', 'url'=>array('index')),
	array('label'=>'Create UsersRenoc', 'url'=>array('create')),
	array('label'=>'Update UsersRenoc', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsersRenoc', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsersRenoc', 'url'=>array('admin')),
);
?>

<h1>View UsersRenoc #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'password',
		'email',
		'activkey',
		'superuser',
		'status',
		'create_at',
		'lastvisit_at',
		'id_type_of_user',
	),
)); ?>
