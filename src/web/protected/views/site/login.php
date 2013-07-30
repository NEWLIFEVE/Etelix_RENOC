<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm
 *  /*<?php echo $form->labelEx($model,'username'); ?>  <?php echo $form->labelEx($model,'password'); ?>
 */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<html><head></head>

<body id="bodylogin">
    <div id="titlelogin">
<font color="white">RE</font><font color="green">NOC</font>
</div>
<div id="login">

<br>
<br><br>
<br>
<font color="Gray" FACE="small fonts" size="5">Ingrese sus Datos</font>
<br>
<br>
<br>
<div id="logprin">
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	<div class="row login">
               <?php echo $form->labelEx($model,''); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row login">
		<?php echo $form->labelEx($model,''); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
<!--		<p class="hint">
			Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>.
		</p>-->
	</div>
    
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Ingresar >')); ?> 
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
</div>
</div>
    
    <div id="minifooter">
      <font color="white">Copyrigth 2013 by</font> <a id="enlacerenoc" href="http://www.sacet.com.ve/">www.sacet.com.ve</a>
      <font color="white"> Legal privacy</font>
    </div>
   </body>
   </html>