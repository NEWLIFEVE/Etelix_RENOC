<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="es-VE">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8"/>
    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css" media="jquery" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom-ui.css" media="ui" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />  
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom-ui.css"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/> 
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
    <div class="container" style="padding:-30px;">
        <!--menu-->
        <?php
            $admin = (isset(Yii::app()->user->perfil) and Yii::app()->user->perfil == 'ADMIN') ? true : false ;
            $this->widget('bootstrap.widgets.TbNavbar',array(
                'type'=>'inverse',
                'brand'=>'RENOC',
                'brandUrl'=>'#',
                'collapse'=>true,
                'items'=>array(
                    array(
                        'class'=>'bootstrap.widgets.TbMenu',
                        'htmlOptions'=>array(
                            'class'=>'pull-right'
                            ),
                        'items'=>array(
                            array(
                                'label'=>'Usuarios',
                                'url'=>array('/usersRenoc/admin'),
                                'visible'=>$admin
                                ),
                            array(
                                'label'=>'Login',
                                'url'=>array('/site/login'),
                                'visible'=>Yii::app()->user->isGuest
                                ),
                            array(
                                'label'=>'Salir ('.Yii::app()->user->name.')',
                                'url'=>array('/site/logout'),
                                'visible'=>!Yii::app()->user->isGuest
                                )
                            ),
                        ),
                    ),
                )
            );
        ?>
        <div style="text-align: right;">
            <?php
                if(!Yii::app()->user->isGuest and isset(Yii::app()->user->last_login))
                {
                    echo "Ultimo Acceso: ".Yii::app()->dateFormatter->format("d-M-y h:m a", Yii::app()->user->last_login);
                }
            ?>
        </div>
        <!--fin de menu-->
        <?php echo Yii::app()->bootstrap->init(); ?>
        <?php echo $content; ?>
    </div>
    <div id="footer">
        Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved. Version 1.1.5
    </div>
    <div class="clear"></div>
        <div class='cargandosori'><h6><b>Re-Rate en proceso &nbsp;<img src='/images/cargandosori.gif'width='15px' height='5px'/><b></h6></div>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"/></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
    <script src="http://malsup.github.io/jquery.blockUI.js"></script>
</body>
</html>
