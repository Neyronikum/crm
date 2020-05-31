<div class="col-xl-6 col-md-12 col-sm-12">
    	<div class="box">
		<div class="box-header">
			<h2 class="box-title">Список задач</h2>
			<div class="box-tools pull-right">
	            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          	</div>
		</div>
		<div class="box-body">			
			<table id="tasks_table" class="table table-bordered table-hover display nowrap margin-top-10 table-responsive dataTable">
				<thead>
					<tr>
						<th>№</th>
						<th>Название</th>
						<th>Исполнитель</th>
						<th>Активна</th>
						<th>Принята</th>
						<th>Дата</th>
					</tr>
				</thead>
				<tbody>
					
				
	<?
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		require_once('../connect.php');
		$worker_id = $_SESSION['id'];
		$status = $_SESSION['status'];
	} else {
		$worker_id = $_SESSION['id'];
		$status = $_SESSION['status'];
	}
	 
	if ($status == 1){
		$query = "SELECT * FROM `tasks` LEFT JOIN `workers` on tasks.responsible = workers.worker_id WHERE `workers`.worker_id = ${worker_id}  ORDER BY task_id DESC";
	} else {
		$query = "SELECT * FROM `tasks` LEFT JOIN `workers` on tasks.responsible = workers.worker_id ORDER BY task_id DESC";
	}
	
	$result = mysqli_query($link, $query);		
	while ($task = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$date = date_create($task['date_end']);
		$date_formated = date_format($date, 'd.m.y');
		$active = $task['firstname']?"Да":"Нет";
		$accepted = $task['accepted']?"Да":"<span class='warning'>Нет</span>";
		echo "<tr>
			<td>${task['task_id']}</td>
			<td>${task['task_name']}</td>
			<td>${task['firstname']} ${task['lastname']}</td>
			<td>$active</td>
			<td>$accepted</td>
			<td>$date_formated</td>
			</tr>";
	};	
	?>
		
					</tbody>
				</table>
			</div>
		</div>
      </div>