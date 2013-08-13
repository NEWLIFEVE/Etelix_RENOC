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
    this.enlaces='a#flecha-forward, a#flecha-backward';
    this.main='#capa';
    this.nueva='.div';
}
navegar.prototype.run=function()
{
    this.boton=$(this.enlaces);
    this.objetoMain=$(this.main);
    this.objetoNueva=$(this.nueva);
    this.pisaAqui();
}
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
navegar.prototype.ida=function()
{
    var self=this;
    this.objetoNueva.load(this.url,function()
    {
        self.objetoMain.toggle('slide');
        self.objetoNueva.fadeIn('slow');
    });
}
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
}
ajax.prototype.run=function()
{
    var self=this;
    $('#mail,#excel').on('click',function(e)
    {
        e.preventDefault();
        var numero=$('input[type="checkbox"]').filter(function()
        {
            return $(this).is(':checked');
        });
        if(numero.length>0)
        { 
            var tipo=$(this).attr('id');
            if(tipo=="mail")
            {
                self.getFormPost();
                self.enviarMail();
              var espere = $("<div class='cargando'><div class='mensaje'><h2>Espere un momento por favor</h2><p><p><p><p><p><p><p><p><img src='/images/circular.gif'width='95px' height='95px'/><p><p><p><p></div></div>").hide();
               $("body").append(espere)
               espere.fadeIn(2000);
            }
            else
            {
                self.getFormPost();
                for(var i = 0; i <= self.formulario.length - 2; i++)
                {
//                    $("body").append.fadeIn()("<div class='cargando'><div class='mensaje'><h2>Espere un momento por favor</h2><p><p><p><p><p><p><p><p><img src='/images/circular.gif'width='95px' height='95px'/><p><p><p><p></div></div>");
                    fecha=self.formulario[self.formulario.length-1].value;
                    nombre=self.formulario[i].name;
                    valor=self.formulario[i].value;
                    var ventana=window.open(self.excel+"?fecha="+fecha+"&"+nombre+"="+valor,"Archivos Excel");
                };
            }
        }
        else
        {
var stop = $("<div class='cargando'><div class='mensaje'><h3>Debe seleccionar al menos un tipo de reporte</h3><img src='/images/stop1.png'width='45px' height='45px'/></div></div>").hide();
$('body').append(stop);
stop.fadeIn(1000);

        setTimeout(function()
        {
            stop.fadeOut(2000);
        }, 3000);
        }
    });
}
ajax.prototype.getFormPost=function()
{
    this.formulario=$("#formRutinarios").serializeArray();
}
ajax.prototype.enviarMail=function()
{
    var self=this;
    var opciones=
    {
        url:this.mail,
        data:this.formulario,
        type:'POST'
    };
    this.envio=$.ajax(opciones).done(function(datos)
    {
        $('.mensaje').html("<h2 class='exito'>Mensaje Enviado</h2><img src='/images/si.png'width='95px' height='95px'/><p><p>").hide().fadeIn(4000);
        setTimeout(function()
        {
            $('.cargando').fadeOut(2000);
        }, 5000);
    }).fail(function()
    {
        $('.mensaje').html("<h2 class='fail'>Ups! Ocurrio un problema</h2><h5>Posiblemente no hay datos en la fecha seleccionada</h5><img src='/images/no.png'width='95px' height='95px'/><p><p><p><p>").fadeIn(200);
        setTimeout(function()
        {
            $('.cargando').fadeOut(2000);
        }, 5000);
    });
}
/**
**
*/
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
    $(this).ajaxComplete(function()
    {
        fecha.run();
        marcar();
    });
});
