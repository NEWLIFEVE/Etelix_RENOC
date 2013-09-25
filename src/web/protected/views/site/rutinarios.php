<?php
/**
* @var $this SiteController
*/
$this->layout=$this->getLayoutFile('menuContent');
?>
<div class="row-fluid show-grid">
    <div id="atras" class="span12">
        <div class="span1 offset1">
            <h1>
                <a id="flecha-backward" href="/" class="tooltip-test"><</a>
            </h1>
        </div>
        <div class="contiene">
            <div class="titulosec">
                RUTINARIOS
            </div>
            
                <div class="calendario">
                    <p>Seleccione una fecha</p>
                    <div id="datepicker" class="span4">
                    </div>
                    <div id="mail" class="span1">
                        <a href="/" rel="tooltip" title="Enviar Reportes a su Correo Electronico" class="tooltip-test">
                            <img src="/images/mail.png" width="95px" height="95px" value="Activar Función">
                        </a>
                    </div>
                    <div id="mailRenoc" class="span1">
                        <a href="/" rel="tooltip" title="Enviar Reportes a Correo Electronico RENOC" class="tooltip-test">
                            <img src="/images/mailRenoc.png" width="95px" height="95px" value="Activar Función">
                        </a>
                    </div>
                    <div id="excel" class="span1">
                        <a href="/" rel="tooltip" title="Exportar Reportes en Excel" class="tooltip-test">
                            <img src="/images/excel.png" width="75px" height="75px" value="Activar Función">
                        </a>
                    </div>
                </div>
                <form id="formRutinarios">
                <div class="opciones">
                    <p>Seleccione los Reportes</p>
                    <div class="choice primeras">
                        <input type="checkbox" value="true" id="todos" class="custom-checkbox" name="lista[todos]" onClick="marcar(this);">
                        <label for="todos">
                            <h4 id="td1">
                                Todos
                            </h4>
                        </label>
                    </div>
                    <div class="choice primeras">
                        <input type="checkbox" value="true" id="compraventa" class="custom-checkbox" name="lista[compraventa]">
                        <label for="compraventa">
                            <h4 id="td1">
                                Ranking Compra Venta
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="perdidas" class="custom-checkbox" name="lista[perdidas]">
                        <label for="perdidas">
                            <h4 id="td2">
                                Perdidas
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AIR" class="custom-checkbox" name="lista[AIR]">
                        <label for="AIR">
                            <h4 id="td2">
                                Alto Impacto Retail(+1$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10" class="custom-checkbox" name="lista[AI10]">
                        <label for="AI10">
                            <h4 id="td3">
                                Alto Impacto(+10$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10V" class="custom-checkbox" name="lista[AI10V]">
                        <label for="AI10V">
                            <h4 id="td3">
                                AI (+10$) por Vendedor
                            </h4>
                    </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="PN" class="custom-checkbox" name="lista[PN]">
                        <label for="PN">
                            <h4 id="td4">
                                Posicion Neta
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="PNV" class="custom-checkbox" name="lista[PNV]">
                        <label for="PNV">
                            <h4 id="td4">
                                Posicion Neta por Vendedor
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ADI" class="custom-checkbox" name="lista[ADI]">
                        <label for="ADI">
                            <h4 id="td5">
                                Arbol Destinos Internal
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ADE" class="custom-checkbox" name="lista[ADE]">
                        <label for="ADE">
                            <h4 id="td5">
                                Arbol Destinos External
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AC" class="custom-checkbox" name="lista[AC]">
                        <label for="AC">
                            <h4 id="td5">
                                Arbol Clientes
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AP" class="custom-checkbox" name="lista[AP]">
                        <label for="AP">
                            <h4 id="td5">
                                Arbol Proveedores
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCV" class="custom-checkbox" name="lista[DCV]">
                        <label for="DCV">
                            <h4 id="td5">
                                DC Vendedor
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCTP" class="custom-checkbox" name="lista[DCTP]">
                        <label for="DCTP">
                            <h4 id="td5">
                                DC Termino Pago
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCM" class="custom-checkbox" name="lista[DCM]">
                        <label for="DCM">
                            <h4 id="td5">
                                DC Monetizable
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCCom" class="custom-checkbox" name="lista[DCCom]">
                        <label for="DCCom">
                            <h4 id="td5">
                                DC Compania
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCCarrier" class="custom-checkbox" name="lista[DCCarrier]">
                        <label for="DCCarrier">
                            <h4 id="td5">
                                DC Carrier
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="Ev" class="custom-checkbox" name="lista[Ev]">
                        <label for="Ev">
                            <h4 id="td5">
                                Evolucion
                            </h4>
                        </label>
                    </div>
                </div>
                <div>
                    <div>
                        <input name="fecha" type="hidden"  id="datepicker_value" value=" <?php
                    mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
                    echo date("Y-m-d");
                    ?>"/>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>            
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>

