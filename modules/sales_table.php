<?
    include_once ("../connect.php");
?>
<div class="col-xl-12 col-md-12 col-sm-12">
    	<div class="box">
		<div class="box-header">
			<h2 class="box-title">Список продаж</h2>

			<div class="box-tools pull-right">
				<!-- <span>Показывать за</span>
				<select id="showYear">
					<option>2019</option>
					<option>2018</option>
				</select>
				<select id="showMonth">
					<option>август</option>
					<option>июль</option>
				</select> -->
				
	            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
	            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          	</div>
		</div>
		<div class="box-body">	
			<div>
				<?if ($_SESSION['status'] >7) echo '
				<input class="filled-in chk-col-light-blue" type="checkbox" id="all_sales" name="all_sales" >
				<label for="all_sales">Продажи всех пользователей</label>'; ?>
				<select class="select" name="workerSelect" id="workerSelect">
<?
$query = "SELECT * FROM `workers` where isManager = 1";
$result = mysqli_query($link, $query);
while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<option value='${user['worker_id']}'>${user['firstname']} ${user['lastname']}</option>";
};
?>
				</select>
			<input class="filled-in chk-col-light-blue" type="checkbox" id="currentMonth" name="currentMonth" checked="true">
				<label for="currentMonth">Показывать только за текущий месяц</label>
					
			</div>		
			<table id="sales_table" class="table table-bordered table-hover display nowrap margin-top-10 table-responsive dataTable table-striped">
				<thead>
				</thead>
				<tbody>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
</div>