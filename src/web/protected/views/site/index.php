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
                </div>
            </div>
        </div>
    </div>
</div> 
<div class="div">
</div>
<div id="footer">
    Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved. 
</div>
<!-- page -->
<!--container-->



 






