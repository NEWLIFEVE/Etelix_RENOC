 var TRACK = (function() {
                var p = document.createElement('p');
                document.body.appendChild(p);
                return function(str) {
                    p.innerHTML = str;
                }
            }());
            var fade_timer,
                    going_left = true,
                    a = document.getElementById('a'),
                    capa = document.getElementById('capa'),
                    one = document.getElementById('one'),
                    two = document.getElementById('two'),
                    three = document.getElementById('three'),
                    set_opacity = function(elem, o) {
                elem.style.opacity = o;
                elem.style.filter = 'alpha(opacity=' + o * 100 + ')';
            },
                    swap = function(fade_in, fade_out, end_left) {
                var start_time = +new Date(), total_time = 750, end_time = start_time + total_time,
                        start_left = parseFloat(capa.style.left) || 0, total_left = end_left - start_left,
                        in_start_o = parseFloat(fade_in.style.opacity) || 0, in_total_o = 1 - in_start_o,
                        out_start_o = parseFloat(fade_out.style.opacity) || 1, out_total_o = -out_start_o;

                fade_timer = setInterval(function() {
                    var percent = +new Date(),
                            current_time = percent > end_time ? 1 : (percent - start_time) / total_time;
                    percent = (1 - Math.cos(current_time * Math.PI)) / 2;

                    set_opacity(fade_in, in_start_o + percent * in_total_o);
                    set_opacity(fade_out, out_start_o + percent * out_total_o);
                    capa.style.left = (start_left + percent * total_left) + 'px';

                    if (current_time === 1) {
                        clearInterval(fade_timer);
                    }
                }, 40);
            };
            
            

            
$('.a').on('click',function () {
document.getElementById('fondo').onclick = function(){
          clearInterval(fade_timer);
                if (going_left) {
                    going_left = false;
                    swap(three, one, -1480);
                } else {
                    going_left = true;
                    swap(two, 0);
                } 
$('.div').fadeIn(500, function () {
$('.span').fadeIn(300);
});
return false;

};});



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
            
      $(function() {
$("#datepicker").datepicker({
   onSelect: function(dateText, inst) {
      $("#datepicker_value").val(dateText);
   }
});
      });
  
  
//  .......................pruebas..PRUEBAS....................................
//   function miFuncion() {
//    alert ("no estas cansado");
//
//    };
//    
//    function getRequest() {
//    var req = false;
//    try{
//        // most browsers
//        req = new XMLHttpRequest();
//    } catch (e){
//        // IE
//        try{
//            req = new ActiveXObject("Msxml2.XMLHTTP");
//        } catch (e) {
//            // try an older version
//            try{
//                req = new ActiveXObject("Microsoft.XMLHTTP");
//            } catch (e){
//                return false;
//            }
//        }
//    }
//    return req;
//}
//
//function getOutput() {
//  var ajax = getRequest();
//  ajax.onreadystatechange = function(){
//      if(ajax.readyState == 4){
//          document.getElementById('output').innerHTML = ajax.responseText;
//      }
//  }
//  ajax.open("GET", "http://renoc.local/AltoImpacto.php", true);
//  ajax.send(null);
//}
//    
//    
////          // esperamos que el DOM cargue
//        $(document).ready(function() { 
//            // definimos las opciones del plugin AJAX FORM
//            var opciones= {
//                               beforeSubmit: mostrarLoader, //funcion que se ejecuta antes de enviar el form
//                               success: mostrarRespuesta, //funcion que se ejecuta una vez enviado el formulario
//							   
//            };
//             //asignamos el plugin ajaxForm al formulario myForm y le pasamos las opciones
//            $('#myForm').ajaxForm(opciones) ; 
//            
//             //lugar donde defino las funciones que utilizo dentro de "opciones"
//             function mostrarLoader(){
//                      $("#loader_gif").fadeIn("slow");
//             };
//             function mostrarRespuesta (responseText){
//				           alert("Mensaje enviado: "+responseText);
//                          $("#loader_gif").fadeOut("slow");
//                          $("#ajax_loader").append("<br>Mensaje: "+responseText);
//             };
//   
//        }); 

////      $.ajax({
//  url: "http://renoc.local/AltoImpactoRetail.php",
//  data: {'json':'datas'},
//  contentType: "application/json; charset=utf-8",
//  dataFilter: function(data) {
//    var resp = eval('(' + data + ')');
//    return resp;
//  },
//  success: function(response, status, xhr){
//    $('#idd').html(response.property);
//  $('#resultado').load('http://renoc.local/AltoImpactoRetail.php');
//  }
//   
//   
//});  
//    
//    
//    
//    
//    
//    
//$(document).ready(function(){
//   $("#enlaceajax").click(function(evento){
//      evento.preventDefault();
//      $("#destino").load("index.php", {nombre: "Pepe", edad: 45}, function(){
//         alert("recibidos los datos por ajax");
//      });
//   });
//})
//
////$(document).ready(function(){
////   $("#enlaceajax").click(function(evento){
////      evento.preventDefault();
////      $("#destino").load("http://renoc.local/AltoImpactoRetail.php");
////   });
////});
//$(function(){
//    $("#JqAjaxForm").submit(function(e){
//       e.preventDefault();
// 
//        dataString = $("#JqAjaxForm").serialize();
//     
//        $.ajax({
//        type: "POST",
//        url: "funciones.php",
//        data: dataString,
//        dataType: "json",
//        success: function(data) {
//         
//            if(data.email_check == "invalid"){
//                $("#message_ajax").html("<div class='errorMessage'>Sorry " + data.name + ", " + data.email + " is NOT a valid e-mail address. Try again.</div>");
//            } else {
//                $("#message_ajax").html("<div class='successMessage'>" + data.email + " is a valid e-mail address. Thank you, " + data.name + ".</div>");
//            }
//          
//        }
//           
//        });         
//         
//    });
//});
//
//
//$(document).ready( function(){
// 
//// Detect if hyperlink has been clicked //
//$("a[title=submit_button]").click( function(){
// 
//// Pass the form values to the php file	
//$.post('pass_value.php', $("#form").serialize(), function(ret){
// 
//	// Detect if values have been passed back	
//	if(ret!=""){
//	// alert windows shows the returned value from php
//	alert("Value passed back from the php file... " + ret);
//	}
// 
//});
// 
//// Important stops the page refreshing
//return false;
// 
//}); 
//});
//
//
//
