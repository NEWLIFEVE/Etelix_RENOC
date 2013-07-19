<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>

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

        <link href="protected/extensiones/bootstrap/assetscss/bootstrap.min.css" rel="stylesheet" media="screen">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <link href="protected/extensiones/bootstrap/assets/css/bootstrap.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/css/docs.css" rel="stylesheet"/>
            <link href="protected/extensiones/bootstrap/assets/js/google-code-prettify/prettify.css" rel="stylesheet"/>
            <link rel="apple-touch-icon-precomposed" sizes="144x144" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" sizes="114x114" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" sizes="72x72" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png"/>
            <link rel="apple-touch-icon-precomposed" href="protected/extensiones/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png"/>
            <link rel="shortcut icon" href="protected/extensiones/bootstrap/assets/ico/favicon.png"/> 
    
    
    </head>

<body>

<div class="container" id="page">
	        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse', // null or 'inverse'
            'brand' => 'Project name',
            'brandUrl' => '#',
            'collapse' => true, // requires bootstrap-responsive.css
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => array(
                        array('label' => 'Home', 'url' => array('/site/index'), 'active' => true),
                        array('label' => 'Contact', 'url' => array('/site/contact'), 'active' => true),
                        array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        array('label' => 'Dropdown', 'url' => '#', 'items' => array(
                                array('label' => 'Action', 'url' => '#'),
                                array('label' => 'Another action', 'url' => '#'),
                                array('label' => 'Something else here', 'url' => '#'),
                                '---',
                                array('label' => 'NAV HEADER'),
                                array('label' => 'Separated link', 'url' => '#'),
                                array('label' => 'One more separated link', 'url' => '#'),
                            )),
                    ),
                ),
                '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'htmlOptions' => array('class' => 'pull-right'),
                    'items' => array(
                        array('label' => 'Link', 'url' => '#'),
                        array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Dropdown', 'url' => '#', 'items' => array(
                                array('label' => 'Action', 'url' => '#'),
                                array('label' => 'Another action', 'url' => '#'),
                                array('label' => 'Something else here', 'url' => '#'),
                                '---',
                                array('label' => 'Separated link', 'url' => '#'),
                            )),
                    ),
                ),
            ),
        ));
        ?>
       

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

                <script src="http://code.jquery.com/jquery.js"></script>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap.min.js"></script>
                <script type="text/javascript" src="http://platform.twitter.com/widgets.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/jquery.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-transition.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-alert.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-modal.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-dropdown.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-scrollspy.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tab.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-tooltip.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-popover.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-button.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-collapse.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-carousel.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-typeahead.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/bootstrap-affix.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/holder/holder.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/google-code-prettify/prettify.js"/>
                <script src="protected/extensiones/bootstrap/assets/js/application.js"/>
</body>
</html>
