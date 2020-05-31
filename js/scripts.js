function init(){
	class AddProducts {
		constructor(elem) {
			//this._elem = elem;
			elem.onchange = this.onChange.bind(this); // (*)
		}

		cost(parentElem){
			let price = parentElem.querySelector('[data-product="price"]').value;
			let amount = parentElem.querySelector('[data-product="amount"]').value;
			parentElem.querySelector('[data-product="cost"]').value = price * amount;

			//parentElem.parentElement.querySelector('tfoot tr').nodeValue('asdfas');
		}

		price(target) {
			this.cost(target.parentElement.parentElement)
		}

		amount(target) {
			this.cost(target.parentElement.parentElement)
		}

		onChange(event) {
			let action = event.target.dataset.product;
			if (action) {
				this[action](event.target);
			}

		};
	}

	let target = document.getElementById('add_products_table');

// Конфигурация observer (за какими изменениями наблюдать)
	const config = {
		attributes: true,
		childList: true,
		subtree: true
	};

// Функция обратного вызова при срабатывании мутации
	const obsFunc = function(mutationsList, observer) {
		for (let mutation of mutationsList) {
			if (mutation.type === 'childList') {
				console.log('A child node has been added or removed.');
			}
		}
		alert('qwe');
	};

// Создаем экземпляр наблюдателя с указанной функцией обратного вызова
	const observer = new MutationObserver(obsFunc);





	if (typeof add_products_table != "undefined") new AddProducts(add_products_table);

	const addProductInit = {
		"initComplete": function () {

		}
	};
	/*Русификатор для Datatable */
	const dataTableRus = {
		language: {
			search: "Поиск по таблице",
			info: "Отбражается с _START_ по _END_ из _TOTAL_ ",
			infoEmpty: "Совпадений не найдено",
			infoFiltered: "(из _MAX_)",
			zeroRecords: "Нет результатов",
			paginate: {
				first: "Первая",
				previous: "Предыдущая",
				next: "Следующая",
				last: "Последняя"
			},
			lengthMenu: "Записей на странице _MENU_"
		}
	};

	let addProductTable = $('#add_products_table').DataTable(Object.assign(addProductInit, dataTableRus));

	$('#products').change(function(){
		let price = `<input class="table-input price" data-product="price" type="number" value="${this.options[this.selectedIndex].dataset.price}">`;
		let cost = `<input class="table-input cost"  data-product="cost" type="number" value="${this.options[this.selectedIndex].dataset.price}" disabled>`;
		let count = "<input class='table-input count' data-product='amount' type=number value='1' >";
		if ( $.fn.DataTable.isDataTable( '#add_products_table' ))
			addProductTable.row.add([
				'1',
				this.options[this.selectedIndex].text,
				price,
				count,
				cost,
				'X'
			]).draw(false);
		else console.warn('It is not a Datatable.');

	});


	if ($('#products').length > 0)
	(function getProducts(){
		$.ajax({
			asinc: true,
			url: 'api.php',
			type: 'POST',
			dataType: 'json',
			data: {get_products: true},
		}).done(function(data){
			let select = [];
			$.each (data, function(key, value){
				select.push(`<option data-price="${value.price_sale}" value=${value.product_id}>${value.name}</option>`)
			});
			$('#products').html(select);
			$('#products').selectpicker('refresh');
			// Начинаем наблюдение за настроенными изменениями целевого элемента
			observer.observe(target, config);
		});

	})();

	$('#payroll').submit(function(e){
		e.preventDefault();
		let payroll = {};
		payroll.add_new_payroll = true;
		payroll.payroll_count = $('#payrollCount').val();
		payroll.reason = $('#payrollReason').val();
		payroll.month = $('#month').val();
		payroll.worker_id = $('#whoIs').val();
		$.ajax({
			url: 'api.php',
			data: payroll,
			type: 'POST',
			success: (data) => {
				getWorkerProfit();
			}
		});
	});

	$('#workerProfit').submit(function(e){
		e.preventDefault();
	});

	$('#month').on('change', function () {
		getWorkerProfit();
	});

	$('#addBonus').submit(function(e){
		e.preventDefault();
		let bonus = {};
		bonus.add_new_bonus = true;
		bonus.bonus_count = $('#bonusCount').val();
		bonus.reason = $('#reason').val();
		bonus.month = $('#month').val();
		bonus.worker_id = $('#whoIs').val();
		$.ajax({
			url: 'api.php',
			data: bonus,
			type: 'POST',
			success: (data) => {
				getWorkerProfit();
			}
		});
	});

	$('#whoIs').on('change', function(){
		getWorkerProfit();
	});

	/** Подтверждение месячных нормативов для сотрудника  */

	$('#admit').click(function (e) {
		e.preventDefault();
		let collection = {};
		collection.set_month_standard = true;
		collection.realization_plan = $('#realizationPlan').val();
		collection.new_clients_plan = $('#newClientsPlan').val();
		collection.new_clients = $('#newClients').val();
		collection.salary_base = $('#salaryBase').val();
		collection.worker_id = $('#whoIs').val();
		collection.month = $('#month').val();
		//console.log(collection);
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: collection
		});
	});
	/** Расчёт зарплаты из месячных нормативов и продаж за расчётный период   */
	function getWorkerProfit(){
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {get_worker_profit: true, worker_id: $('#whoIs').val(), month: $('#month').val()},
			dataType: 'json',
			success: (data) => {
				$('#monthRealizations').val(data.realization_sum);
				$('#monthPay').val(data.pay_sum);
				$('#debit').val(data.debit);
				$('#demotivation').val(data.demotivation);
				$('#newClientsPlan').val(data.new_clients_plan);
				$('#newClients').val(data.new_clients);
				$('#realizationPlan').val(data.realization_plan);
				$('#salaryBase').val(data.salary_base);
				$('#salaryProfit').val(data.salary_profit);
				$('#salaryPayable').val(data.salary_payable);
				$('#rewardSum').val(data.reward);
				$('#finResult').val(data.fin_result);
				$('#grossMargin').val(data.gross_margin * 100 + '%');
				$('#returnOfInvestment').val(data.return_of_investment * 100 + '%');
				$('#clientsPlanMotivation').val(data.clients_plan_motivation);
			}
		});
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {get_worker_bonus: true, worker_id: $('#whoIs').val(), month: $('#month').val()},
			dataType: 'json',
			success: (data) => {
				let header;
				let tbody = [];
				let footer;
				let count = 1;
				let sum = 0;
				$.each(data, function (key, value) {
					tbody.push(`
						<tr>
							<td>${count}</td>
							<td>${value.bonus_description}</td>
							<td>${value.bonus_count.toFixed(2)}</td>
							<td>${value.bonus_date}</td>
						</tr>
					`);
					count++;
					sum += value.bonus_count;
				});
				header = `<tr>
							<th>№</th>
							<th>Основание</th>
							<th>Сумма</th>
							<th>Дата</th>
						</tr>`;
				footer = `<tr>
							<th colspan="2">Итого за масяц:</th>							
							<th colspan="2">${sum.toFixed(2)}</th>
						</tr>`;
				if ($.fn.DataTable.isDataTable( '#bonusTable' )) {
					$('#bonusTable').DataTable().destroy();
					$('#bonusTable tbody').html(tbody);
					$('#bonusTable tfoot').html(footer);
					$('#bonusTable').DataTable(dataTableRus);
				} else {
					$('#bonusTable thead').html(header);
					$('#bonusTable tbody').html(tbody);
					$('#bonusTable tfoot').html(footer);
					$('#bonusTable').DataTable(dataTableRus);
				}
			}
		});
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {get_worker_payroll: true, worker_id: $('#whoIs').val(), month: $('#month').val()},
			dataType: 'json',
			success: (data) => {
				let header;
				let tbody = [];
				let footer;
				let count = 1;
				let sum = 0;
				$.each(data, function (key, value) {
					tbody.push(`
						<tr>
							<td>${count}</td>
							<td>${value.description}</td>
							<td>${value.amount.toFixed(2)}</td>
							<td>${value.payroll_date}</td>
						</tr>
					`);
					count++;
					sum += value.amount;
				});
				header = `<tr>
							<th>№</th>
							<th>Основание</th>
							<th>Сумма</th>
							<th>Дата</th>
						</tr>`;
				footer = `<tr>
							<th colspan="2">Итого за масяц:</th>							
							<th colspan="2">${sum.toFixed(2)}</th>
						</tr>`;
				if ($.fn.DataTable.isDataTable( '#payrollTable' ))
					$('#payrollTable').DataTable().destroy();
				$('#payrollTable thead').html(header);
				$('#payrollTable tbody').html(tbody);
				$('#payrollTable tfoot').html(footer);
				$('#payrollTable').DataTable(dataTableRus);
			}
		})
	}
	/** переключение режима просмотра продаж всех сотрудников  */
	let all_sales = false;
	$('#all_sales').click(function() {
		all_sales =!!$(this).prop('checked');
		if (all_sales)
			$('#workerSelect').attr('disabled', 'disabled');
		else
			$('#workerSelect').removeAttr('disabled');
		getSales(currentMonth, all_sales);
	});
	let worker = 0;
	$('#workerSelect').change(function() {
		worker = $(this).val();
		getSales(currentMonth, all_sales);
	});
	/** Вывод таблицы всех контрагентов  */
	function getContragentsTable(){
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {get_contragents_table: true},
			dataType: 'json',
			success: (data) => {
				let header;
				let tbody = [];
				let footer;
				let count = 0;
				$.each(data, function(key, value){
					let number = ++key;
					count += 1;
					tbody.push(`
						<tr>
							<td>${number}</td>
							<td>${value.name}</td>
							<td>${value.firstname} ${value.lastname}</td>
							<td>${value.condition_name}</td>
						</tr>
					`)
				});
				footer = `<tr>
							<th colspan=2>Всего товарищей: ${count}</th>
							<th></th>
							<th></th>
							
						</tr>`;
				header = `<tr>
							<th>№</th>
							<th>Контрагент</th>
							<th>Куратор</th>
							<th>Статус</th>							
						</tr>`;
				if ($.fn.DataTable.isDataTable( '#contragents_table' ))
					$('#contragents_table').DataTable().destroy();
				$('#contragents_table thead').html(header);
				$('#contragents_table tbody').html(tbody);
				$('#contragents_table tfoot').html(footer);
				$('#contragents_table').DataTable(dataTableRus);
			}
		})
	}
	/** инициализация таблицы контрагентов */
	if ($('#contragents_table').length > 0)
	getContragentsTable();
	/** Самовызывающаяся функция для составления выподающего списка состояний клиента  */
	if ($('#contragent_status').length > 0)
	(function get_conditions(){
		let conditions = [];
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: {get_conditions: true},
			dataType: 'json',
			success: (data) => {
				$.each(data, function(key, condition){
					if (key == 2) {
						conditions.push(`<option selected value="${condition.condition_id}">${condition.condition_name}</option>`);
					} else {
						conditions.push(`<option value="${condition.condition_id}">${condition.condition_name}</option>`);
					}

				});
				$('#contragent_status').html(conditions);
			}
		})
	})();

	/** Радио самовывоза для забивания новой продажи  */
	$("input[name=logisticInsideSelect]:radio").change(function() {
		let logisticInsideSelect = $("input[name='logisticInsideSelect']:checked").data('value');
		switch (logisticInsideSelect) {
			case 1: {
				$('#car').removeAttr('disabled');
				$('#distance').removeAttr('disabled');
				$('#specialCalc').attr('disabled','disabled');
				break
			}
			case 2: {
				$('#car').attr('disabled','disabled');
				$('#distance').attr('disabled','disabled');
				$('#specialCalc').attr('disabled','disabled');
				break
			}
			case 3: {
				$('#car').attr('disabled','disabled');
				$('#distance').attr('disabled','disabled');
				$('#specialCalc').removeAttr('disabled');
				break
			}
		}


	});
	/** Добавление новой записи в ежедневника  */
	$('#blueprint').submit(function(event){
		event.preventDefault();
		let dataset = $(this).serialize();
		$.ajax({
			url: 'api.php',
			data: dataset + '&add_conversation=true',
			type: 'POST',
			dataType: 'json',
			success: (data) => {
				console.log(data);
				$('#blueprint')[0].reset();
				$.toast({
					text: "Задача для себя добавлена.",
					heading: 'Так держать!',
					position: 'top-right',
					icon: 'success'
				});
			}
		});
	});
	/** Галочка для переключения видов в таблице продаж за всё время или за текущий месяц  */
	let currentMonth = true;
	$('#currentMonth').click(function() {
		currentMonth =!!$(this).prop('checked');
		getSales(currentMonth, all_sales);
	});

	/** Функция составление выподающего списка контрагентов и инициализация поиска по списку  */
	function getContragents(){
		$.ajax({
			asinc: true,
			url: 'api.php',
			type: 'POST',
			dataType: 'json',
			data: {get_contragents: true},
		}).done(function(data){
			let select = [];
			let owner;
			$.each (data, function(key, value){
				owner = value.firstname == null?'':`(${value.firstname})`;
				if (key !== 0) select.push(`<option value=${value.contragent_id}>${value.name} ${owner}</option>`)
				//else select.push('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addContragentModal">Добавить контрагента</button>')

			});
			$('#contragent').html(select);
			$('#contragent').selectpicker('refresh');
			$('#contragent_to_convers').html(select);
			$('#contragent_to_convers').selectpicker('refresh');


		});

	}
	$('input[data=toggle]').click(function(e){
		e.preventDefault();
	});
	/** Функция закраски прогрессбара платежа по сделке  */
	function percentPay(whole, part){
		let percent = part / whole *100;
		let color;
		if (percent.toFixed(0) == 100)
			color = 'progress-bar-success';
		else color = `progress-bar-red`;
		return `<div class="progress progress-xs progress-striped active">
		          <div class="progress-bar ${color}" style="width: ${percent}%"></div>
		        </div>
		        <span class="forHover" style="display: none;">${part}</span>`;
	}
	/** Функция подкраски процентов в зависимости от их количества  */
	function colorPercent(numero){
		numero= numero.toFixed(2);
		if (isNaN(numero)) return '';
		if (numero < 15)
			return `<span class="badge bg-red">${numero}%</span>`;
		else if (15 <= numero && numero < 25)
			return `<span class="badge bg-yellow">${numero}%</span>`;
		else
			return `<span class="badge bg-green">${numero}%</span>`;
	}
	/** Функция вывода даты в формат dd.mm.yyyy  */
	function formatDate(input){
		let date = new Date(input);
		let dd = date.getDate(input);
		if (dd < 10) dd = '0' + dd;

		let mm = date.getMonth() + 1;
		if (mm < 10) mm = '0' + mm;

		let yy = date.getFullYear() % 100;
		if (yy < 10) yy = '0' + yy;

		return dd + '.' + mm + '.' + yy;
	}
	/** Вывод типа контрагента в зависимости от от численного типа  */
	function contragentType(type){
		switch (type) {
			case 1: return 'НДС'; break;
			case 2: return 'Без НДС'; break;
			case 3: return 'частник'; break;
		}
	}
	/** Функция забивания в таблицу всех продаж  */
	function getSales(currentMonth = true, all_sales = false){
		$.ajax({
			type: 'POST',
			data: {sales_table : true, current_month: currentMonth?1:0, all_sales: all_sales?1:0, worker_id: worker},
			dataType: 'json',
			url: 'api.php',
			success: function(data){
				let header;
				let tbody = [];
				let footer;
				let count = 0;
				let realization_sum = 0;
				let purchase_sum = 0;
				let pay_sum = 0;
				let logisticOutside_sum = 0;
				let reward_sum = 0;
				let profitability_final = 0;
				let commission_sum = 0;
				let NDSToPay_sum = 0;
				let logisticInside_sum = 0;
				//console.log(data);
				$.each(data, function(key, value){
					let number = ++key;
					const profit = value.profitability * 100;
					const percent = colorPercent(profit);
					tbody.push(`<tr class='specialHover' data-sale_id=${value.sale_id}>
									<td>${number}</td>
									${all_sales?'<td>'+value.firstname+' '+value.lastname+'</td>':''}
									<td>${value.name}</td>
									<td>${value.realization}</td>
									<td>${value.purchase}</td>
									<td>${percentPay(value.realization, value.pay)}</td>
									<td>${value.logisticOutside}</td>
									<td>${value.logisticInside}</td>
									<td>${value.reward}</td>
									<td>${percent}</td>
									<td>${value.commission !== undefined? value.commission: '0'}</td>
									<td>${value.NDSToPay}</td>
									<td>${contragentType(value.NDS)}</td>
									<td>${formatDate(value.sale_date) }</td>
									<td><div class="btn-group">
                      <button type="button" class="btn btn-primary sale-edit"><i class="fa fa-pencil"></i></button>
                      
                      <button type="button" class="btn btn-danger  sale-delete"><i class="fa fa-close"></i></button>
                    </div></td>
									</tr>`);
					count += 1;
					realization_sum += value.realization;
					purchase_sum += value.purchase;
					pay_sum += value.pay;
					logisticOutside_sum += value.logisticOutside;
					logisticInside_sum += value.logisticInside;
					reward_sum += value.reward;
					profitability_final += value.profitability;
					NDSToPay_sum += value.NDSToPay;
					commission_sum += value.comission === undefined? 0 : value.comission;

				});
				profitability_final = profitability_final / count;
				footer = `<tr>
							<th colspan=2>Всего сделок: ${count}</th>
							${all_sales?'<th></th>':''}
							<th>${realization_sum.toFixed(2)}</th>
							<th>${purchase_sum.toFixed(2)}</th>
							<th>${pay_sum.toFixed(2)}</th>
							<th>${logisticOutside_sum.toFixed(2)}</th>
							<th>${logisticInside_sum.toFixed(2)}</th>
							<th>${reward_sum.toFixed(2)}</th>
							<th>${colorPercent(profitability_final * 100)}</th>
							<th>${commission_sum.toFixed(2)}</th>
							<th>${NDSToPay_sum.toFixed(2)}</th>
							<th></th>
							<th></th>
							<th></th>
						</tr>`;
				header = `<tr>
							<th>№</th>
							${all_sales?'<th>Куратор</th>':''}
							<th>Контрагент</th>
							<th>Реализация</th>
							<th>Закуп</th>
							<th>Оплата</th>
							<th>Внешняя логистика</th>
							<th>Внутренняя логистика</th>
							<th>Вознаграждение</th>
							<th>Рентабельность</th>
							<th>Комиссия</th>
							<th>НДС к оплате</th>
							<th>Тип</th>
							<th>Дата сделки</th>
							<th></th>
						</tr>`;
				if ( $.fn.DataTable.isDataTable( '#sales_table' ))
					$('#sales_table').DataTable().destroy();
				$('#sales_table tbody').html(tbody);
				$('#sales_table tfoot').html(footer);
				$('#sales_table thead').html(header);
				$('#sales_table').DataTable(Object.assign(initOptions, dataTableRus));
			}
		});
	};
	/** Инициализауия таблицы продаж при входе  */
	if ($('#sales_table').length > 0)
	getSales();
	/** Инициализация выпадающих списков контрагентов  */
	if ($('#contragent_to_convers').length > 0 || $('#contragent').length > 0)
	getContragents();

	/** Параметры инициализации для Datatable  */
	const initOptions = {
		scrollCollapse: true,
		"scrollY": '60vh',
		"scrollX": true,
		"initComplete": function () {
			let api = this.api();
			api.$('.sale-edit').click( function () {
				const id = $(this).closest('tr').data('sale_id');
				swal("Введите новую оплату:", {
					content: "input",
				})
					.then((value) => {
						if (value > 0){
							$.toast({
								text: "Оплата изменена.",
								heading: 'Пацаны одобряют!',
								position: 'top-right',
								icon: 'success'
							});
							$.ajax({
								url: 'api.php',
								type: 'POST',
								data: {sale_edit: true, sale_id: id, new_pay: value},
								success: function(data){
									getSales(currentMonth, all_sales);
								}
							});
						}
					});
			});
			api.$('.sale-delete').click(function(){
				const id = $(this).closest('tr').data('sale_id');
				swal({
					title: 'Продажа будет удалена!',
					text: "Точно?",
					icon: 'warning',
					buttons: {cancel: "Отмена", ok: 'Удалить'},
					dangerMode: true
				}).then((result) => {
					if (result) {
						$.toast({
							text: "Продажа удалена.",
							heading: 'Не многие так могут',
							position: 'top-right',
							icon: 'success'
						});
						$.ajax({
							type: 'POST',
							url: 'api.php',
							data: {sale_delete: true, sale_id: id},
							success: function() {
								getSales(currentMonth, all_sales);
							}
						})
					}
				})
			});
			api.$('.specialHover').hover(function() {
				$(this).find('.progress').css("display", "none");
				$(this).find('.forHover').css("display", "");
			}, function() {
				$(this).find('.progress').css("display", "");
				$(this).find('.forHover').css("display", "none");
			});
		}
	} ;

	/*инициализация обёртки таблиц */

	if ($('#tasks_table').length > 0)
	$('#tasks_table').DataTable(dataTableRus);

	/*  Добавление продажи */

	$('#sale').on('submit', function(event){
		event.preventDefault();
		let contragent = this.contragent.value;
		let realization = this.realization.value;
		let purchase = this.purchase.value;
		let NDS = this.NDS.value;
		let pay = this.pay.value;
		let weight = this.weight.value;
		let commission = this.commission.value;
		let profit;
		let NDSOut;
		let NDSIn;
		let NDSToPay;
		let logisticOutside;
		let logisticInside;
		let taxBase;
		let taxProfit;
		let cleanProfit;
		let reward;
		let profitability;
		//let pickup = this.pickup.checked;
		let distance = this.distance.value;
		let logisticInsideSelect = $("input[name='logisticInsideSelect']:checked").data('value'); // Выбор варианта доставки
		//let car = this.car.value;
		let consumption = this.car.options[this.car.selectedIndex].dataset.consumption;
		profit = realization - purchase;
		if (NDS == 1) NDSOut = realization - (realization / 1.2)
		else NDSOut = 0;
		NDSIn = purchase - (purchase / 1.2);
		if (NDSOut > NDSIn) NDSToPay = NDSOut - NDSIn
		else NDSToPay = 0;
		logisticOutside = weight * 6;
		//logisticInside = realization / 100;   //Упрощённая система расчёта внешней логистики
		switch (logisticInsideSelect) {
			case 1: logisticInside = consumption/100 * distance * 2 * 45.5; break;
			case 2: logisticInside = 0; break;
			case 3: logisticInside = $('#specialCalc').val(); break;
		}
		// if (pickup){
		// 	logisticInside = 0
		// } else {
		// 	logisticInside = consumption/100 * distance * 2 * 45.5
		// }
		taxBase = realization - purchase - NDSToPay - logisticOutside - commission - logisticInside;
		if (NDS == 1) taxProfit = taxBase * 0.11
		else taxProfit = taxBase * 0.06;
		if (NDS == 3) cleanProfit = realization - purchase - NDSToPay - logisticInside - logisticOutside - commission
		else cleanProfit = realization - purchase - NDSToPay - logisticOutside - logisticInside - commission - taxProfit;
		if (NDS == 3) reward = cleanProfit * 0.5
		else reward = cleanProfit * 0.3;
		profitability = cleanProfit / realization;
		this.profit.value = profit;
		this.NDSOut.value = NDSOut;
		this.NDSIn.value = NDSIn;
		this.NDSToPay.value = NDSToPay;
		this.logisticOutside.value = logisticOutside;
		this.logisticInside.value = logisticInside;
		this.taxBase.value = taxBase;
		this.taxProfit.value = taxProfit;
		this.cleanProfit.value = cleanProfit;
		this.reward.value = reward;
		this.profitability.value = profitability;
		this.fuel_consumption.value = consumption;
		let dataset = $(this).serialize();
		$.ajax({
			type: 'POST',
			url: 'api.php',
			data: dataset + '&add_sale=true',
			success: function(data){
				getSales(currentMonth, all_sales);
				$.toast({
					text: "Продажа добавлена.",
					heading: 'Ну, норм!',
					position: 'top-right',
					icon: 'success'
				});
				$('#sale')[0].reset();
				$('#contragent').selectpicker('refresh');
			}
		});
	});

	/*  Добавление продажи *

	/*Создание новой задачи*/
	$('#task').on('submit', function(event){
		event.preventDefault();
		let dataset = $(this).serialize();

		$.ajax({
			type: 'POST',
			url: "bot.php",
			data: dataset,
			success: (function(data){
				$('#task')[0].reset();
			})
		}).done(function(){
			// var worker_id =
			$.ajax({
				type: 'POST',
				url: 'modules/tasks_tables.php',
				success: (function(data){
					$('#tasks_table').html($(data).find('#tasks_table').html());

				})
			})
		});


	});
	/*Добавление нового контрагента*/
	$('#new_contragents').submit(function(event) {
		event.preventDefault();
		let dataset = $(this).serialize();
		$.ajax({
			url: 'api.php',
			type: 'POST',
			data: dataset + "&add_contragent=true",
			dataType: 'json',
			success: (function(data){
				$.toast({
					text: "Контрагент добавлен.",
					heading: 'Чётко!',
					position: 'top-right',
					icon: 'success'
				});
				getContragents();
				getContragentsTable(true);
				$('#new_contragents')[0].reset();
				$('#addContragentModal').modal('hide');

			})
		});

	});




	// Подгоняет левый футер под размер правого
	$('.sidebar-footer').height($('.main-footer').outerHeight());

	/* Для получения данных о компинии*/

	var token = "54fd2f790fa7430a86a27494a726f39ad608d666";

	function join(arr /*, separator */) {
		var separator = arguments.length > 1 ? arguments[1] : ", ";
		return arr.filter(function(n){return n}).join(separator);
	}

	function typeDescription(type) {
		var TYPES = {
			'INDIVIDUAL': 'Индивидуальный предприниматель',
			'LEGAL': 'Организация'
		}
		return TYPES[type];
	}

	function showSuggestion(suggestion) {
		var data = suggestion.data;
		if (!data)
			return;

		$("#type").text(
			typeDescription(data.type) + " (" + data.type + ")"
		);

		if (data.name) {
			$("#name_short").val(data.name.short_with_opf || "");
			$("#name_full").val(data.name.full_with_opf || "");
		}

		$("#inn_kpp").val(join([data.inn, data.kpp], " / "));

		if (data.address) {
			var address = "";
			if (data.address.data.qc == "0") {
				address = join([data.address.data.postal_code, data.address.value]);
			} else {
				address = data.address.data.source;
			}
			$("#address").val(address);
		}
	}

	$("#party").suggestions({
		token: token,
		type: "PARTY",
		count: 5,
		/* Вызывается, когда пользователь выбирает одну из подсказок */
		onSelect: showSuggestion
	});
	/* Для получения данных о компании ----- конец*/
	if ($('#car').length > 0)
	(function getCars(){
		$.ajax({
			url: 'api.php',
			data: {get_cars: true},
			type: 'POST',
			dataType: 'json',
			success: function(data) {
				let cars = [];
				$.each(data, function(key, car){
				cars.push(`<option value='${car['transport_id']}' data-consumption='${car['fuel_consumption']}'><b>${car['transport_name']}</b> (расход - ${car['fuel_consumption']})</option>`);
				})
				$('#car').append(cars);
			}
		})
	})();

	if ($('#blueprints_table').length > 0)
	(function getBlueprintTable(){
		$.ajax({
			url: 'api.php',
			data: {get_blueprints_table: true},
			type: 'POST',
			dataType: 'json',
			success: function(data){
				let table = [];
				$.each(data, function(key, blueprint){
					table.push(`
					<tr>
						<td>${blueprint.date}</td>
						<td>${blueprint.name}</td>
						<td>${blueprint.reason}</td>
						<td>${blueprint.conversation_date}</td>
						<td>${blueprint.description}</td>
						<td>${blueprint.telephones}</td>
						<td>${blueprint.lastname}</td>
						
						<td>${blueprint.is_closed == 1 ? 'закрыто': 'не закрыто'}</td>
					</tr>	
					`)
				});
				//console.log(table);
				$('#blueprints_table tbody').html(table);
				$('table#blueprints_table > tbody > tr').on('click', function(){
					$('table#blueprints_table').css('background-color', '');
					$(this).css('background-color', 'lightgreen');
					$(this).siblings('tr').css('background-color','white');
				})

					.on('dblclick', function(){
					$('#blueprint ');
					$('#editBlueprintModal').modal('show');
				})
			}
		})
	})();


	/*запрет попытки отправки формы для кнопок, вызывающих модальные окна*/
	$('[data-toggle]').click((e) => e.preventDefault());
}

$(document).ready(function(){
	init();
	$('[data-module]').click(function(){
		let url = this.dataset.module;
		let section = $('section.content div');
		$.ajax({
			url: url,
			type: 'POST',
			success: function(data){
				section.html(data);
				init();
			}
		})
	});


});