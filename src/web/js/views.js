var selector=function(id)
{
    this.variable=id;
}
selector.prototype.run=function()
{
    this.objeto=$(this.variable);
    this.objeto.datepicker(
    {
        onSelect: function(dateText, inst)
        {
            $("#datepicker_value").val(dateText);
        }
    });
}

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

//            $(function() 
//            {
//                var formulario = $("#formRutinarios").serialize();
//                alert("Selecciono AltoImpacto +10$ para la Fecha:" + fecha);
//                $.ajax({
//                    url: $(this).attr("/site/SiteController/EnviarMail"),
//                    data: "fecha=" + fecha,
//                    type: $(this).attr("post"),
//                    $("#results").text(formulario);
//                     })
//            }
//            );
//
//   
//   
   
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
$(document).on('ready',function()
{
    ventana.run();
    fecha.run();
    marcar();
    $(this).ajaxComplete(function()
    {
        fecha.run();
        marcar();
    });
});
