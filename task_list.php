<?php include 'db_connect.php' ?>
<style>
	.view_task{
		display: flex; /* Utilizamos flexbox */
    justify-content: flex-end; /* Alineamos los elementos al extremo derecho */
    padding: 10px; /* Añadimos espacio alrededor del contenedor */
	}
	.button-right{
		position: absolute; /* Establecer posición absoluta */
            right: 0; /* Posicionar el botón a la derecha */
           
            margin-bottom: 10px; 
			background-color: transparent;
			border: none;
			padding: 0;
	}
</style>
<img src="assets/uploads/flecha.gif"  id="carpetaRaiz"><i>Regresar a Carpeta Raíz</i></img>
<h1>
<?php 
			$valor = $conn->query("SELECT * from carpetas WHERE seleccionado=1  and empresa='{$_SESSION['login_empresa']}'");
                     while($row=$valor->fetch_assoc()):
        				?>
                        <b> <?php echo $row['nombre'] ?> </b>
						
                    <?php endwhile; 
		?>
</h1>
<div class="col-lg-12">
<div class="card card-outline card-success">
		<div class="card-header">
		<div class="card-tools">
			
			<button class="btn btn-block btn-sm btn-default btn-flat border-primary" id="new_task"><i class="fa fa-plus"></i> Agregar Nueva Tarea</button>
		</div>
		<?php 
			$valor = $conn->query("SELECT * from carpetas WHERE seleccionado=1 and id_usuario={$_SESSION['login_id']} and empresa='{$_SESSION['login_empresa']}'");
                     while($row=$valor->fetch_assoc()):
        				?>
						<i class="fas fa-search"></i>
				<label for="">Buscar</label>
				<input type="text" id="search" placeholder="Nombre de la Tarea" autocomplete="off" value=""></input>
                    <?php endwhile; 
		?>
 			<?php if($_SESSION['login_type'] == 2): ?>
			
			<?php endif; ?>
		</div>
		<div class="row tarealist">
			<?php
				$i = 1;
				$where = " where t.empresa='{$_SESSION['login_empresa']}' and t.seleccionado=1";
				if($_SESSION['login_type'] == 0)
					$where = " where t.employee_id = '{$_SESSION['login_id']}' and t.empresa='{$_SESSION['login_empresa']}'";
				elseif($_SESSION['login_type'] == 1)
					$where = " where e.evaluator_id = {$_SESSION['login_id']} and e.empresa='{$_SESSION['login_empresa']}'";
				$qry = $conn->query("SELECT t.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as name FROM task_list t left join employee_list e on e.empresa = t.empresa $where order by unix_timestamp(t.date_created) asc");
				while($row= $qry->fetch_assoc()):
					$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
					unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
					$desc = strtr(html_entity_decode($row['description']),$trans);
					$desc=str_replace(array("<li>","</li>"), array("",", "), $desc);
			?>
