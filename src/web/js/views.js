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
$(document).on('ready',function()
{
    ventana.run();
    $("#datepicker").datepicker(
    {
        onSelect: function(dateText, inst)
        {
            $("#datepicker_value").val(dateText);
        }
    });
    marcar();
});