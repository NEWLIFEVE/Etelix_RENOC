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
      
            $(function() {

      });

 