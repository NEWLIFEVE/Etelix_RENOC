<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>


<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />

<script>
    $(function() {
        $("#datepicker").datepicker();
    });
</script>
<script type="text/javascript">
    function marcar(source)
    {
        checkboxes = document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
        for (i = 0; i < checkboxes.length; i++) //recoremos todos los controles
        {
            if (checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
</script>

<div class="row">

    <div id="barraVerde" style="border:1px solid white;background:green;text-align:rigth;
         width:5px; height:300px; margin-left: -50px; margin-bottom: 0spx;" class="span2" >
    </div> 
    <div id="instruccion" style="border:0px solid red;text-align:rigth;
         width:215px; height:32px; margin-top: -43px; margin-left: 28px; margin-bottom: 0spx;"  class="span1">
        <h4><font color="#848484">Seleccione una fecha</font></h4>
    </div>  
    <div  id="datepicker"  style="margin-left: -5px;" class="span4">

    </div>

    <div id="exportar" style="border:px solid black;text-align:rigth;
         width:41px; height:41px; margin-top: 247px; margin-left: -184px; margin-bottom: 0spx;"  class="span1">
        <img src="/assets/e3ecaab1/img/mail.png"  width="40px" height="35px">
    </div>
    <div id="exportar1" style="border:px solid black;text-align:rigth;
         width:48px; height:41px; margin-top: 245px; margin-left: -345px; margin-bottom: 0spx;"  class="span1">
        <img src="/assets/e3ecaab1/img/excel.png"  width="55px" height="55px">
    </div>
    <div id="barraVerde" style="border:1px solid white;background:green;text-align:rigth;
         width:5px; height:300px; margin-left: -60px; margin-bottom: 0spx;" class="span2" >
    </div>
    <div id="tabla" style="border:2px solid white;text-align:rigth;
         width:300px; height:301px; margin-left: 25px; margin-bottom: 0spx;" class="span2" >
        <table>
            <tr ><td><h3><label class="checkbox">
                            <input type="checkbox" onclick="marcar(this);" />
                        </label></td><td style=" border:2px solid white; background:#FAFAFA;"><h4><font color="#848484">Todos</font></h4></td></tr>
            <tr ><td><label class="checkbox">
                        <input type="checkbox"> 
                    </label></td><td style=" border:2px solid white; background:#F2F2F2;"><h4><font color="#848484">Alto Impacto Retail(+1$)</font></h4></td></tr>
            <tr><td><label class="checkbox">
                        <input type="checkbox"> 
                    </label></td><td style=" border:2px solid white; background:#E6E6E6;"><h4><font color="#848484">Alto Impacto(+10$)</font></h4></td></tr>
            <tr><td><label class="checkbox">
                        <input type="checkbox"> 
                    </label></td><td style=" border:2px solid white; background:#D8D8D8;"><h4><font color="#848484">Otros</font></h4></td></tr>
            <tr><td><label class="checkbox">
                        <input type="checkbox"> 
                    </label></td><td style=" border:2px solid white; background:#BDBDBD;"><h4><font color="#848484">Otros</font></h4></td></tr>
            <tr><td><label class="checkbox">
                        <input type="checkbox"> 
                    </label></td><td style=" border:2px solid white; background:#A4A4A4;"><h4><font color="#848484">Otros</font></h4></td></tr>
        </table>
    </div>    
</div>