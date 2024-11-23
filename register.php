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
          
          <!-- Formulario de Registro -->
          <form action="#" id="manage_user" class="sign-in-form">
            <h2 class="title">Ingresa tus datos</h2>
            <div class="input-field">
              <i class="fas fa-building"></i>
              <input type="text" id="empresa" name="empresa" placeholder="Nombre de la Empresa" required />
            </div>
            <div class="input-field">
              <i class="fas fa-user-circle"></i>
              <input type="text" id="firstname" name="firstname" placeholder="Nombres" required />
            </div>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" id="lastname" name="lastname" placeholder="Apellidos" required />
            </div>
            <div class="input-field">
              <i class="fas fa-globe"></i>
              <select id="pais" name="pais" class="select-transparente" required>
                <option selected disabled>Seleccione su País</option>
                <?php 
                include "db_connect.php";
                $departments = $conn->query("SELECT * FROM paises WHERE pais='Honduras'");
                while($row=$departments->fetch_assoc()):
                ?>
                  <option value="<?php echo $row['pais'] ?>"><?php echo $row['pais'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="input-field">
              <i class="fas fa-flag"></i>
              <select id="departamento" name="departamento" class="select-transparente" required>
                <option selected disabled>Seleccione su Departamento</option>
                <?php 
                $departments = $conn->query("SELECT * FROM departamentos_lista ORDER BY departamento ASC");
                while($row=$departments->fetch_assoc()):
                ?>
                  <option value="<?php echo $row['id'] ?>"><?php echo $row['departamento'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="input-field">
              <i class="fas fa-hotel"></i>
              <select id="municipio" name="municipio" class="select-transparente" required>
                <option selected disabled>Seleccione su Municipio</option>
              </select>
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" id="email" name="email" placeholder="Correo Electrónico" required />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" id="password" name="password" placeholder="Contraseña" required />
            </div>
            <div class="input-field">
              <i class="fas fa-file"></i>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this)">
              </div>
            </div>
            <input type="submit" class="btn" value="Crear cuenta" />
          </form>
        </div>
      </div>
    </div>

    <div class="panels-container">
        <!-- Panel de registro -->
        <div class="panel left-panel">
          <div class="content">
            <h3>Ya tienes una cuenta creada?</h3>
            <p>Si ya tienes una cuenta creada, puedes acceder directamente aquí.</p>
            <button class="btn transparent" id="sign-up-btn" href="register.php" >Iniciar Sesión</button>
            <script>
            document.getElementById('sign-up-btn').addEventListener('click', function() {
            window.location.href = 'login.php'; // Redirige a la página de registro
            });
            </script>
          </div>
          <img src="assets/uploads/img-login/register.svg" class="image" alt="" />
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

      // Manejo de la selección de departamento
      document.getElementById('departamento').addEventListener('change', function() {
        var selectedValue = this.value;
        
        var formData = new FormData();
        formData.append('opcion', selectedValue);

        fetch('valorSeleccionadoDepartamentoLogin.php', {
          method: 'POST',
          body: formData
        }).then(response => response.text())
          .then(data => {
            document.getElementById('municipio').innerHTML = data;
          }).catch(err => console.error(err));
      });
      
            document.getElementById('manage_user').addEventListener('submit', function (e) {
            e.preventDefault();
            console.log('Formulario enviado, evento detectado.');

            // Mostrar el modal de progreso
            Swal.fire({
                title: 'Procesando...',
                html: 'Por favor espera un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Muestra el spinner
                }
            });

            // Crear un objeto FormData a partir del formulario
            const formData = new FormData(this);

            // Realizar la solicitud usando fetch
            fetch('ajax.php?action=save_user', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json()) // Asegúrate de que el servidor responde con JSON
            .then(data => {
                console.log('Respuesta del servidor:', data);

                // Cerrar el modal de progreso
                Swal.close();

                // Manejo de la respuesta
                if (data.status === 'success') {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1500
                     }).then(() => {
                    // Limpiar el formulario después de mostrar el mensaje
                    document.getElementById('manage_user').reset();
                    });
                } else if (data.status === 'failure') {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "¡Correo Electrónico ya está siendo utilizado!",
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
            .catch(err => {
                console.error('Error:', err);

                // Cerrar el modal de progreso en caso de error
                Swal.close();

                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Hubo un problema al procesar tu solicitud.",
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });

    </script>
  </body>
</html>
