<?php
/**
 * @var $this SiteController
 */
$this->layout=$this->getLayoutFile('menuContent');
?>
<div class="especificos">
    <header>
        <h1>
            <a id="flecha-backward" href="/"><</a>
        </h1>
    </header>
    <section>
    <article class="titulo90">
        ESPECIFICOS
    </article>
    <article class="parametros">
        <p>Seleccione los parametros</p>
                    <form id="formRutinarios">
                <div class="choice_parametros fecha">
                    <input type="checkbox" value="true" id="fecha" class="custom-checkbox" name="lista[fecha]">
                    <label for="fecha">
                        <h4 id="td1">
                            Fecha
                        </h4>
                    </label>
                    <input name="fechaini" id="datepicker" type="text" />
                    <input name="fechafin" id="datepicker" type="text" class="hasDatepicker"/>
                </div>
                <div class="choice_parametros">
                        <input type="checkbox" value="true" id="Hora" class="custom-checkbox" name="lista[Hora]">
                        <label for="Hora">
                            <h4 id="td1">
                                Hora
                            </h4>
                        </label>
                    </div>
                    <div class="choice_parametros">
                        <input type="checkbox" value="true" id="Mes" class="custom-checkbox" name="lista[Mes]">
                        <label for="Mes">
                            <h4 id="td1">
                                Mes
                            </h4>
                        </label>
                    </div>
            </form>
        <p>PARAMETROS</p>
      <footer>
                <div id="excel" class="botones">
                    <img src="/images/excel.png" class='ver'>
                    <img src="/images/excel_hover.png" title='Exportar Reportes en Excel' class='oculta' id='excel'>
                </div>
                <div id="mailRenoc" class="botones">
                    <img src="/images/mailRenoc.png" class='ver'>
                    <img src="/images/mailRenoc_hover.png" title='Enviar Reportes a Correo Electronico RENOC' class='oculta'>
                </div>
                <div id="mail" class="botones">
                    <img src="/images/mail.png" class='ver'>
                    <img src="/images/mail_hover.png" title='Enviar Reportes a su Correo Electronico' class='oculta'>
                </div>
            </footer>
    </article> 
        <article class='especificos_reportes'>
            <p>Seleccione los Reportes</p>
            <form id="formEspecificos">
<!--                <div class="choice">
                    <input type="checkbox" value="true" id="todos" class="custom-checkbox" name="lista[todos]" onClick="marcar(this);">
                    <label for="todos">
                        <h4 id="td1">
                            Todos
                        </h4>
                    </label>
                </div>-->
                <div class="choice primeras">
                        <input type="checkbox" value="true" id="compraventa" class="custom-checkbox" name="lista[compraventa]">
                        <label for="compraventa">
                            <h4 id="td1">
                                Ranking Compra Venta
                            </h4>
                        </label>
                    </div>
<!--                    <div class="choice">
                        <input type="checkbox" value="true" id="perdidas" class="custom-checkbox" name="lista[perdidas]">
                        <label for="perdidas">
                            <h4 id="td1">
                                Perdidas
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AIR" class="custom-checkbox" name="lista[AIR]">
                        <label for="AIR">
                            <h4 id="td1">
                                Alto Impacto Retail(+1$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10" class="custom-checkbox" name="lista[AI10]">
                        <label for="AI10">
                            <h4 id="td2">
                                Alto Impacto(+10$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10V" class="custom-checkbox" name="lista[AI10V]">
                        <label for="AI10V">
                            <h4 id="td2">
                                AI (+10$) por Vendedor
                            </h4>
                    </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="PN" class="custom-checkbox" name="lista[PN]">
                        <label for="PN">
                            <h4 id="td2">
                                Posicion Neta
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="PNV" class="custom-checkbox" name="lista[PNV]">
                        <label for="PNV">
                            <h4 id="td2">
                                Posicion Neta por Vendedor
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ADI" class="custom-checkbox" name="lista[ADI]">
                        <label for="ADI">
                            <h4 id="td3">
                                Arbol Destinos Internal
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ADE" class="custom-checkbox" name="lista[ADE]">
                        <label for="ADE">
                            <h4 id="td3">
                                Arbol Destinos External
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AC" class="custom-checkbox" name="lista[AC]">
                        <label for="AC">
                            <h4 id="td3">
                                Arbol Clientes
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AP" class="custom-checkbox" name="lista[AP]">
                        <label for="AP">
                            <h4 id="td3">
                                Arbol Proveedores
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCV" class="custom-checkbox" name="lista[DCV]">
                        <label for="DCV">
                            <h4 id="td4">
                                DC Vendedor
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCTP" class="custom-checkbox" name="lista[DCTP]">
                        <label for="DCTP">
                            <h4 id="td4">
                                DC Termino Pago
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCM" class="custom-checkbox" name="lista[DCM]">
                        <label for="DCM">
                            <h4 id="td4">
                                DC Monetizable
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DCCom" class="custom-checkbox" name="lista[DCCom]">
                        <label for="DCCom">
                            <h4 id="td4">
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
                    </div>-->
                    <input name="fecha" type="hidden"  id="datepicker_value" value="<?php mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")); echo date("Y-m-d"); ?>"/>
            </form>
        </article>
    </section>
</div> 
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
<script>
    $(".fecha").on('click',function(){
//        alert('hola');
        $(".fecha label h4").addClass("stretchRight");
        
    });
    
    
</script>