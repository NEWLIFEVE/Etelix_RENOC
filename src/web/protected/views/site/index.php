<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>
<div id="container">
    <div id="espacio"  class="hidden-phone hidden-tablet visible-desktop">
    </div>
    <div id="barraVerde" class="span4 hidden-phone hidden-tablet visible-desktop" >
    </div>
    <div class="row">
        <div  class="span8">
            <div id="fondo" >
                <?php if (isset($this->breadcrumbs)): ?>
                <?php 
                    $this->widget('zii.widgets.CBreadcrumbs', array(
                        'links'=>$this->breadcrumbs,
                        )
                    );
                ?>
                <!-- breadcrumbs -->
                <?php endif ?>
                <div id="capa">
                    <div id="barraVerdeC">
                    </div>
                    <div id="barrablanca">
                    </div>
                    <div id="one" href="#" class="">
                        <ul class="thumbnails">
                            <li class="span3">
                                <div id="claseboot" href="#" class="a">
                                    <h1>RUTINARIOS &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;  &nbsp;
                                        <a id="flecha-forward" href="/site/rutinarios"  rel="tooltip" title="esta es la consulta basica por hora y fecha" class="tooltip-test ">></a>
                                    </h1>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div id="two">
                        <ul class="thumbnails">
                            <li class="span3">
                                <div id="claseboot" class="a">
                                    <h1> ESPECIFICOS &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  &nbsp;
                                        <a id="flecha-forward" href="/site/especificos" rel="tooltip" title="aqui se muestra informacion de data por fecha y hora especifica" class="tooltip-test">></a>
                                    </h1>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div  id="three">
                        <ul class="thumbnails">
                            <li class="span3">
                                <div id="claseboot" class="a">
                                    <h1> PERSONALIZADOS &nbsp;  &nbsp;&nbsp;
                                        <a id="flecha-forward" href="/site/personalizados" rel="tooltip" title="puede realizar una busqueda filtrada de data, por fecha, operadora, entre otras" class="tooltip-test">></a>
                                    </h1>
                                </div>
                            </li>
                        </ul> 
                    </div>
                    <div id="segundacapa">
                    </div>
                </div>
            </div>
        </div>
<<<<<<< HEAD
        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> SACET 
            All Rights Reserved. 
        </div> 
        <!-- page -->
        <!--container-->   
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
</body>
</html>
        <!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
                    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css"/>
                    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom-ui.css"/>-->
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="/resources/demos/style.css" />
        <script src="/js/jquery-1.9.1.js"></script>
        <script src="/js/jquery-ui.js"></script>
<!--        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
 <script src=“http://code.jquery.com/jquery-1.5.js“></script>

=======
    </div>
</div> 
<div class="div">
</div>
<div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved. 
</div>
<!-- page -->
<!--container-->
>>>>>>> 8eeff929de888375c05e22864a9f301d18172c1c



 






