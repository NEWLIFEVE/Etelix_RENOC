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
            $("#datepicker_value").val(dateText);
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
var errores=(function()
{
    var rerate=null;
    (function()
    {
        $.ajax({
            url:"Log/revisarRR",
            success:function(data)
            {
                if(data==true)
                {
                    window.errores.rerate=true;
                }
                else
                {
                    window.errores.rerate=false;
                }
            }
        });
    })();
    return{
        rerate:rerate
    }
})();
/**
**
*/
var ajax=function()
{
    this.formulario=null;
    this.mail="/site/mail";
    this.excel="/site/excel";
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
    console.log("justo despues del click");
    $('#mail,#excel,#mailRenoc').on('click',function(e)
    {

        var id=tipo=numero=valor=nombre=fecha=mensaje=null, ventana={};
        self.setCero();
        e.preventDefault();
        numero=$('input[type="checkbox"]').filter(function()
        {
            return $(this).is(':checked');
        });
        //asigno la ruta de reportes
        tipo=$(this).attr('id');
        //compruebo que al menos un reporte este seleccionado
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
        if(self.error==0)
        {
            if(errores.rerate==true)
            {
                mensaje="<h4>En estos momentos se esta corriendo un proceso de Re-Rate, es posible que la data en los reportes no sea fiable, desea igualmente emitir el/los reporte/es?.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div id='cancelar' class='cancelar'><img src='/images/cancelar.png'width='85px' height='45px'/>&nbsp;</div><div id='confirma' class='confirma'><img src='/images/aceptar.png'width='85px' height='45px'/></div>";
                self.crearCapa(mensaje);
                $('#cancelar, #confirma').on('click',function()
                {
                    id=$(this).attr('id');
                    if(id=="confirma")
                    {
                        self.setCero();
                        mensaje="<h2>Espere un momento por favor</h2><img src='/images/circular.gif'width='95px' height='95px'/>";
                        self.crearCapa(mensaje);
                        if(tipo=="excel")
                        {
                            self.ruta=self.excel;
                            self.getFormPost();
                            self.genExcel();
                            self.destruirCapa();
                        }
                        else if(tipo=="mail")
                        {
                            self.ruta=self.mail;
                            self.getFormPost();
                            self.enviar();
                        }
                        else if(tipo=="mailRenoc")
                        {
                            mensaje="<h4>Se enviara un correo a toda la lista de RENOC.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div id='cancelar' class='cancelar'><img src='/images/cancelar.png'width='85px' height='45px'/></div><div id='confirma' class='confirma'><img src='/images/aceptar.png'width='85px' height='45px'/>";
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
                    else if(id=="cancelar")
                    {
                        self.setUno();
                        self.destruirCapa();
                    }
                });
            }
            else
            {
                self.setCero();
                mensaje="<h2>Espere un momento por favor</h2><img src='/images/circular.gif'width='95px' height='95px'/>";
                self.crearCapa(mensaje);
                if(tipo=="excel")
                {
                    self.ruta=self.excel;
                    self.getFormPost();
                    self.genExcel();
                    self.destruirCapa();
                }
                else if(tipo=="mail")
                {
                    self.ruta=self.mail;
                    self.getFormPost();
                    self.enviar();
                }
                else if(tipo=="mailRenoc")
                {
                    mensaje="<h4>Se enviara un correo a toda la lista de RENOC.</h4><p>Si esta seguro presione Aceptar, de lo contrario cancelar</p><div id='cancelar'\n\
                             class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\
                             <p><label><b>Aceptar</b></label></div></div>";
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
        id=tipo=numero=valor=nombre=fecha=mensaje=null;
    });
}
ajax.prototype.genExcel=function()
{
    var self=this;
    for(var i=0, j=self.formulario.length-1;i<=j; i++)
    {
        fecha=self.formulario[self.formulario.length-1].value;
        nombre=self.formulario[i].name;
        valor=self.formulario[i].value;
        if(self.formulario[i].name!="lista[todos]" && self.formulario[i].name!="fecha")
        {
            console.log(i,self.ruta+"?fecha="+fecha+"&"+nombre+"="+valor);
            //ventana[i]=window.open(self.ruta+"?fecha="+fecha+"&"+nombre+"="+valor,nombre,'width=200px,height=100px');
        }
    }
}
ajax.prototype.getFormPost=function()
{
    this.formulario=$("#formRutinarios").serializeArray();
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
    this.envio=$.ajax(opciones).done(function(datos)
    {
        mensaje="<h2 class='exito'>"+datos+"</h2><img src='/images/si.png'width='95px' height='95px' class='si'/>";
        self.crearCapa(mensaje);
        setTimeout(function()
        {
            self.destruirCapa();
        }, 3000);
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
/**
**
*/
function marcar(source)
{
    checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    if(checkboxes.length>0)
    {
        for(i=0,j=checkboxes.length-1; i<=j; i++) //recoremos todos los controles
        {
            if(checkboxes[i].type=="checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
};
var ventana=new navegar();
var fecha=new selector("#datepicker");
var ejecutar=new ajax();
$(document).on('ready',function()
{  
    ejecutar.run();
    ventana.run();
    fecha.run();
    marcar();
    /*$(this).ajaxComplete(function()
    {
        fecha.run();
        marcar();
    });*/
});

$(document).on('ready',function(muestramensaje)
{
    $.ajax({ 
        url: "Log/revisarRR",     
        success: function(data) 
        {
            if(data==true){
                var espere = $(".cargandosori");
                espere.prop("display",'block');
                espere.slideDown('slow');
            }        
        }
    });

});
