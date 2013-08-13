

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
****************************************************************************************************************************************
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
        var datefecha = $('input#datepicker_value').val().length;
        var numero=$('input[type="checkbox"]').filter(function()
        {
            return $(this).is(':checked');
        });
        if(numero.length>0 || datefecha.length >0)
        {
            var tipo=$(this).attr('id');
            if(tipo=="mail")
            {
                self.getFormPost();
                self.enviarMail();
            }
            else
            {
                self.getFormPost();
                for(var i = 0; i <= self.formulario.length - 2; i++)
                {
                    fecha=self.formulario[self.formulario.length-1].value;
                    nombre=self.formulario[i].name;
                    valor=self.formulario[i].value;
                    var ventana=window.open(self.excel+"?fecha="+fecha+"&"+nombre+"="+valor,"Archivos Excel");
                };
            }
        }
        else
        {
           $('.mensaje').html("<h2>Debe seleccionar al menos un reporte y una fecha</h2><img src='/images/stop.png'width='95px' height='95px'/><br>");
        setTimeout(function()
        {
            $('.cargando').remove();
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
        alert(datos);
    }).fail(function()
    {
        alert("Error");
    });
}

/**
****************************************************************************************************************************************
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




























//
/////**
//*
//*/
//var selector=function(id)
//{
//    this.variable=id;
//}
//selector.prototype.run=function()
//{
//    this.objeto=$(this.variable);
//    this.objeto.datepicker(
//    {
//        dateFormat: 'yy-mm-dd',
//        onSelect: function(dateText, inst)
//        {
//            $("#datepicker_value").val(dateText);
//        }
//    });
//}
///**
//*
//*/
//var navegar=function()
//{
//    this.enlaces='a#flecha-forward, a#flecha-backward';
//    this.main='#capa';
//    this.nueva='.div';
//}
//navegar.prototype.run=function()
//{
//    this.boton=$(this.enlaces);
//    this.objetoMain=$(this.main);
//    this.objetoNueva=$(this.nueva);
//    this.pisaAqui();
//}
//
//navegar.prototype.pisaAqui=function()
//{
//    var self=this;
//    this.boton.on('click',function(e)
//    {
//        e.preventDefault();
//        self.url=$(this).attr('href');
//        if(self.url=="/")
//        {
//            self.vuelta();
//        }
//        else
//        {
//            self.ida();
//        }
//    });
//}
//
//navegar.prototype.ida=function()
//{
//    var self=this;
//    this.objetoNueva.load(this.url,function()
//    {
//        self.objetoMain.toggle('slide');
//        self.objetoNueva.fadeIn('slow');
//    });
//}
//navegar.prototype.vuelta=function()
//{
//    var self=this;
//    this.objetoNueva.load(this.url,function()
//    {
//        self.objetoNueva.fadeOut('slow');
//        self.objetoMain.toggle('slide');
//    });
//}
///**
//**************************************************************************************************************************
//*/
//var ajax=function()
//{
//    this.formulario=null;
//    this.mail="/site/mail";
//    this.excel="/site/excel";
//}
//ajax.prototype.run=function()
//{
//    var self=this;
//    $('#mail,#excel').on('click',function(e)
//    {           
////        $("body").append("<div class='cargando'><div class='mensaje'><h1>Espere un momento por favor</h1><img src='/images/circular.gif'width='95px' height='95px'/></div></div>");  
//        setTimeout(function()
//        {
//            $('.cargando').remove();
//        }, 3000);
//        e.preventDefault();
////        var datefecha = $('input#datepicker_value').val().length;
//        var numero = $('input[type="checkbox"]').filter(function()
//        { 
//            return $(this).is(':checked');
//        });
//        if (numero.length >0 && datefecha.length >0)
//        {
//            var tipo = $(this).attr('id');
//            if (tipo == "mail")
//            { 
//                self.getFormPost();
//                self.enviarMail();
//            }
//            else
//            {
//                self.getFormPost();
//                for(var i = 0; i <= self.formulario.length - 2; i++)
//                {
//                    fecha=self.formulario[self.formulario.length-1].value;
//                    nombre=self.formulario[i].name;
//                    valor=self.formulario[i].value;
//                    var ventana=window.open(self.excel+"?fecha="+fecha+"&"+nombre+"="+valor,"Archivos Excel");
//                };
//            }
//        }   
//        else
//        {
////            $('.mensaje').html("<h2>Debe seleccionar al menos un tipo de reporte y una fecha</h2><img src='/images/stop.png'width='95px' height='95px'/>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////        }, 3000);
//                alert ('faltan datos');
//        }
//    });
//};
//ajax.prototype.getFormPost=function()
//{
//    this.formulario=$("#formRutinarios").serializeArray();
//};
//ajax.prototype.enviarMail=function()
//{
//    var self=this;
//    var opciones=
//    {
//        url:this.mail,
//        data:this.formulario,
//        type:'POST'
//    };
//    this.envio=$.ajax(opciones).done(function(datos)
//    {
////        $('.mensaje').html("<h1 class='exito'>Mensaje Enviado(datos)</h1><img src='/images/si.png'width='95px' height='95px'/>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////        }, 5000);
//           alert ('enviado');
//    }).fail(function()
//    {
////        $('.mensaje').html("<h1 class='fail'>Ups! Ocurrio un problema</h1><img src='/images/no.png'width='95px' height='95px'/><br>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////        }, 5000);
//         alert ('error');
//    });
//}
///**
// * 
// * @param {type} source
// * @returns {undefined}********************************************************************************************************
// */
//function marcar(source)
//{
//    checkboxes = document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
//    for (i = 0; i < checkboxes.length; i++) //recoremos todos los controles
//    {
//        if (checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
//        {
//            checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
//        }
//    }
//};
//
//var ventana=new navegar();
//var fecha=new selector("#datepicker");
//var ejecutar=new ajax();
//$(document).on('ready',function()
//{
//    ejecutar.run();
//    ventana.run();
//    fecha.run();
//    marcar();
//    $(this).ajaxComplete(function()
//    {
//        fecha.run();
//        marcar();
//    });
//});
//
////*/
////var ajax=function()
////{
////    this.formulario=null;
////    this.mail="/site/enviarmail";
////}
////ajax.prototype.run=function()
////{
////    var self=this;
////    $('#mail,#excel').on('click',function(e)
////    {
////        e.preventDefault();
////        var tipo=$(this).attr('id');
////        if(tipo=="mail")
////        {
////            self.getForm();
////            self.enviarMail();
////        }
////        else
////        {
////            alert("Excel aun en desarrollo");
////        }
////    });
////}
////$('#mail').click(function()
////{
//   // $("body").append("<div class='cargando'><div class='mensaje'><h1>Espere un momento por favor</h1><img src='/images/circular.gif'width='95px' height='95px'/></div></div>");
////}); 
////ajax.prototype.getForm=function()
////{
////    this.formulario=$("#formRutinarios").serializeArray();
////}
////ajax.prototype.enviarMail=function()
////{
////    var self=this;
////    var opciones=
////    {
////        url:this.mail,
////        data:this.formulario,
////        type:'POST'
////    };
////    $.ajax(opciones).done(function(datos)
////    {
////        jQuery('.mensaje').html("<h1 class='exito'>Mensaje Enviado</h1><img src='/images/si.png'width='95px' height='95px'/>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////
////        }, 5000);
////    }).fail(function()
////    {
////        jQuery('.mensaje').html("<h1 class='fail'>Ups! Ocurrion un problema</h1><img src='/images/no.png'width='95px' height='95px'/><br>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////
////        }, 5000);
////    });
////    
////};
//
//
////
////
////
////fvbfdghfdhgdfgdfgdfgdfgdfgdfgfdfgfdgdfgdfgdfgdfgdfgdfgdgdgdfgdf..............thfthfhfghfghfghg
////
////
////
////ajax.prototype.getFormPost=function()
////{
////    this.formulario=$("#formRutinarios").serializeArray();
////};
////ajax.prototype.enviarMail=function()
////{
////    var self=this;
////    var opciones=
////    {
////        url:this.mail,
////        data:this.formulario,
////        type:'POST'
////    };
////    this.envio=$.ajax(opciones).done(function(datos)
////    {
////        $('.mensaje').html("<h1 class='exito'>Mensaje Enviado(datos)</h1><img src='/images/si.png'width='95px' height='95px'/>");
////        setTimeout(function()
////        {
////            $('.cargando').remove();
////        }, 5000);
////    }).fail(function()
////    {
//        $('.mensaje').html("<h1 class='fail'>Ups! Ocurrio un problema</h1><img src='/images/no.png'width='95px' height='95px'/><br>");
//        setTimeout(function()
//        {
//            $('.cargando').remove();
//        }, 5000);
//         alert ('hola');
////    });
////}
