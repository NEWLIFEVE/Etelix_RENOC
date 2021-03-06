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
        <article class="titulo90">ESPECIFICOS</article>
        
            <form id="formulario">
                <article class='especificos_reportes'>
                    <p>Seleccione los Reportes</p>
                    <div class="choice primeras">
                        <input type="checkbox" value="true" id="compraventa" class="custom-checkbox-simple" name="lista[compraventa]">
                        <label for="compraventa">
                            <h4 id="td1">
                                Ranking Compra Venta
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10" class="custom-checkbox-simple" name="lista[AI10]">
                        <label for="AI10">
                            <h4 id="td2">
                                Alto Impacto(+10$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="AI10R" class="custom-checkbox-simple" name="lista[AI10R]">
                        <label for="AI10R">
                            <h4 id="td3">
                                Alto Impacto Resumen(+10$)
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                            <input type="checkbox" value="true" id="PN" class="custom-checkbox-simple" name="lista[PN]">
                            <label for="PN">
                                <h4 id="td4">
                                    Posicion Neta
                                </h4>
                            </label>
                        </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="calidad" class="custom-checkbox-simple" name="lista[calidad]">
                        <label for="calidad">
                            <h4 id="td5">
                                Calidad
                            </h4>
                        </label>
                    </div>
                    <div class="choice">
                        <input type="checkbox" value="true" id="A2NP" class="custom-checkbox-simple" name="lista[A2NP]">
                        <label for="A2NP">
                            <h4 id="td5">
                                Arbol 2N Proveedor
                            </h4>
                        </label>
                    </div>

               </article>
                <article class="parametros">
                    <p>Seleccione los parametros</p>
                        <div class="choice_parametros fecha">
                            <input type="checkbox" value="true" id="fecha" class="custom-checkbox-simple" name="lista[Fecha]">
                            <label for="fecha">
                                <h4 id="td1">
                                    Fecha
                                </h4>
                            </label>
                        </div>
                        <div class="choice_parametros carrier">
                            <input type="checkbox" value="true" id="Carrier" class="custom-checkbox-simple" name="lista[Carrier]">
                            <label for="Carrier">
                                <h4 id="td1">
                                    Carrier
                                </h4>
                            </label>
                        </div>
                        <div class="choice_parametros group">
                            <input type="checkbox" value="true" id="Group" class="custom-checkbox-simple" name="lista[Group]">
                            <label for="Group">
                                <h4 id="td1">
                                    Grupo
                                </h4>
                            </label>
                        </div>

                        <h3 class="indication">En esta seccion deberia seleccionar los parametros para cada reporte</h3>

                </article>
                <footer id="footer_especificos">
                    <div id="excel" class="botones_especificos">
                        <img src="/images/excel.png" title='Exportar Reportes en Excel' id='excel'>
                    </div>
                    <div id="lista" class="botones_especificos">
                        <img src="/images/mailRenoc.png" title='Enviar Reportes a Correo Electronico RENOC'>
                    </div>
                    <div id="mail" class="botones_especificos">
                        <img src="/images/mail.png" title='Enviar Reportes a su Correo Electronico' >
                    </div>
                    <div id="preview" class="botones_especificos">
                        <img src="/images/preview.png" title='Muestra una vista previa de Reportes ' >
                    </div>
                </footer>
          </form>
    </section>
</div> 
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
