<?php
/* @var $this UsersRenocController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users Renocs',
);

$this->menu=array(
	array('label'=>'Create UsersRenoc', 'url'=>array('create')),
	array('label'=>'Manage UsersRenoc', 'url'=>array('admin')),
);
?>

<h1>Users Renocs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
