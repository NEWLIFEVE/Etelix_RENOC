<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<html>
    <head>
        <meta charset="utf-8"/>

        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css" />
    </head>
    <body>
        <div id="container">
            <div id="espacio"  class="hidden-phone"> 
            </div>
            <div id="barraVerde" class="span4 hidden-phone" ></div>
            <div class="row">
                <div  class="span8">
                    <div id="fondo" >   
                        <?php if (isset($this->breadcrumbs)): ?>
                            <?php
                            $this->widget('zii.widgets.CBreadcrumbs', array(
                                'links' => $this->breadcrumbs,
                            ));
                            ?><!-- breadcrumbs -->
                        <?php endif ?>
                        <div id="capa"  href="index.php" class="">
                            <div id="barraVerdeC" ></div>
                            <div id="barrablanca" ></div>
                            <div  id="one" href="#" class="" > 
                                <ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="#" class="a">
                                            <h1> RUTINARIOS &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;  &nbsp;
                                                <a id="flecha" href="#"  rel="tooltip" 
                                                   title="esta es la consulta basica por hora y fecha" 
                                                   class="tooltip-test ">></a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul>             
                            </div>
                            <div id="two"><ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="index.php" class="a">
                                            <h1> ESPECIFICOS &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  &nbsp;
                                                <a id="flecha" href="index.php" rel="tooltip" 
                                                   title="aqui se muestra informacion de data por fecha y hora especifica"
                                                   class="tooltip-test"> > </a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul>  </div>
                            <div  id="three"><ul class="thumbnails">
                                    <li class="span3">
                                        <div id="claseboot" href="index.php?r=usersRenoc/view&id=1" class="a">
                                            <h1> PERSONALIZADOS &nbsp;  &nbsp;&nbsp;
                                                <a id="flecha" href="index.php?r=usersRenoc/view&id=1" rel="tooltip" 
                                                   title="puede realizar una busqueda filtrada de data, por fecha, operadora, entre otras" 
                                                   class="tooltip-test"> > </a>
                                            </h1>
                                        </div>
                                    </li>
                                </ul> 
                            </div>

                            <div id="segundacapa" ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div">

                <span class="span">

                    <div  id="atras"><ul class="thumbnails">
                            <li class="span1">
                                <div id="icono"  href="#" class="">
                                    <rigth>  <h1> <a href="index.php" class="icon-backward "></a></h1></rigth>
                                </div>
                            </li>
                        </ul> 
                    </div>
                    <div class="Rotate-90">RUTINARIOS</div>
                    <div id="barraVerde1" class="span2" >
                    </div> 
                    <div id="instruccion" class="span1">
                        Seleccione una fecha
                    </div>  

                    <div id="datepicker" class="span6">
                    </div>



                    <div id="exportar"  class="span1">
                        <a href="#" rel="tooltip" 
                           title="presione el icono para enviar los reportes seleccionados a su correo electronico" 
                           class="tooltip-test"><img src="/images/mail.png"  width="95px" height="95px" onclick="miFuncion()"
                                                  value="Activar Función"> 
                        </a>
                    </div>
                    <div id="exportar1"  class="span1">
                        <a href="#" rel="tooltip" 
                           title="con esta opcion, exporta los registros seleccionados a documentos en formato excel" 
                           class="tooltip-test"><img src="/images/excel.png"  width="75px" height="75px"onclick="miFuncion()"
                                                  value="Activar Función">
                        </a>
                    </div>
                    <div id="barraVerde2" class="span2" >
                    </div>
                    <div id="instruccion2" class="span1">
                        Seleccione tipos de reportes
                    </div>  
                    <div id="tablagris" class="span2" >

                        <table class="">
                            <tr><td><h3><label class="checkbox">
                                            <input type="checkbox" onclick="marcar(this);" />
                                        </label></h3></td>
                                <td id="tdtodos"><h4>Todos</h4></td></tr>
                            <tr><td><label class="checkbox">
                                        <input type="checkbox"> 
                                    </label></td><td id="td1"><h4>Alto Impacto Retail(+1$)</h4></td></tr>
                            <tr><td><label class="checkbox">
                                        <input type="checkbox"> 
                                    </label></td><td id="td2"><h4>Alto Impacto(+10$)</h4></td></tr>
                            <tr><td><label class="checkbox">
                                        <input type="checkbox"> 
                                    </label></td><td id="td3"><h4>Otros</h4></td></tr>
                            <tr><td><label class="checkbox">
                                        <input type="checkbox"> 
                                    </label></td><td id="td4"><h4>Otros</h4></td></tr>
                            <tr><td><label class="checkbox">
                                        <input type="checkbox"> 
                                    </label></td><td id="td5"><h4>Otros</h4></td></tr>
                            <tr> <td></td> <td>
                                    <input type="hidden"  id="datepicker_value"/>

                                </td></tr>
                        </table>
                    </div>    

                </span>
            </div>
        </div> 
        <!-- page -->
        <!--container-->   
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>

</body>
</html>