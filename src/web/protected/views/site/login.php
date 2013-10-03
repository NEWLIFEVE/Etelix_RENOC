<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
$this->pageTitle=Yii::app()->name.' - Login';
$this->breadcrumbs=array(
	'Login',
	);
?>
<div class="bodylogin">
	<div class="cuadro">
		<p class="titulo">
			<font color="white">RE</font><font color="green">NOC</font>
		</p>
		<div class="cuadro_login">
			<p class="login_titulo">
				<font color="Gray"  size="5">Ingrese sus Datos</font>
			</p>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
					),
				)
			);
			?>
			<div class="login">
				<?php echo $form->labelEx($model,''); ?>
				<?php echo $form->textField($model,'username'); ?>
			</div>
			<div class="login">
				<?php echo $form->labelEx($model,''); ?>
				<?php echo $form->passwordField($model,'password'); ?>
			</div>
			<div class="row rememberMe">
				<div class="botonLogin">
					<?php echo $form->checkBox($model,'rememberMe', array('class'=>'custom-checkboxLogin')); ?>
					<?php echo $form->label($model,'rememberMe'); ?>
					<?php echo $form->error($model,'rememberMe'); ?>
					<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'success', 'label'=>'Ingresar >')); ?>
					<?php echo $form->error($model,'username'); ?>
					<?php echo $form->error($model,'password'); ?>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
		<div id="minifooter">
			<font color="white">Copyrigth 2013 by</font> <a id="enlacerenoc" href="http://www.sacet.com.ve/">www.sacet.com.ve</a>
			<font color="white"> Legal privacy</font>
		</div>
	</div>
</div>