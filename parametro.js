// Obtén la URL actual
const urlActual = window.location.href;

// Verifica si el parámetro '??' ya está presente en la URL
const parametros = new URLSearchParams(window.location.search);
let carpetaNombre = parametros.get('??');

if (!carpetaNombre) {
  // Si '??' no está presente, genera una cadena aleatoria
  carpetaNombre = generarCadenaAleatoria();
  // Agrega el parámetro '??' a la URL
  const urlConParametro = `${window.location.origin}${window.location.pathname}?${parametros.toString()}??=${carpetaNombre}`;
  // Redirige a la nueva URL con el parámetro '??'
  window.location.href = urlConParametro;
}

// Función para generar una cadena aleatoria
function generarCadenaAleatoria() {
  const caracteres = "abcdefghijklmnopqrstuvwxyz023456789";
  let cadenaAleatoria = "";
  for (let i = 0; i < 3; i++) { 
    const caracterAleatorio = caracteres.charAt(
      Math.floor(Math.random() * caracteres.length)
    );
    cadenaAleatoria += caracterAleatorio;
  }
  return cadenaAleatoria;
}
