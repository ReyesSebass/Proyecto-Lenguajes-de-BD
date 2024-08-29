

var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
  
if (keyCode >= 48 && keyCode <= 57) {
  return true;
}

return false;


function SoloMontos(e, elemento)
{
let valor = elemento.value;
let keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;

if (keyCode >= 48 && keyCode <= 57) {
  return true;
}
else if(keyCode == 46){
    //el indexOf valida si un caracter se encuentra en un string = -1 es que no existe
    if(valor.indexOf(".") == -1){ 
        return true;
    }
}

return false;
}

function AnnadirProducto(idVehiculo, cantidad) {
  var cantidadIngresada = parseInt(document.getElementById('prd-' + idVehiculo).value);

  if (isNaN(cantidadIngresada) || cantidadIngresada <= 0 || cantidadIngresada > cantidad) {
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Cantidad no válida'
      });
      return;
  }

  console.log("Enviando datos:", {
      RegistrarCarrito: "FUNCION",
      idVehiculo: idVehiculo,
      Cantidad: cantidadIngresada
  });

  $.post("../Controller/CarritoController.php", {
      RegistrarCarrito: "FUNCION",
      idVehiculo: idVehiculo,
      Cantidad: cantidadIngresada
  }, function(response) {
      Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: response
      });
      
  }).fail(function(jqXHR, textStatus, errorThrown) {
      console.error("Error en la solicitud:", textStatus, errorThrown);
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Hubo un problema al procesar la solicitud'
      });
  });
}




function SoloNumeros(evt) {
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
  }
  return true;
}



function MostrarMensaje(titulo, mensaje, icono)
{
Swal.fire({
  title: titulo,
  text: mensaje,
  icon: icono
});
}

function MostrarMensajeRecarga(titulo, mensaje, icono)
{
Swal.fire({
  title: titulo,
  showDenyButton: false,
  showCancelButton: false,
  confirmButtonText: "Aceptar",
  text: mensaje,
  icon: icono
}).then((result) => {
  if (result.isConfirmed) {
      window.location.href = 'home.php';
  }
});

const btnToggle = document.querySelector('.toggle-btn');

btnToggle.addEventListener('click', function () {
console.log('clik')
document.getElementById('sidebar').classList.toggle('active');
console.log(document.getElementById('sidebar'))
});

}
