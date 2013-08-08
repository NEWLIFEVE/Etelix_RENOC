         
//------------------------------------ANIMACION DE ARRASTRAR Y DE RESALTAR LUEGO DE ARRASTRAR -------------------------------
    function slide(){
//        clearInterval(fade_timer);
//                if (going_left) {
//                    going_left = false;
//                    swap(three, one, -1480);
//                } else {
//                    going_left = true;
//                    swap(two, 0);
//                } 
            $('#capa').animate({
                opacity: 0.25,
                left: "-=50",
                width: "slide"
              }, 500, function() {
                  $(this).fadeOut()
                $('.div').fadeIn(500, function () {
                $('.capaOculta').fadeIn(300);
                }); 
              });
                   
    }

//document.getElementById('fondo').onclick = function(){
//          clearInterval(fade_timer);
//                if (going_left) {
//                    going_left = false;
//                    swap(three, one, -1480);
//                } else {
//                    going_left = true;
//                    swap(two, 0);
//                } 
//$('.div').fadeIn(500, function () {
//$('.capaOculta').fadeIn(300);
//});
//return false;
////----------------------FIN--------------ANIMACION DE ARRASTRAR Y DE RESALTAR LUEGO DE ARRASTRAR -------------------------------
//};
//       -------------FIN--------------------ARRASTRAR EL PANEL PRINCIPAL DE RENOC------------------------------------------------ 


//       ---------------------------------MARCAR TODOS LOS CHECKBOX DE RUTINARIOS------------------------------------------------ 

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
    //       -----------------FIN----------------MARCAR TODOS LOS CHECKBOX DE RUTINARIOS------------------------------------------------ 
    //       
//       ---------------------------------DATEPICKER DE RUTINARIOS------------------------------------------------     
      $(function() {
$("#datepicker").datepicker({
   onSelect: function(dateText, inst) {
      $("#datepicker_value").val(dateText);
   }
});
      });
      //       -------------FIN--------------------DATEPICKER DE RUTINARIOS------------------------------------------------ 
      
      
//------------------------------AQUI VA LA FUNCION PARA  ENVIAR DATOS AL PHP DE ENVIAR POR E-MAIL---------------   
   
//   
//          
////cuando hagamos submit al formulario con id id_del_formulario
////se procesara este script javascript
//$("#mail").click(function() {
//alert('hoola');
//$("#formRutinarios")(function(e)
//{  alert('hoola222');
//    e.preventDefault();
//    var fecha = $("#datepicker_value").val();
//    alert(fecha);
//    if (fecha != "")
//    {
//        if ($("#AI10").is(":checked"))
//        {
//            alert("Selecciono AltoImpacto +10$ para la Fecha:" + fecha);
//            $.ajax
//                    (
//                       {
//                            url: $(this).attr("/site/SiteController/EnviarMail"), //action del formulario, ej:
//                            //http://localhost/mi_proyecto/mi_controlador/mi_funcion
//                            type: $(this).attr("post"), //el método post o get del formulario
//                            data: $(this).serialize(), //obtenemos todos los datos del formulario
//                            error: function()
//                            {
//                                    //si hay un error mostramos un mensaje
//                            },
//                            success: function(data)
//                            {
//                                    alert(data); //hacemos algo cuando finalice todo correctamente
//                            }
//                        }
//                    );
//        }
//
//    }
//    ;
//});
//});
            $(function() 
            {
                var formulario = $("#formRutinarios").serialize();
                alert("Selecciono AltoImpacto +10$ para la Fecha:" + fecha);
                $.ajax({
                    url: $(this).attr("/site/SiteController/EnviarMail"),
                    data: "fecha=" + fecha,
                    type: $(this).attr("post"),
                    $("#results").text(formulario);
                     })
            }
            );

   
   
   
    $(function() {
                $("#mail").click(function() {
                var fecha = $("#datepicker_value").val();
                alert(fecha);
                if(fecha!=""){
                if($("#AI10").is(":checked")){                    
                    alert("Selecciono AltoImpacto +10$ para la Fecha:"+fecha);
                    $.ajax({

                       url: $(this).attr("/site/SiteController/EnviarMail"),

                        //url: 'PHPMailer_5.2.4/AltoImpacto.php',
                     //   url: 'site/Enviarmail',

                        data: "fecha="+fecha,
                       type: $(this).attr("post"),
                       data: $(this).serialize(), 
                        success: function(data){
                            alert (data);
//                           $('#respuestaAI').html(data);
                        }
                    });
                }
                if($("#AIR").is(":checked")){                    
                    alert("Selecciono AltoImpactoRetail +1$ para la Fecha:"+fecha);
//                    $.ajax({
//                        url: 'PHPMailer_5.2.4/AltoImpactoRetail.php',
//                        data: "fecha="+fecha,
//                        type: 'get',
//                        success: function(data){
//                            //alert (data);
//                            $('#respuestaAIR').html(data);
//                        }
//                    });
                }
                }else{
                alert('seleccione una fecha');
                }
        });
        
            });     
$(document).ready(function() {
    $("#one").click(function(event) {
        $(".div").load('site/rutinarios',slide());
        
    }
    );
    $("#two").click(function(event) {
          
        $(".div").load('site/especificos');
 
    }
    );
    $("#three").click(function(event) {
        
        $(".div").load( 'site/personalizados');
        
    }
    );

});
//-------------------FIN--------FUNCION PARA ABRIR LOS ENLACES DE RUTINARIOS, ESPECIFICOS Y PERSONALIZADOS---------------------------
           
