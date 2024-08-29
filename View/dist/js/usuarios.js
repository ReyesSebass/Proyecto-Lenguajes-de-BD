function ConsultarNombre(nombreBoton)
{
    document.getElementById(nombreBoton).disabled = true;
    let identificacion =  document.getElementById("txtIdentificacion").value;

    if(identificacion.length >= 9)
    {
        $.ajax({
            type : 'GET',
            url : 'https://apis.gometa.org/cedulas/' + identificacion,
            dataType : 'json',
            success: function(data){
                if(data.resultcount > 0)
                {
                    document.getElementById(nombreBoton).disabled = false;
                    document.getElementById("txtNombre").value = data.nombre;
                }
                else
                {
                    document.getElementById("txtNombre").value = "";
                }   
            }
        });  
    } 
    else
    {
        document.getElementById("txtNombre").value = "";
    }
}