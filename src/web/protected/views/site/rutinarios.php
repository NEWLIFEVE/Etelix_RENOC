<?php
/**
 * @var $this SiteController
 */
$this->layout=$this->getLayoutFile('menuContent');
?>
<div class="rutinarios">
    <header>
        <h1>
            <a id="flecha-backward" href="/"><</a>
        </h1>
    </header>
    <section>
        <article class='titulo90'>
            RUTINARIOS
        </article>
        <article class='calendario'>
            <p>Seleccione una fecha</p>
            <div id="datepicker">
            </div>
            <footer>
                <div id="excel" class="botones">
                    <img src="/images/excel.png" class='ver'>
                    <img src="/images/excel_hover.png" title='Exportar Reportes en Excel' class='oculta'>
                </div>
                <div id="lista" class="botones">
                    <img src="/images/mailRenoc.png" class='ver'>
                    <img src="/images/mailRenoc_hover.png" title='Enviar Reportes a Correo Electronico RENOC' class='oculta'>
                </div>
                <div id="mail" class="botones">
                    <img src="/images/mail.png" class='ver'>
                    <img src="/images/mail_hover.png" title='Enviar Reportes a su Correo Electronico' class='oculta'>
                </div>
            </footer>
        </article>
        <article class='rutinarios_reportes'>
            <p>Seleccione los Reportes</p>
            <form id="formulario">
                <div class="choice">
                    <input type="checkbox" value="true" id="todos" class="custom-checkbox" name="lista[todos]" onClick="$RENOC.UI.marcar(this);">
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
                                AD Internal
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ADE" class="custom-checkbox" name="lista[ADE]">
                        <label for="ADE">
                            <h4 id="td3">
                                AD External
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ACI" class="custom-checkbox" name="lista[ACI]">
                        <label for="ACI">
                            <h4 id="td3">
                                AC Internal
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="ACE" class="custom-checkbox" name="lista[ACE]">
                        <label for="ACE">
                            <h4 id="td3">
                                AC External
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="API" class="custom-checkbox" name="lista[API]">
                        <label for="API">
                            <h4 id="td4">
                                AP Internal
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="APE" class="custom-checkbox" name="lista[APE]">
                        <label for="APE">
                            <h4 id="td4">
                                AP External
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="DC" class="custom-checkbox" name="lista[DC]">
                        <label for="DC">
                            <h4 id="td4">
                                Distribucion Comercial
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="Ev" class="custom-checkbox" name="lista[Ev]">
                        <label for="Ev">
                            <h4 id="td4">
                                Evolucion
                            </h4>
                        </label>
                    </div>
                    <input name="startDate" type="hidden"  id="startDate" value="<?php $nuevafecha=strtotime('-1 day',strtotime(date('Y-m-d')));
        echo $nuevafecha=date('Y-m-d',$nuevafecha);?>"/>
            </form>
        </article>
    </section>
</div>       
<!--<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>-->

