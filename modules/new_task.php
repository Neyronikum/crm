<div class="col-xl-4 col-lg-6 col-md-6 col-xs-12">
     		
     		<div class="box box-default" id="new_task">
		        <div class="box-header with-border">
		          	<h3 class="box-title">Создать новую задачу</h3>

		          	<div class="box-tools pull-right">
			            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
			            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
		          	</div>
		        </div>
		        <div class="box-body">
		          <form id="task" role="form">
		            <div class="form-group">
		              <label for="worker">Выбрать исполнителя:</label>
		              
		                <select id="worker" class="form-control" name="worker" required>
		                  <option selected disabled>Не выбрано</option>
<?php
  $query = "SELECT * FROM `workers`";
  $result = mysqli_query($link, $query);    
  while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<option value='${user['worker_id']}'>${user['firstname']} ${user['lastname']}</option>";
  };    
?>        
		                </select>
		            </div>
		            <div class="form-group">
		              <label for="task">Текст задачи:</label>
		              <textarea rows="5" class="form-control" id="task" name="text" required></textarea>
		            </div>    
		            <div class="form-group">
		              <label for="date">Дата:</label>
		              <input class="form-control" id="date" type="date" name="date" 
		              <? echo ( "value='" . date('Y-m-d') . "'"); ?> required>
		            </div>  
		              <div class="form-group">
		              <label for="priority">Приоритет:</label>
		              <select class="form-control" id="priority" name="priority" required> 
		<?php
		  $query = "SELECT * FROM `priority`";
		  $result = mysqli_query($link, $query);    
		  while ($priority = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		    echo "<option value='${priority['priority_id']}'>${priority['priority_name']}</option>";
		  };    
		?>
		                </select>
		            </div> 
	            <button type="submit" class="btn btn-primary">Создать</button>
	          </form>
	        </div>
     	</div>		
     </div>