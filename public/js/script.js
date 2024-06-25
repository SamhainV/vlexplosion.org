document.addEventListener("DOMContentLoaded", function () {
  /***************************************************************/
  /******* script utilizado en login.php *************************/
  /***************************************************************/
  const passwordInput = document.querySelector('input[name="password"]');
  const usernameInput = document.querySelector('input[name="username"]');
  const form = document.querySelector("form");

  // Verificar si los elementos existen antes de asignar eventos
  if (passwordInput) {
    passwordInput.oninput = function () {
      if (passwordInput.validity.patternMismatch) {
        passwordInput.setCustomValidity(
          "Password no válida.\nLa contraseña debe tener entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula. Puede tener otros símbolos."
        );
      } else {
        passwordInput.setCustomValidity("");
      }
    };
  }
  if (form && usernameInput) {
    form.addEventListener("submit", function (event) {
      // Limpia el valor del input de username antes de enviar el formulario
      usernameInput.value = usernameInput.value.trim();
    });
  }
  /***************************************************************/
  /******* FIN! script utilizado en login.php ********************/
  /***************************************************************/
  const images = [
    "images/Avalon.jpeg",
    "images/DepecheMode.jpeg",
    "images/ScaryMonster.jpeg",
    "images/MusicFTMass.jpeg",
    // Agrega más rutas de imágenes a tu array
  ];

  let currentImageIndex = 0;
  let imageElement = null;

  // Función para obtener el elemento de imagen
  function getImageElement() {
    return document.getElementById("random-image");
  }
  // Función para obtener el índice de la siguiente imagen
  function getNextImageIndex() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
  }
  // Función para cambiar la imagen
  function setNextImage() {
    // Obtén el elemento de imagen si aún no lo has hecho
    if (!imageElement) {
      imageElement = getImageElement();
      if (!imageElement) return; // Salir si el elemento no está presente en el DOM
    }

    getNextImageIndex(); // Obtén el índice de la siguiente imagen
    const imageUrl = images[currentImageIndex]; // Obtén la URL de la siguiente imagen

    // Inicia el efecto de fundido de salida para la imagen actual
    imageElement.style.opacity = 0;

    // Espera la duración de la transición antes de cambiar la imagen y de fundido de entrada
    setTimeout(() => {
      imageElement.src = imageUrl;
      imageElement.alt = `Imagen ${currentImageIndex + 1}`;

      // Muestra gradualmente la siguiente imagen
      imageElement.style.opacity = 1;
    }, 1000); // Este tiempo debe coincidir con la duración de tu transición CSS
  }

  /************************************************************/
  /***************** Comienzo de la ejecución *****************/
  /************************************************************/
  // Llama a la función para mostrar la primera imagen al cargar la página
  setNextImage();

  // Cambia automáticamente las imágenes cada 3 segundos
  setInterval(setNextImage, 3000);

  /************************************************************/
  /****************** Menú Desplegable ************************/
  /************************************************************/
  document.querySelector(".dropbtn").addEventListener("click", function () {
    var dropdownContent = document.querySelector(".dropdown-content");
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });

  // Cierra el menú si se hace clic fuera de él
  window.onclick = function (event) {
    if (!event.target.matches(".dropbtn")) {
      var dropdownContent = document.querySelector(".dropdown-content");
      if (dropdownContent.style.display === "block") {
        dropdownContent.style.display = "none";
      }
    }
  };
});
