<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="protected/extensions/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet"></link>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link href="protected/extensions/bootstrap/assetscss/bootstrap.min.css" rel="stylesheet" media="screen"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href="protected/extensions/bootstrap/assets/css/bootstrap.css" rel="stylesheet"/>
        <link href="protected/extensions/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="protected/extensions/bootstrap/assets/css/docs.css" rel="stylesheet"/>
        <link href="protected/extensions/bootstrap/assets/js/google-code-prettify/prettify.css" rel="stylesheet"/>
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="protected/extensions/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png"/>
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="protected/extensions/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png"/>
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="protected/extensions/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png"/>
        <link rel="apple-touch-icon-precomposed" href="protected/extensions/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png"/>
        <link rel="shortcut icon" href="protected/extensions/bootstrap/assets/ico/favicon.png"/> 


    </head>
    <body>
        <div class="container" style="padding:-30px;">
            <!--menu-->
            <?php
            
            $admin = (isset(Yii::app()->user->perfil) and Yii::app()->user->perfil == 'ADMIN') ? true : false ;    
            $this->widget('bootstrap.widgets.TbNavbar', array(
                'type' => 'inverse', // null or 'inverse'
                'brand' => 'RENOC',
                'brandUrl' => '#',
                'collapse' => true, // requires bootstrap-responsive.css
                'items' => array(
                    array(
                        'class' => 'bootstrap.widgets.TbMenu',
                        'htmlOptions' => array('class' => 'pull-right'),
                        'items' => array(
array('label'=>'Usuarios', 'url'=>array('/usersRenoc/admin'), 'visible' => $admin),
array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
array('label'=>'Salir ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                            
                            
                        ),
                    ),
                ),
            ));
            ?>
            <div style="text-align: right;">
<?php if(!Yii::app()->user->isGuest and isset(Yii::app()->user->last_login)){
echo "Ultimo Acceso: ".Yii::app()->dateFormatter->format("d-M-y h:m a", Yii::app()->user->last_login);} ?>
</div>
            <!--fin de menu-->
            <?php echo Yii::app()->bootstrap->init(); ?>
            <div id="header">
                <div id="logo"></div>
            </div> 
 <?php echo $content; ?>
        </div>
        <div class="clear"></div>
        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> ETELIX DEV.<br/>
            All Rights Reserved.   <br/>
        </div><!-- footer1 -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"/>
        <script src="protected/extensions/bootstrap/assets/js/jquery.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-transition.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-alert.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-modal.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-dropdown.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-scrollspy.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-tab.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-popover.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-button.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-carousel.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-collapse.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-tooltip.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-typeahead.js"/>
        <script src="protected/extensions/bootstrap/assets/js/bootstrap-affix.js"/>
        <script src="protected/extensions/bootstrap/assets/js/holder/holder.js"/>
        <script src="protected/extensions/bootstrap/assets/js/google-code-prettify/prettify.js"/>
        <script src="protected/extensions/bootstrap/assets/js/application.js"/>
    </body>
</html>

