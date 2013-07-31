<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<script>

    $(function() {
        
        $("#mail").click(function() {
          alert("Enviar por Correo");
//          $.ajax({
//                url: '../../AltoImpacto.php',
//                //data: str,
//                type: 'post',
//                success: function(data){
//                    alert('aqui voy');
//                    alert(data);
//                }
//            });
        });
        $("#excel").click(function() {
          alert("Exportar a Excel");
        });
        
    });

</script>
<html>
    <head>
        <meta charset="utf-8"/>

<!--        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css"/>
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom-ui.css"/>-->
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
                                                <a id="flecha-forward" href="#"  rel="tooltip" 
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
                                                <a id="flecha-forward" href="index.php" rel="tooltip" 
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
                                                <a id="flecha-forward" href="index.php?r=usersRenoc/view&id=1" rel="tooltip" 
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
<!--/**********************************************INICIO CAPA RUTINARIOS*****************************************************************************************/-->                   
 
            <div class="div">

                <span class="span">

                    <div  id="atras"><ul class="thumbnails">
                            <li class="span1">
                                <div id="icono"  href="#" class="">
                                    <rigth>  <h1> <a id="flecha-backward" href="index.php" class="tooltip-test"><</a></h1></rigth>
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


                    <div id="mail"  class="span1">
                        <a href="#" rel="tooltip" 
                           title="Enviar Reportes a su Correo Electronico" 
                           class="tooltip-test"><img src="/images/mail.png"  width="95px" height="95px"
                                                  value="Activar Función"> 
                        </a>
                    </div>
                    <div id="excel"  class="span1">
                        <a href="#" rel="tooltip" 
                           title="Exportar Reportes en Excel" 
                           class="tooltip-test"><img src="/images/excel.png"  width="75px" height="75px"
                                                  value="Activar Función">
                        </a>
                    </div>
                    <div id="barraVerde2" class="span2" >
                    </div>
                    <div id="instruccion2" class="span1">
                        Seleccione los Reportes
                    </div>  
                    <div id="tablagris" class="span2" >

                        <table class="">
                            <tr>
                                <td width="47">
                                    <h3>
                                        <label class="checkbox">
<!--                                            <input type="checkbox" class="custom-checkbox" onclick="marcar(this);" />-->
                                            <input type="checkbox" value="todos" id="todos" class="custom-checkbox" name="todos" onClick="marcar(this);">
                                            <label for="todos"><h4></h4></label>
                                        </label>
                                    </h3>
                                </td>
                                <td width="209" id="tdtodos">
                                    <label for="todos"><h4>Todos</h4></label>
                                </td>
                            </tr>
                            <tr>  
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" value="AIR" id="AIR" class="custom-checkbox" name="AIR"> 
                                        <label for="AIR"><h4></h4></label>
                                    </label>
                                </td>
                                <td id="td1">
                                    <label for="AIR"><h4>Alto Impacto Retail(+1$)</h4></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" value="AI10" id="AI10" class="custom-checkbox" name="AI10"> 
                                        <label for="AI10"><h4></h4></label>
                                    </label>
                                </td>
                                <td id="td2">
                                   <label for="AI10"><h4>Alto Impacto(+10$)</h4></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" value="PN" id="PN" class="custom-checkbox" name="PN"> 
                                        <label for="PN"><h4></h4></label>
                                    </label>
                                </td>
                                <td id="td3">
                                    <label for="PN"><h4>Posicion Neta</h4></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" value="otros" id="otros" class="custom-checkbox" name="otros"> 
                                        <label for="otros"><h4></h4></label>
                                    </label>
                                </td>
                                <td id="td4">
                                    <label for="otros"><h4>Otros</h4></label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="checkbox">
                                        <input type="checkbox" value="otros2" id="otros2" class="custom-checkbox" name="otros2"> 
                                        <label for="otros2"><h4></h4></label>
                                    </label>
                                </td>
                                <td id="td5">
                                    <label for="otros2"><h4>Otros</h4></label>
                                </td>
                            </tr>
                            <tr> 
                                <td>
                                    
                                </td> 
                                <td>
                                    <input type="hidden"  id="datepicker_value"/>                                        
                                </td></tr>
                        </table>
                    </div> 
                    <a href="#" style="display:block; color:#000033; font-family:Tahoma; font-size:12px;"     
onclick="getOutput(); return false;"> test </a>
<span id="output"></span>
                </span>
            </div>
 <!--/**********************************************FIN CAPA RUTINARIOS*****************************************************************************************/-->                   
 
        </div> 
        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> SACET 
            All Rights Reserved. 
        </div> 
        <!-- page -->
        <!--container-->   
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>

</body>
</html>
