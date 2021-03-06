/**
*
*/
var selector=function(id)
{
    this.variable=id;
}
selector.prototype.run=function()
{
    this.objeto=$(this.variable);
    this.objeto.datepicker(
    {
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText, inst)
        {
            $("#startDate").val(dateText);
        }
    });
}
/**
*
*/
var navegar=function()
{
    this.enlaces='a#flecha-forward'/*, a#flecha-backward'*/;
    this.main='#capa';
    this.nueva='.vistas';
}
/**
 *
 */
navegar.prototype.run=function()
{
    this.boton=$(this.enlaces);
    this.objetoMain=$(this.main);
    this.objetoNueva=$(this.nueva);
    this.pisaAqui();
}
/**
 *
 */
navegar.prototype.pisaAqui=function()
{
    var self=this;
    this.boton.on('click',function(e)
    {
        e.preventDefault();
        self.url=$(this).attr('href');
        if(self.url=="/")
        {
            self.vuelta();
        }
        else
        {
            self.ida();
        }
    });
}
/**
 *
 */
navegar.prototype.ida=function()
{
    var self=this;
    this.objetoNueva.load(this.url,function()
    {
        self.objetoMain.toggle('slide');
        self.objetoNueva.fadeIn('fast');
        $RENOC.UI.init();
        $RENOC.AJAX.init();
    });
}
/**
 *
 */
navegar.prototype.vuelta=function()
{
    var self=this;
    this.objetoNueva.load(this.url,function()
    {
        self.objetoNueva.fadeOut('slow');
        self.objetoMain.toggle('slide');
    });
}

/**
**
*/
var ajax=function()
{
    this.formulario=null;
    this.mail="/site/mail";
    this.excel="/site/excel";
    this.preview="/site/preview";
    this.mailLista="/site/maillista";
    this.ruta=null;
    this.error=0;
}
/**
 *
 */
