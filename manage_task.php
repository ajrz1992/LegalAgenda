<?php 
include 'db_connect.php';
session_start();
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>


<script>

	  $(document).on('click', function(event) {
  var modal = $('#manage-task');
  if (!modal.is(event.target) && modal.has(event.target).length === 0) {
    modal.hide();
	//window.location.href = 'https://demolegalagenda.tecnologiainnovacion.com/index.php?page=task_list';
  }
  
});
 $('#documentosPendientes').hide();
const isEmail = input => /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(input);
	$('#no').prop('checked', true);
		$('#si').on('change', function() {
			//$('#si').prop('checked', true);
			$('#no').prop('checked', false);
            $('#documentosPendientes').show();
			$('#documentosPendientes').tagEditor({
  
  beforeTagSave: (field, editor, tags, tag, val) => {

    // make sure it is a formally valid email
    if (!isEmail(val)) {
      console.log(`"${val}" is not a valid email`);
      //return false;
    }
  }
});
    	});

		$('#no').on('change', function() {
			$('#no').prop('checked', true);
			$('#si').prop('checked', false);
            $('#documentosPendientes').hide();
    	});

	$(document).ready(function() {
		if($('#valor_id').val()==""){ 
			$('.botonAgregarCampos').hide();
					$('.imagendiv').hide();
		$('.imagenlogo').hide();
		$('.imagenlogodos').hide();
		$('.numeroExpediente').hide();
		$('.documentosAdjuntados').hide();
		$('.nombreFuncionario').hide();
		$('.lugarTarea').hide();
		$('.prioridad').show();
		}
		if($('#valor_id').val()!=""){ 
			$('.botonAgregarCampos').show();
					$('.imagendiv').show();
		$('.imagenlogo').show();
		$('.imagenlogodos').show();
		$('.numeroExpediente').show();
		$('.documentosAdjuntados').show();
		$('.nombreFuncionario').show();
		$('.lugarTarea').show();
		$('.prioridad').show();
		}
		function displayImg(input,_this) {
	    	if (input.files && input.files[0]) {
	        	var reader = new FileReader();
	        	reader.onload = function (e) {
	        		$('#cimg').attr('src', e.target.result);
	        	}
	        reader.readAsDataURL(input.files[0]);
	    	}
			$('#archivo').text($('#customFile').val().split('\\').pop());
		}

		$('.summernote').summernote({
        	height: 200,
				toolbar: [
					[ 'style', [ 'style' ] ],
					[ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
					[ 'fontname', [ 'fontname' ] ],
					[ 'fontsize', [ 'fontsize' ] ],
					[ 'color', [ 'color' ] ],
					[ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
					[ 'table', [ 'table' ] ],
					[ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
        		]
    	})
    
	
		$('#employee_id').select2({
			placeholder:'Elija empleado',
			width:'100%'
		})
		$('#prioridad').select2({
			placeholder:'Elija la prioridad',
			width:'100%'
		})
	});
			
    $('#manage-task').submit(function(e){
    	e.preventDefault()
    	start_load()
    	$.ajax({
    		url:'ajax.php?action=save_task',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Datos grabados satisfactoriamente',"Proceso Exitóso");
					$('#exampleModal').hide();
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
    	})
	})

    $('#exampleModal').on('show.bs.modal', function (event) {
  		var button = $(event.relatedTarget) // Button that triggered the modal
  		var recipient = button.data('whatever') // Extract info from data-* attributes
  		var modal = $(this)
  	modal.find('.modal-title').text('Agregar Campos')
	})
	$('#campo').on('input', function() {
        // Obtener el texto ingresado y eliminar los espacios y convertirlo a minúsculas
        var texto = $(this).val().replace(/\s+/g, '').toLowerCase();
        
        // Agregar el texto modificado al segundo campo de texto
        $('#campobdd').val(texto + "_"+$("#task").val().replace(/\s+/g, '').toLowerCase());
        
        // Establecer la variable de sesión con el texto modificado
        //sessionStorage.setItem('textoModificado', texto);
    });
	$('.cerrarModal').on('click', function() {
    	$('#exampleModal').hide();
		$('#manage-task').show();
	});
	$('.borrarCampo').on('click', function() {
		var idLabel = $(this).parent().attr("id");
		Swal.fire({
			title: "¿Estas seguro que deseas elimnar este campo?",
			text: "¡No podrás revertir esto!",
			icon: "warning",
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Si, ¡Eliminalo!"
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "borrar_campo.php", // URL del script PHP que procesará la solicitud
					type: "POST", // Método de la solicitud (POST o GET)
					data: { campo: idLabel }, // Datos a enviar (en este caso, el ID del registro)
					success: function(response) {
						// Manejar la respuesta del servidor si es necesario
						Swal.fire({
							title: "¡Eliminado!",
							text: "El campo se ha eliminado",
							icon: "success"
						});
						// Recargar la página o actualizar la interfaz de usuario según sea necesario
						location.reload(); // Recargar la página para mostrar los cambios
					},
					error: function(xhr, status, error) {
						// Manejar errores si los hay
						console.error(xhr.responseText);
					}
            	});
			}
		});
	});
	$('.guardarCampos').on('click', function() {
		campo=$('#campo').val();
		campobdd=$('#campobdd').val();
		tipo=$('#tipo').val();
		idTarea=$('#valor_id').val();
		$.ajax({
				url:'guardarCampos.php',
				data: {campo: campo, tipo: tipo, idtarea: idTarea, campobdd: campobdd},
				method: 'POST',
				type: 'POST',
				success:function(resp){
						alert_toast('Datos grabados satisfactoriamente',"Proceso Exitoso");
						setTimeout(function(){
							location.reload()
						},1500)
				},
				error: function(xhr, status, error) {
        			Swal.fire({
					position: "center",
					icon: "error",
					title: "El nombre del Campo bdd ya esta utilizado, prueba con otro",
					showConfirmButton: false,
					timer: 2000
				});
				$('#campobdd').val('');
				$('#campobdd').focus();
        			console.error(error);
    		}
		})
	});
	$(".preguntaTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Tarea",
		html: "<b>Indica aquí el nombre o proceso que realizarás.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
		$(".asignarTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Asignar a",
		html: "<b>Aquí puede asignar la tarea a otra persona.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
			$(".fechaVencimientoTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Plazo de Vencimiento",
		html: "<b>Utiliza este campo para establecer una fecha límite para la presentación del expediente o para asignar un plazo a otra persona.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
	$(".documentosPendientesTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Documentos Pendientes",
		html: "<b>Aquí puedes listar los documentos que aún faltan para completar el expediente.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
		$(".plagoLegalTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Vencimiento de Plazo Legal",
		html: "<b>Indica aquí la fecha límite establecida por una autoridad gubernamental o judicial.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
			$(".documentosAdjuntadosTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Documentos Adjuntados",
		html: "<b>Indica qué documentos has adjuntado al expediente.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
				$(".nombreFuncionarioTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Nombre del Funcionario",
		html: "<b>Indica aquí el nombre del funcionario público o judicial a cargo del expediente</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
					$(".lugarDondeTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Lugar Donde se presentó la Tarea",
		html: "<b>Especifica dónde se presentó el expediente.</b> ",
		timer: 5000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
						$(".logotipoTarea").on('click', function() {
		let timerInterval;
		Swal.fire({
		title: "Logotipo",
		html: "<b>Puedes cargar una imagen relacionada con la tarea, como el logotipo de tu bufete, empresa o el de tu cliente, para distinguirla de las demás tareas.</b> ",
		timer: 15000,
		timerProgressBar: true,
		didOpen: () => {
			Swal.showLoading();
			const timer = Swal.getPopup().querySelector("b");
			timerInterval = setInterval(() => {
			timer.textContent = `${Swal.getTimerLeft()}`;
			}, 100);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
		}).then((result) => {
		/* Read more about handling dismissals below */
		if (result.dismiss === Swal.DismissReason.timer) {
			console.log("I was closed by the timer");
		}
		});
	});
</script>
<button type="button" class="btn btn-primary botonAgregarCampos" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" style="display:none"> <i class="fas fa-plus"></i> Agregar Campos</button><br><br>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Campos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Nombre del Campo</label>
            <input type="text" class="form-control form-control-sm" id="campo" placeholder="Escribe el nombre del campo">
          </div>
          <div class="form-group" style="display:none">
            <label for="recipient-name" class="col-form-label">Nombre del Campo BDD</label>
            <input type="text" class="form-control form-control-sm"  id="campobdd" placeholder="Escribe el nombre del campo EN MINISCULA, SIN ESPACIOS Y SINCARACTERES ESPECIALES">
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Tipo del Campo</label>
            <select id="tipo" class="form-control form-control-sm" required="">
							<option value="varchar(100)">Texto</option>
							<!-- <option value="int">Numérico</option> -->
							<option value="date">Fecha</option>
						</select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary cerrarModal">Cerrar</button>
        <button type="button" class="btn btn-primary guardarCampos">Guardar</button>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
	<form action="" id="manage-task">
		<input type="hidden" id="valor_id" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-5">
					<div class="form-group">
						<label for="">Tarea </label>&nbsp<i class="fa fa-question-circle preguntaTarea" aria-hidden="true"></i>
						<input type="text" class="form-control form-control-sm" id="task" name="task" value="<?php echo isset($task) ? $task : '' ?>" required placeholder="Escriba el nombre de su tarea">
					</div>
					<div class="form-group">
						<label for="">Asignar a</label>&nbsp<i class="fa fa-question-circle asignarTarea" aria-hidden="true"></i>
						<select name="employee_id" id="employee_id" class="form-control form-control-sm" required="">
							<option value=""></option>
							<optgroup label="Empleados">
							<?php 
							$employees = $conn->query("SELECT concat(firstname,' ',middlename,' ',lastname) AS name,id FROM employee_list where empresa='{$_SESSION['login_empresa']}'");
							while($row=$employees->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && $employee_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
							</optgroup>
							<?php endwhile; ?>
							<optgroup label="Administradores">
							<?php 
							$employees = $conn->query("SELECT concat(firstname,' ',lastname) AS name, id FROM users where empresa='{$_SESSION['login_empresa']}'");
							while($row=$employees->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($employee_id) && $employee_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
							</optgroup>
							<?php endwhile; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="">Cliente</label>
						<input type="text" class="form-control form-control-sm" name="cliente" value="<?php echo isset($cliente) ? $cliente : '' ?>" required placeholder="Escriba el nombre del Cliente">
					</div>
					<div class="form-group">
						<label for="">Plazo de Vencimiento</label>&nbsp<i class="fa fa-question-circle fechaVencimientoTarea" aria-hidden="true"></i>
						<input type="date" class="form-control form-control-sm" name="due_date" value="<?php echo isset($due_date) ? $due_date : date("Y-m-d") ?>" required>
					</div>
					<div id="tagContainer" class="form-group">
						<label for="">Documentos Pendientes: </label>&nbsp<i class="fa fa-question-circle documentosPendientesTarea" aria-hidden="true"></i>
						<label for="">Si</label>
						<input type="checkbox" id="si" value="si">
						<label for="">No</label>
						<input type="checkbox" id="no" value="no">
						<input type="text" class="form-control form-control-sm" id="documentosPendientes" name="documentosPendientes" value="<?php echo isset($documentosPendientes) ? $documentosPendientes : '' ?>" required placeholder="Escriba los documentos pendientes">
					</div>
					<div class="form-group">
						<label for="">Vencimiento de Plazo Legal </label>&nbsp<i class="fa fa-question-circle plagoLegalTarea" aria-hidden="true"></i>
						<input type="number" class="form-control form-control-sm" name="plazoLegal" value="<?php echo isset($plazoLegal) ? $plazoLegal : '' ?>" required placeholder="Escriba el Plazo" maxlength="4" min="0" max="9999">
					</div>
					<div class="form-group numeroExpediente" style="display:none">
						<label for="">Numero de Expediente</label>
						<input type="text" class="form-control form-control-sm" name="numeroExpediente" value="<?php echo isset($numeroExpediente) ? $numeroExpediente : '' ?>" placeholder="Escriba el número de expediente">
					</div>
					<div class="form-group documentosAdjuntados" style="display:none">
						<label for="">Documentos Adjuntados </label>&nbsp<i class="fa fa-question-circle documentosAdjuntadosTarea" aria-hidden="true"></i>
						<input type="text" class="form-control form-control-sm" name="documentosAdjuntados" value="<?php echo isset($documentosAdjuntados) ? $documentosAdjuntados : '' ?>" placeholder="Escriba los documentos adjuntados">
					</div>
					<div class="form-group nombreFuncionario"  style="display:none">
						<label for="">Nombre del Funcionario </label>&nbsp<i class="fa fa-question-circle nombreFuncionarioTarea" aria-hidden="true"></i>
						<input type="text" class="form-control form-control-sm" name="nombreFuncionario" value="<?php echo isset($nombreFuncionario) ? $nombreFuncionario : '' ?>" placeholder="Escriba el nombre del Funcionario">
					</div>
					<div class="form-group lugarTarea" style="display:none">
						<label for="">Lugar donde se presentó la Tarea </label>&nbsp<i class="fa fa-question-circle lugarDondeTarea" aria-hidden="true"></i>
						<input type="text" class="form-control form-control-sm" name="lugarTarea" value="<?php echo isset($lugarTarea) ? $lugarTarea : '' ?>" placeholder="Escriba el lugar">
					</div>
					<div class="form-group prioridad" style="display:none">
						<label for="">Nivel de Prioridad</label>
						<select name="prioridad" id="prioridad" class="form-control form-control-sm" required="">
						<option value="<?php echo isset($prioridad) ? $prioridad : '' ?>"><?php echo isset($prioridad) ? $prioridad : '' ?></option>
							<option value="Urgente">Urgente</option>
							<option value="Importante">Importante</option>
							<option value="Medio">Medio</option>
							<option value="Bajo">Bajo</option>
						</select>
					</div>
					<div class="form-group imagendiv">
						<label for="" class="control-label">Logotipo </label>&nbsp<i class="fa fa-question-circle logtipoTarea" aria-hidden="true"></i>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this,$(this))">
		                    <label class="custom-file-label" id="archivo" for="customFile">Escoge archivo</label>
            			</div>
					</div>
					<div class="form-group d-flex justify-content-center imagenlogo">
						<img src="<?php echo isset($meta['avatar']) ? 'assets/uploads/'.$meta['avatar'] :'' ?>" alt="" id="cimg" class="img-fluid img-thumbnail imagenlogodos">
					</div>
					<div class="form-group" style="display:none;">
							<label class="control-label">Empresa</label>
							<input type="text" class="form-control form-control-sm" name="empresa" required value="<?php echo isset($_SESSION['login_empresa']) ? $_SESSION['login_empresa'] : '' ?>">
							<small id="#msg"></small>
						</div>
				</div>
		
				<?php		
					$valor = $conn->query("SELECT nombre from carpetas WHERE seleccionado=1 and id_usuario={$_SESSION['login_id']} and empresa='{$_SESSION['login_empresa']}'");
                     while($row=$valor->fetch_assoc()):
        				?>
                        <input type="hidden"  name="carpeta" value="<?php $v=$row['nombre']; echo trim($v); ?>"></input>
                    <?php endwhile; ?>
						<input type="hidden" name="seleccionado" value=1>
				<div class="col-md-7">
					<div class="form-group">
						<label for="">Descripción</label>
						<textarea name="description" id="" cols="30" rows="10" class="summernote form-control" placeholder="Escriba la descripcion aqui sin espacios a la izquierda">
							<?php echo isset($description) ? $description : '' ?>
						</textarea>
					</div>
					<?php 
							$cnuevos = $conn->query("SELECT * FROM camposnuevos_tareas where id_tarea=$id");
							while($row=$cnuevos->fetch_assoc()):
								$i=$row['campobdd'];
							?>
							<div class="form-group camposnuevos">
						<label id="<?php echo $row['campobdd']; ?>" for=""><?php echo $row['campo']; ?> <i class="fa fa-trash borrarCampo" aria-hidden="true"></i></label>
						<input type="<?php
						 if($row['tipo']=="varchar(100)"){
							echo "text";
						}
						if($row['tipo']=="int"){
							echo "number";
						}
						if($row['tipo']=="date"){
							echo "date";
						}
							?>" class="form-control form-control-sm" name="<?php echo $row['campobdd']; ?>" placeholder="<?php echo $row['campo']; ?>" 
							value="<?php
							$sql = "SELECT " .$i. " FROM task_list WHERE id=$id"; // Suponiendo que deseas el nombre del usuario con ID 1
							$resultado = $conn->query($sql);
							
							// Verificar si se encontraron resultados
							if ($resultado->num_rows > 0) {
								// Obtener el valor del campo 'nombre'
								$row = $resultado->fetch_assoc();
								$nombre = $row[$i];
							} else {
								$nombre = ""; // Asignar un valor por defecto si no se encontraron resultados
							}
							echo $nombre;
							?>">
					</div>
							<?php endwhile; ?>
				</div>
				
			</div>
		</div>
		
	</form>
</div>