<div class="card" style="width: 18rem;" data-nombre="<?php echo ucwords($row['task']) ?>">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle button-right" data-toggle="dropdown" aria-expanded="true">
							<i class="fa-solid fa-align-justify"></i>
		                    </button>
			                    <div class="dropdown-menu" style="">
								<div class="dropdown-divider"></div>
									<a class="dropdown-item view_task"  name="<?php echo $row['id'] ?>" onClick="view_task(this)" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa-regular fa-eye"></i> Ver Tarea</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item copiar" data-id="<?php echo $row['id'] ?>" style="display:none"><i class="fa fa-arrows"></i> Copiar a...</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item mover" data-id="<?php echo $row['id'] ?>"><i class="fa fa-hand-rock-o"></i> Mover a...</a>
									<div class="dropdown-divider"></div>
									<?php if($_SESSION['login_type'] == 2): ?>
									<a class="dropdown-item" onClick="manage_task(this)" name="<?php echo $row['id'] ?>" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fa-regular fa-pen-to-square"></i>  Editar</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?>
									<?php if($_SESSION['login_type'] == 0): ?>
									<?php if($row['status'] != 2): ?>
									<a class="dropdown-item new_progress" data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-task = '<?php echo ucwords($row['task']) ?>'  href="javascript:void(0)"><i class="fa fa-tasks"></i> Agregar Progreso</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?>
									<?php endif; ?>
									<a class="dropdown-item view_progress" onClick="view_progress(this)" name="<?php echo ucwords($row['task']) ?>" id="<?php echo $row['id'] ?>"
									data-pid = '<?php echo $row['pid'] ?>' data-tid = '<?php echo $row['id'] ?>'  data-task = '<?php echo ucwords($row['task']) ?>'  href="javascript:void(0)"><i class="fa-solid fa-bars-progress"></i> Ver Progreso</a>
								</div>
								
  <img class="card-img-top" src="assets/uploads/<?php echo $row['avatar'] ?>" alt=""></img>
  <div class="card-body">
    <h5 class="card-title"><b>Tarea: </b><?php echo ucwords($row['task']) ?></h5>
    <br>
								<p class="card-text"><b>Nombre del Cliente: </b><?php echo ucwords($row['cliente']) ?></p>
								<p class="card-text"><b>Fecha de Creación: </b><?php echo $row['date_created'] ?></p>
								<p class="card-text"><b>Estado: </b>
								<?php 
                        	if($row['status'] == 0){
						  		echo "<span class='badge badge-info'>Pendiente</span>";
                        	}elseif($row['status'] == 1){
						  		echo "<span class='badge badge-primary'>En-Progreso</span>";
                        	}elseif($row['status'] == 2){
						  		echo "<span class='badge badge-success'>Completo</span>";
                        	}
                        	if(strtotime($row['due_date']) < strtotime(date('Y-m-d'))){
						  		echo "<span class='badge badge-danger mx-1'>Vencido</span>";
                        	}
                        	?>
							</p>
							<p class="card-text"><b>Fecha de Presentación: </b><?php echo $row['due_date'] ?></b>
							<p class="card-text" style="display:none"><b>Plazo Legal: </b><?php echo $row['plazoLegal'] ?> días</b>
							<p class="card-text"><b>Cliente: </b><?php echo $row['cliente'] ?></b>
							<p class="card-text"><b>Prioridad: </b>
								<?php 
                        	if($row['prioridad'] == "Urgente"){
								echo "<span class='badge badge-danger mx-1'>Urgente</span>";
                        	}elseif($row['prioridad'] == "Importante"){
						  		echo "<span class='badge badge-warning'>Importante</span>";
                        	}elseif($row['prioridad'] == "Medio"){
						  		echo "<span class='badge badge-info'>Medio</span>";
                        	}
							elseif($row['prioridad'] == "Bajo"){
						  		echo "<span class='badge badge-success'>Bajo</span>";
                        	}
                        	?>
							</p>
							<p class="card-text" style="display:none"><b>Plazo: </b><?php $hoy=new DateTime(); $ddate=new datetime($row['due_date']); $diferencia=$hoy->diff($ddate); echo $diferencia->format('%R%a días'); ?></b>
							<p class="card-text"><b>Documentos Faltantes: </b>
								<?php 
									if($row['documentosPendientes']==""){
										echo "<span class='badge badge-success'>No hay Documentos Faltantes</span>";
									}else{
										$valores = explode(",", $row['documentosPendientes']);
										foreach ($valores as $valor) {
										echo "<span class='badge badge-danger mx-1'>".$valor."</span>";
									}
									}
									
							?>

    <a style="display:none" href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div>
			<?php endwhile; ?>
		</div>
	</div>
</div>
<style>
	table p{
		margin: unset !important;
	}
	table td{
		vertical-align: middle !important
	}