ajax.prototype.run=function()
{
    var self=this;
    $('#mail,#excel,#lista,#preview').on('click',function(e)
    {
        var id=tipo=numero=valor=nombre=mensaje=null, ventana={};
        self.setCero();
        e.preventDefault();
        //Reviso cuantos check han sido seleccionados
        var numero=$('input[type="checkbox"]').filter(function()
        {
            return $(this).is(':checked');
        });
        //asigno la ruta de reportes
        self.tipo=$(this).attr('id');
        //Valido que al menos un reporte esté selecionado
        console.log(numero.length);
        
        self.setCero();
        //si hay alguna opcion seleccionada verifico que la fecha para el reporte no sea mayor a la fecha actual
        var f = new Date(),
        fecha2=( f.getDate()+ "-" + (f.getMonth()+1) + "-" +f.getFullYear() );
        var fecha=Date.parse(Date());
        var seleccion1=Date.parse($('#startDate').val());

        if(seleccion1>fecha)
        {
                mensaje="<h3>La fecha seleccionada no puede ser mayor a la fecha actual "+fecha2+" </h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 3000);
                mensaje=null;
                self.setUno();
        }
        if(( ($('#endingDate').val()!="") || ($('#endingDate').val()!=undefined)) && (($('#startDate').val()!="") || ($('#startDate').val()!=undefined)) )
        {
                self.setCero();
                var f = new Date();
                fecha2=( f.getDate()+ "-" + (f.getMonth()+1) + "-" +f.getFullYear() );
                var fecha=Date.parse(Date());
                var seleccion1=Date.parse($('#startDate').val());
                var seleccion2=Date.parse($('#endingDate').val());
                if(seleccion1>seleccion2)
                {
                         mensaje="<h3>La fecha de inicio seleccionada no puede ser mayor a la fecha fin seleccionada</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                         self.crearCapa(mensaje);
                 setTimeout(function()
                 {
                     self.destruirCapa();
                 }, 3000);
                 mensaje=null;
                 self.setUno();	
                }else if(seleccion2<seleccion1)
                {
                         mensaje="<h3>La fecha fin seleccionada no puede ser menor a la fecha de inicio seleccionada</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                         self.crearCapa(mensaje);
                 setTimeout(function()
                 {
                     self.destruirCapa();
                 }, 3000);
                 mensaje=null;
                 self.setUno();

                }else if(seleccion1>fecha)
                {
                        console.log("3");
                        mensaje="<h3>La fecha de inicio seleccionada no puede ser mayor a la fecha actual "+fecha2+" </h3><img src='/images/stop.png'width='25px' height='25px'/>";
                        self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 3000);
                mensaje=null;
                self.setUno();
                }else if(seleccion2>fecha)
                {
                        console.log("4");
                        mensaje="<h3>La fecha fin seleccionada no puede ser mayor a la fecha actual "+fecha2+" </h3><img src='/images/stop.png'width='25px' height='25px'/>";
                        self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 3000);
                mensaje=null;
                self.setUno();
                }
        }
        if($(".titulo90").html()==("ESPECIFICOS"))
        {
            if(numero.length<=0)
            {
                mensaje="<h3>Debe seleccionar al menos un tipo de reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }
            else if(numero.length<=1)
            {
                mensaje="<h3>Debe seleccionar los parametros del reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }

            else if($("input[type='checkbox'][name='lista[Carrier]']").prop("checked")!=true && $("input[type='checkbox'][name='lista[Group]']").prop("checked")!=true)
            {
                mensaje="<h3>Falto seleccionar un parametro entre carrier o grupo</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }
            else if($("input#carrier").val()!=undefined && $("input#group").val()!=undefined)
            {
                console.log($("input#carrier").val());
                console.log($("input#group").val());
                mensaje="<h3>Debe seleccionar solo un parametro entre carrier o grupo</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }
            
        }else{
            if(numero.length<=0)
            {
                mensaje="<h3>Debe seleccionar al menos un tipo de reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }else if(this.id=='preview')
            {
                if(numero.length>=2){
                    mensaje="<h3>La vista previa solo esta disponible para un reporte a la vez</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                    self.crearCapa(mensaje);
                    setTimeout(function()
                    {
                        self.destruirCapa();
                    }, 2000);
                    mensaje=null;
                    self.setUno();
                }
                if($("input[type='checkbox'][name='lista[DC]']").prop("checked")==true || $("input[type='checkbox'][name='lista[Ev]']").prop("checked")==true){
                    var mensaje="<h3>La vista previa No esta disponible para este reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                    self.crearCapa(mensaje);
                    setTimeout(function()
                    {
                        self.destruirCapa();
                    }, 2000);
                    mensaje=null;
                    self.setUno();
                }
            }
        }
        //valido rerate
        self.validarRerate();
        //Valido el reportes
        self.validarReporte();
        //mando a ejecutar las cosas
        self.ejecutarAcciones();
        
        id=tipo=numero=valor=nombre=mensaje=null;
    });
}
ajax.prototype.genExcel=function()
{
    var self=this,reportes=Array(),fechas=Array(), opciones=Array();
    var total=self.formulario.length-1;
    for(var i=0, j=total;i<=j; i++)
    {
        switch(self.formulario[i].name)
        {
            case "lista[compraventa]":
            case "lista[perdidas]":
            case "lista[AIR]":
            case "lista[AI10]":
            case "lista[AI10R]":
            case "lista[AI10V]":
            case "lista[PN]":
            case "lista[PNV]":
            case "lista[ADI]":
            case "lista[ADE]":
            case "lista[ACI]":
            case "lista[ACE]":
            case "lista[API]":
            case "lista[APE]":
            case "lista[DC]":
            case "lista[Ev]":
            case "lista[RCC]":
            case "lista[RN]":
            case "lista[calidad]":
            case "lista[A2NP]":
                reportes[self.formulario[i].name]={name:self.formulario[i].name,value:self.formulario[i].value};
                break;
            case "startDate":
            case "endingDate":
                fechas[self.formulario[i].name]={name:self.formulario[i].name,value:self.formulario[i].value};
                break;
            case "carrier":
            case "group":
                opciones[self.formulario[i].name]={name:self.formulario[i].name,value:self.formulario[i].value};
                break;
        }
    };

    if(fechas['endingDate']==undefined) fechas['endingDate']={name:'endingDate',value:''};

    for(var key in reportes)
    {
        if(reportes[key].name=="lista[calidad]" || reportes[key].name=="lista[A2NP]")
        {
            for(var key2 in opciones)
            {
                ventana[key2]=window.open(self.ruta+"?"+fechas['startDate'].name+"="+fechas['startDate'].value+"&"+fechas['endingDate'].name+"="+fechas['endingDate'].value+"&"+reportes[key].name+"="+reportes[key].value+"&"+opciones[key2].name+"="+opciones[key2].value,opciones[key2].name,'width=500,height=200');
                ventana[key2].document.write("<html><head>" +
                  		" <link href='http://getbootstrap.com/assets/css/docs.min.css' rel='stylesheet'>" +
                  		"<script src='http://getbootstrap.com/assets/js/docs.min.js'></script>"+
                  		"</head><body onload='window.close();'><div class='bs-callout bs-callout-info'>" +
                  		"<h3 style='color:#3CB371'>Su reporte se esta generando, por favor espere <img src='/images/circular.gif'width='25px' height='25px'/></h3><h5>Al terminar la carga, podr&aacute; cerrar esta ventana o presionar <a href='#' onclick='window.close();'>AQUI</a> para cerrarla</h5></div></body></html>");
            }
        }
        else
        {
        	
            	  ventana[key]=window.open(self.ruta+"?"+fechas['startDate'].name+"="+fechas['startDate'].value+"&"+fechas['endingDate'].name+"="+fechas['endingDate'].value+"&"+reportes[key].name+"="+reportes[key].value,reportes[key].name,'width=500,height=200');
                  ventana[key].document.write("<html><head>" +
                  		" <link href='http://getbootstrap.com/assets/css/docs.min.css' rel='stylesheet'>" +
                  		"<script src='http://getbootstrap.com/assets/js/docs.min.js'></script>"+
                  		"</head><body onload='window.close();'><div class='bs-callout bs-callout-info'>" +
                  		"<h3 style='color:#3CB371'>Su reporte se esta generando, por favor espere <img src='/images/circular.gif'width='25px' height='25px'/></h3><h5>Al terminar la carga, podr&aacute; cerrar esta ventana o presionar <a href='#' onclick='window.close();'>AQUI</a> para cerrarla</h5></div></body></html>");
               
                  //cerrar ventana al realizar el excel
                 //   ventana[key].close();

                  
        }
    }

}
ajax.prototype.getFormPost=function()
{
    this.formulario=$("#formulario").serializeArray();
   
}
ajax.prototype.enviar=function()
{ 
    var self=this, mensaje=null;
    var opciones=
    {
        url:self.ruta,
        data:self.formulario,
        type:'POST'
    };
    console.log(self.formulario);
    this.envio=$.ajax(opciones).done(function(datos)
    {
        if(self.ruta=="/site/preview")
        {
            $RENOC.UI.fancyBox(datos);
        }else{
            mensaje="<h2 class='exito'>"+datos+"</h2><img src='/images/si.png'width='95px' height='95px' class='si'/>";
            self.crearCapa(mensaje);
            setTimeout(function()
            {
                self.destruirCapa();
            }, 3000);
        }
    }).fail(function()
    {
        mensaje="<h2 class='fail'>Ups! Ocurrio un problema</h2><h5>Posiblemente no hay datos en la fecha seleccionada</h5><img src='/images/no.png'width='95px' height='95px'/>";
        setTimeout(function()
        {
            self.destruirCapa();
        }, 4000);
    });
}
ajax.prototype.crearCapa=function(mensaje)
{
    if($('.cargando').length>0)
    {
        $('.mensaje').html(mensaje);
    }
    else
    {
        this.capa=$("<div class='cargando'><div class='mensaje'></div></div>").hide();
        $("body").append(this.capa);
        $('.mensaje').html(mensaje);
        $('.cargando').fadeIn('fast');
    }
    
}
ajax.prototype.destruirCapa=function()
{
    this.capa.fadeOut('slow');
    this.capa.remove();
    this.capa=null;
}
ajax.prototype.setUno=function()
{
    this.error=1;
}
ajax.prototype.setCero=function()
{
    this.error=0;
}
ajax.prototype.validarRerate=function()
{

    self=this;
    if(self.error==0)
    {
        if($RENOC.DATA.rerate=="true")
        {
            mensaje="<h4>En estos momentos se esta corriendo un proceso de Re-Rate, es posible que la data en los reportes no sea fiable, desea igualmente emitir el/los reporte/es?.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div class='rerateBtn'><div id='cancelar' class='cancelar'>Cancelar</div><div id='confirma' class='confirma'>Confirmar</div></div>";
            self.crearCapa(mensaje);
            self.setUno();
            $('#cancelar, #confirma').on('click',function()
            {
                id=$(this).attr('id');
                if(id=="confirma")
                {
                    self.setCero();
                }
                else
                {
                    self.setUno();
                }
                self.destruirCapa();
                //self.validarReporte('calidad','carrier');
            });
        }
    }
}
ajax.prototype.validarReporte=function()
{
	
    self=this;
    if(self.error==0)
    {
    	
        //validad el reporte de calidad
        if($('#calidad:checked').val()=="true")    
        {
            var carrier=$('#carrier').val(),
                group=$('#group').val(),
                fecha=$('#startDate').val(), 
                frase="Debe ",
                mensaje=null;group
            if(((carrier==="" || carrier===undefined) && (group==="" || group===undefined)) || (fecha==="" || fecha===undefined))
            {            
                if((carrier=="" || carrier==undefined) || (group==="" || group===undefined)) frase=frase+" seleccionar carrier";
                if((carrier=="" || carrier==undefined) && (fecha=="" || fecha==undefined)) frase=frase+" y";
                if(fecha=="" || fecha==undefined) frase=frase+" seleccionar al menos una fecha";
                frase=frase+" para generar el reporte";
                mensaje="<h3>"+frase+"</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                carrier=group=fecha=frase=mensaje=null;
                self.setUno();
            }
        }
        //valida los demas reportes
        if($('#compraventa:checked').val()=="true" || $('#AI10:checked').val()=="true" || $('#AI10R:checked').val()=="true")
        {
        	if($('#startDate').val()=="" || $('#startDate').val()==undefined )
            {
                mensaje="<h3>Debe seleccionar al menos una fecha para generar el reporte</h3><img src='/images/stop.png'width='25px' height='25px'/>";
                self.crearCapa(mensaje);
                setTimeout(function()
                {
                    self.destruirCapa();
                }, 2000);
                mensaje=null;
                self.setUno();
            }
            else
            {
                self.setCero();
            }
        }
    }
}
ajax.prototype.ejecutarAcciones=function()
{
    self=this;
    if(self.error==0)
    {
    	
        mensaje="<h2>Espere un momento por favor</h2><img src='/images/circular.gif'width='95px' height='95px'/>";
        self.crearCapa(mensaje);
        if(self.tipo=="excel")
        {
        	
            self.ruta=self.excel;  
            self.getFormPost();
            self.genExcel();
            self.destruirCapa();
        }
        else if(self.tipo=="mail")
        {
            self.ruta=self.mail;
            self.getFormPost();
            self.enviar();
        }
        else if(self.tipo=="preview")
        {
            self.ruta=self.preview;
            self.getFormPost();
            self.enviar();
            
        }
        else if(self.tipo=="lista")
        {
            mensaje="<h4>Se enviara un correo a toda la lista de RENOC.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div id='cancelar'\n\class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\<p><label><b>Aceptar</b></label></div></div>";
            self.crearCapa(mensaje);
            $('#cancelar, #confirma').on('click',function()
            {
                id=$(this).attr('id');
                if(id=='confirma')
                {
                    mensaje="<h2>Espere un momento por favor</h2><img src='/images/circular.gif'width='95px' height='95px'/>";
                    self.crearCapa(mensaje);
                    self.ruta=self.mailLista;
                    self.getFormPost();
                    self.enviar();
                }
                else
                {
                    self.destruirCapa();
                }
            });
        }
    }
}
/**
**
*/
function marcar(source)
{
    var checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    if(checkboxes.length>0)
    {
        for(i=0,j=checkboxes.length-1; i<=j; i++) //recorremos todos los controles
        {
            if(checkboxes[i].type=="checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
};
var ventana=new navegar();
var fecha=new selector("#inicio,#datepicker,#fin");
var ejecutar=new ajax();
$(document).on('ready',function()
{  
    ejecutar.run();
    ventana.run();
    fecha.run();
    marcar();
    $.ajax({ 
        url: "Log/revisarRR",     
        success: function(data) 
        {
            if(data==true){
                var espere=$(".cargandosori");
                espere.prop("display",'block');
                espere.slideDown('slow');
                $RENOC.DATA.rerate="true";
            }        
        }
    });
    
    /*$(this).ajaxComplete(function()
    {
        fecha.run();
        marcar();
        
    });*/
});
/*provisional, mientras se empareja branch dev*/
$(".especificos_reportes div.choice input").click(function()
{
    $(".especificos_reportes div.choice input").prop("checked", "");
    $(this).prop("checked", "checked");
});

$(".especificos_reportes div.choice label h4").click(function()
{
    $(".especificos_reportes div.choice label h4").removeClass("testcss");
    $(this).addClass("testcss");
    var hide = [".parametros .fecha,.parametros .carrier,.parametros .group,h3.indication"];
    switch ($(this).attr("id")) {
        case "td5":
            var show = [".parametros .fecha,.parametros .carrier,.parametros .group"];
            $RENOC.UI.showHideElement(hide, show);
            break;
        default:
            var show = [".parametros .fecha"];
            $RENOC.UI.showHideElement(hide, show);
            break
    }
});

