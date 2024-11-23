<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/dist/css/style-login.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");
      
      .select-transparente {
        background-color: #f0f0f0;
        border: none; 
        outline: none; 
        padding: 5px; 
        font-family: "Poppins", sans-serif;
        font-size: 1.1rem;
        color: #333;
      }

     
    </style>

    <title>Legal Agenda Demo</title>
    <link rel="icon" href="assets/uploads/iconolegalAgenda.ico" type="image/x-icon">
  </head>

  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          
          <!-- Formulario de Ingreso -->
          <form action="#" id="login-form" class="sign-in-form">
            <h2 class="title">Inicia Sesión</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="email" class="form-control" name="email" placeholder="Correo Electrónico" required />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" class="form-control" name="password" required placeholder="Contraseña">
            </div>
            <div class="input-field">
              <i class="fas fa-users"></i>
              <select name="login" class="select-transparente" required>
                <option selected disabled>Ingresar como</option>
                <option value="0">Empleado</option>
                <option value="1">Evaluador</option>
                <option value="2">Administrador</option>
              </select>
            </div>
            <input type="submit" value="Ingresar" class="btn solid" />
          </form>

         
        </div>
      </div>

      <div class="panels-container">
        <!-- Panel de registro -->
        <div class="panel left-panel">
          <div class="content">
            <h3>¿Eres nuevo aquí?</h3>
            <p>Registra tu empresa aquí para que puedas revisar las tareas de tus empleados.</p>
            <button class="btn transparent" id="sign-up-btn" href="register.php" >Crear Cuenta</button>
            <script>
            document.getElementById('sign-up-btn').addEventListener('click', function() {
            window.location.href = 'register.php'; // Redirige a la página de registro
            });
            </script>
          </div>
          <img src="assets/uploads/img-login/log.svg" class="image" alt="" />
        </div>

        

    <script>
      // Mostrar imagen al seleccionar archivo
      function displayImg(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            document.getElementById('cimg').src = e.target.result;
          }
          reader.readAsDataURL(input.files[0]);
        }
      }

      // Manejo del formulario de login
      document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        fetch('ajax.php?action=login', {
          method: 'POST',
          body: formData
        }).then(response => response.text())
          .then(data => {
            if (data == '1') {
              window.location.href = 'index.php?page=home';
            } else {
              Swal.fire({
                position: "center",
                icon: "error",
                title: "¡Usuario y/o Contraseña incorrectos!",
                showConfirmButton: false,
                timer: 1500
              });
            }
          }).catch(err => console.error(err));
      });

        
      document.getElementById('sign-in-btn').addEventListener('click', function() {
        console.log("Botón login clickeado"); 
     
      });

      document.getElementById('sign-up-btn').addEventListener('click', function() {
        console.log("Botón Crear Cuenta clickeado"); 
        // Añadir la clase que activa la visibilidad del formulario
       
      });
    </script>
  </body>
</html>
