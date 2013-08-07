 //       ---------------------------------ARRASTRAR EL PANEL PRINCIPAL DE RENOC------------------------------------------------ 
// var TRACK = (function() {
//                var p = document.createElement('p');
//                document.body.appendChild(p);
//                return function(str) {
//                    p.innerHTML = str;
//                }
//            }());
//            var fade_timer,
//                    going_left = true,
//                    a = document.getElementById('a'),
//                    capa = document.getElementById('capa'),
//                    one = document.getElementById('one'),
//                    two = document.getElementById('two'),
//                    three = document.getElementById('three'),
//                    set_opacity = function(elem, o) {
//                elem.style.opacity = o;
//                elem.style.filter = 'alpha(opacity=' + o * 100 + ')';
//            },
//                    swap = function(fade_in, fade_out, end_left) {
//                var start_time = +new Date(), total_time = 750, end_time = start_time + total_time,
//                        start_left = parseFloat(capa.style.left) || 0, total_left = end_left - start_left,
//                        in_start_o = parseFloat(fade_in.style.opacity) || 0, in_total_o = 1 - in_start_o,
//                        out_start_o = parseFloat(fade_out.style.opacity) || 1, out_total_o = -out_start_o;
//
//                fade_timer = setInterval(function() {
//                    var percent = +new Date(),
//                            current_time = percent > end_time ? 1 : (percent - start_time) / total_time;
//                    percent = (1 - Math.cos(current_time * Math.PI)) / 2;
//
//                    set_opacity(fade_in, in_start_o + percent * in_total_o);
//                    set_opacity(fade_out, out_start_o + percent * out_total_o);
//                    capa.style.left = (start_left + percent * total_left) + 'px';
//
//                    if (current_time === 1) {
//                        clearInterval(fade_timer);
//                    }
//                }, 40);
//            };
//            
            
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
      
      
//     ------------------------------AQUI VA LA FUNCION PARA  ENVIAR DATOS AL PHP DE ENVIAR POR E-MAIL---------------   
   
    $(function() {
                $("#mail").click(function() {
                var fecha = $("#datepicker_value").val();
                alert(fecha);
                if(fecha!=""){
                if($("#AI10").is(":checked")){                    
                    alert("Selecciono AltoImpacto +10$ para la Fecha:"+fecha);
                    $.ajax({
                        url: 'site/prueba',
                        data: "fecha="+fecha,
                        type: 'get',
                        success: function(data){
                            alert (data);
//                            $('#respuestaAI').html(data);
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

//    $(function() {
//                function siRespuesta(r) {
//                    $('#respuesta').html(r); // Mostrar la respuesta del servidor en el div con el id "respuesta"
//                }
//
//                function siError(e) {
//                    alert('Ocurrió un error al realizar la petición: ' + e.statusText);
//                }
//
//                function peticion(e) {
//                    alert('entre');
//// Obtener valores de los campos de texto
////                    var parametros = {
////                        fecha: $('#datepicker_value').val()
////                    };
//                    var fecha = $('#datepicker_value').val();
//                    alert(fecha);
//// Realizar la petición
//                    var post = $.post(
//                            "http://renoc.local/AltoImpactoRetail.php", // Script que se ejecuta en el servidor
//                            fecha,
//                            siRespuesta, // Función que se ejecuta cuando el servidor responde
//                            'html' // Tipo de respuesta del servidor
//                            );
//
//                    /* Registrar evento de la petición (hay mas)
//                     (no es obligatorio implementarlo, pero es muy recomendable para detectar errores) */
//
//                    post.error(siError); // Si ocurrió un error al ejecutar la petición se ejecuta "siError"
//                }
//
//                $('#mail').click(peticion); // Registrar evento al boton "Calcular" con la funcion "peticion"
//            });
            
//     --------------FIN--------------- FUNCION PARA  ENVIAR DATOS AL PHP DE ENVIAR POR E-MAIL---------------   
        
     
//---------------------------FUNCION PARA ABRIR LOS ENLACES DE RUTINARIOS, ESPECIFICOS Y PERSONALIZADOS---------------------------
            
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
           