</style>
<script>
	$(document).on('keydown', function(event) {
  if (event.key === "Escape") {
	//$('#exampleModal').hide();
    //$('#manage-task').hide();
	window.location.href = 'http://localhost/LegalAgenda/index.php?page=task_list';
  }


});
	$(document).ready(function(){
		
		$('#carpetaRaiz').click(function(){
			window.location.href = 'http://localhost/LegalAgenda/index.php?page=carpetas';
		})
		$('#search').on('input', function() {
            var textoBusqueda = $(this).val().toLowerCase(); // Obtener el texto de búsqueda y convertirlo a minúsculas
                // Mostrar u ocultar divs según el texto de búsqueda
        $('.tarealist').children().each(function() {
			if ($(this).is('img')) {
            // Si el elemento es una imagen, no se oculta
            $(this).show();
        }
			var contenidoElemento = $(this).text().toLowerCase(); // Obtener el contenido del elemento y convertirlo a minúsculas
        
        // Mostrar u ocultar el elemento según el texto de búsqueda
		
        if (contenidoElemento.includes(textoBusqueda)) {
            $(this).show(); // Mostrar el elemento si coincide con el texto de búsqueda
        } else {
            $(this).hide(); // Ocultar el elemento si no coincide con el texto de búsqueda
        }

			
        });
		
    });
	$('#list').dataTable();
	$('#new_task').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Nueva Tarea","manage_task.php",'mid-large')
	})
	// $('.view_task').click(function(){
	// 	uni_modal("Ver Tarea","view_task.php?id="+$(this).attr('data-id'),'mid-large')
	// })
	// $('.manage_task').click(function(){
	// 	console.log("editar");
	// 	uni_modal("<i class='fa fa-edit'></i> Editar Tarea","editar_tarea.php?id="+$(this).attr('data-id'),'mid-large')
	// })
	$('.new_progress').click(function(){
		uni_modal("<i class='fa fa-plus'></i> Nuevo progreso de: "+$(this).attr('data-task'),"manage_progress.php?tid="+$(this).attr('data-tid'),'mid-large')
	})
	$('.mover').click(function(){
		id_valor= $(this).attr('data-id');
		// Hacer una solicitud AJAX para obtener los datos del servidor
$.ajax({
    url: 'obtener_carpetas.php',
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        // Construir las opciones del campo de selección
        var opcionesSelect = '';
        data.forEach(function(opcion) {
            opcionesSelect += `<option value="${opcion.id}">${opcion.nombre}</option>`;
        });

        // Abrir el SweetAlert con el campo de selección llenado
        Swal.fire({
            title: 'Seleccione una opción',
            html: `<select id="selectOpciones" class="swal2-select">${opcionesSelect}</select>`,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                //id_valor= $(this).attr('data-id');
				empresa_valor=$('#selectOpciones option:selected').text();
					$.ajax({
			url:'mover_carpetas.php',
			method:'POST',
			data:{id_valor: id_valor, empresa_valor: empresa_valor},
			success:function(resp){
				if(resp==1){
					alert_toast("La carpeta se movió a su nuevo destino",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
		window.location.href = 'http://localhost/LegalAgenda/index.php?page=task_list';
                // Aquí puedes hacer algo con el valor seleccionado
                console.log('El valor seleccionado de la empresa es:', empresa_valor);
				console.log('El valor seleccionado del id es:', id_valor);
				}
		
        });
    },
    error: function(xhr, status, error) {
        // Manejar errores de la solicitud AJAX
        console.error('Error al obtener las opciones:', error);
    }
});

	})
	// $('.view_progress').click(function(){
	// 	uni_modal("Progeso de: "+$(this).attr('data-task'),"view_progress.php?id="+$(this).attr('data-tid'),'mid-large')
	// })
	$('.delete_task').click(function(){
	_conf("¿Estás seguro de eliminar esta tarea?","delete_task",[$(this).attr('data-id')])
	})
	})
	function delete_task($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_task',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Datos eliminados exitósamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
	function manage_task(obj){
		uni_modal("<i class='fa fa-edit'></i> Editar Tarea","manage_task.php?id="+obj.name,'mid-large');
	}
	function view_task(obj){
		uni_modal("Ver Tarea","view_task.php?id="+obj.name,'mid-large')
	}
	function view_progress(obj){
		uni_modal("Progeso de: "+obj.name,"view_progress.php?id="+obj.id,'mid-large')
	}
	
</script>
