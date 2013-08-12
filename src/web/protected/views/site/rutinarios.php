<?php
/**
* @var $this SiteController
*/
$this->layout=$this->getLayoutFile('menuContent');
?>
    <script src="http://malsup.github.io/jquery.blockUI.js"></script>
<div class="">
    <div id="atras">
        <ul class="thumbnails">
            <li class="span1">
                <div id="icono">
                    <h1>
                        <a id="flecha-backward" href="/" class="tooltip-test"><</a>
                    </h1>
                </div>
            </li>
        </ul>
    </div>
    <div class="Rotate-90 hidden-phone hidden-tablet">
        RUTINARIOS
    </div>
    <div id="barraVerde1" class="span2 hidden-phone">
    </div>
    <div id="instruccion" class="span1">
        Seleccione una fecha
    </div>
    <div id="datepicker" class="span6">
    </div>
    <div id="mail" class="span1">
        <a href="/" rel="tooltip" title="Enviar Reportes a su Correo Electronico" class="tooltip-test">
            <img src="/images/mail.png" width="95px" height="95px" value="Activar Función">
        </a>
    </div>
    <div id="excel" class="span1">
        <a href="/" rel="tooltip" title="Exportar Reportes en Excel" class="tooltip-test">
            <img src="/images/excel.png" width="75px" height="75px" value="Activar Función">
        </a>
    </div>
    <div id="barraVerde2" class="span2">
    </div>
    <div id="instruccion2" class="span1">
        Seleccione los Reportes
    </div>
    <div id="tablagris" class="span2">
        <form id="formRutinarios">
        <table>
            <tr>
                <td width="47">
                    <h3>
                        <label class="checkbox">
                            <input type="checkbox" value="todos" id="todos" class="custom-checkbox" name="todos" onClick="marcar(this);">
                            <label for="todos">
                                <h4>
                                </h4>
                            </label>
                        </label>
                    </h3>
                </td>
                <td width="209" id="tdtodos">
                    <label for="todos">
                        <h4>
                            Todos
                        </h4>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" value="true" id="AIR" class="custom-checkbox" name="lista[AIR]">
                        <label for="AIR">
                            <h4>
                            </h4>
                        </label>
                    </label>
                </td>
                <td id="td1">
                    <label for="AIR">
                        <h4>
                            Alto Impacto Retail(+1$)
                        </h4>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" value="true" id="AI10" class="custom-checkbox" name="lista[AI10]">
                        <label for="AI10">
                            <h4>
                            </h4>
                        </label>
                    </label>
                </td>
                <td id="td2">
                    <label for="AI10">
                        <h4>
                            Alto Impacto(+10$)
                        </h4>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" value="true" id="PN" class="custom-checkbox" name="lista[PN]">
                        <label for="PN">
                            <h4>
                            </h4>
                        </label>
                    </label>
                </td>
                <td id="td3">
                    <label for="PN">
                        <h4>
                            Posicion Neta
                        </h4>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" value="true" id="otros" class="custom-checkbox" name="lista[otros]"> 
                        <label for="otros">
                            <h4>
                            </h4>
                        </label>
                    </label>
                </td>
                <td id="td4">
                    <label for="otros">
                        <h4>
                            Otros
                        </h4>
                    </label>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="checkbox">
                        <input type="checkbox" value="true" id="otros2" class="custom-checkbox" name="lista[otros2]"> 
                        <label for="otros2">
                            <h4>
                            </h4>
                        </label>
                    </label>
                </td>
                <td id="td5">
                    <label for="otros2">
                        <h4>
                            Otros
                        </h4>
                    </label>
                </td>
            </tr>
            <tr> 
                <td>
                </td> 
                <td>
                    <input name="fecha" type="hidden"  id="datepicker_value"/>                                        
                </td>
            </tr>
        </table>
        </form>
    </div> 
</div>
<div id="respuesta">
</div>
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
