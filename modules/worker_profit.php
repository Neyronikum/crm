<div class="col-xl-12 col-md-12 col-sm-12">
    <div class="box">
        <div class="box-header">
            <h2 class="box-title">Вознаграждение сотрудников</h2>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6 col-xl-6">
                    <form id="workerProfit" action="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <label for="whoIs">Работник</label>
                                    <select name="whoIs" id="whoIs" class="form-control" required>
                                        <option selected disabled>Не выбрано</option>
                                        <?
                                        require_once ("../connect.php");
                                        ini_set('display_errors', 1);
                                        ini_set('display_startup_errors', 1);
                                        error_reporting(E_ALL);
                                        $query = "SELECT * FROM `workers`";
                                        $result = mysqli_query($link, $query);
                                        while ($user = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            print_r($user);
                                            echo "<option value='${user['worker_id']}'>${user['firstname']} ${user['lastname']}</option>";
                                        };
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month">Месяц</label>
                                    <input value="2019-09" id="month" type="month" name="month" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="newClientsPlan">План новых клиентов</label>
                                    <input type="text" id="newClientsPlan" name="newClientsPlan" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="realizationPlan">План продаж</label>
                                    <input type="text" id="realizationPlan" name="realizationPlan" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="salaryBase">Базовый Оклад</label>
                                    <input type="text" id="salaryBase" name="salaryBase" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="newClients">Новые клиенты</label>
                                    <input type="text" id="newClients" name="newClients" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success float-right" id="admit" name="admit">Утвердить план</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="monthRealizations">Сумма отгрузок</label>
                                    <input type="text" id="monthRealizations" name="monthRealizations" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="monthPay">Сумма оплаты</label>
                                    <input type="text" id="monthPay" name="monthPay" value="0" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="demotivation">Демотивация</label>
                                    <input type="text" id="demotivation" name="demotivation" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="rewardSum">Вознаграждение итого:</label>
                                    <input type="text" id="rewardSum" name="rewardSum" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="debit">Дебиторка</label>
                                    <input type="text" id="debit" name="debit" value="0" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="salaryProfit">Зарплата к начислению</label>
                                    <input type="text" id="salaryProfit" name="salaryProfit" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="clientsPlanMotivation">премия за клиентов</label>
                                    <input type="text" id="clientsPlanMotivation" name="clientsPlanMotivation" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="salaryPayable">Зарплата к выдаче</label>
                                    <input type="text" id="salaryPayable" name="salaryPayable" value="0" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="finResult">Фин результат</label>
                                    <input type="text" id="finResult" name="finResult" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="grossMargin">Валовая рентабельность</label>
                                    <input type="text" id="grossMargin" name="grossMargin" value="0" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="returnOfInvestment">Уровень доходности</label>
                                    <input type="text" id="returnOfInvestment" name="returnOfInvestment" value="0" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" >Закрыть месяц</button>
                    </form>
                </div>
                <div class="col-md-6 col-xl-6">
                    <form id="addBonus" name="addBonus">
                        <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                            <div class="col-md-4 col-xl-4">
                                <div class="form-group">
                                    <label for="bonusCount">Размер премии / штрафа</label>
                                    <input type="number" id="bonusCount" name="bonusCount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-8 col-xl-8">
                                <div class="form-group">
                                    <label for="reason">Основание</label>
                                    <textarea id="reason" name="reason" class="form-control" required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                            <div class="col-md-12 col-xl-12">
                                <div class="form-group">
                                    <button type="submit" id="admitBonus" name="admitBonus" class="btn btn-primary">Подтвердить начисление</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                        <div class="col-md-12 col-xl-12">
                            <table style="width: 100%" id="bonusTable"  class="table table-bordered table-hover display nowrap margin-top-10 dataTable table-responsive table-striped">
                                <thead></thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>

                    <form id="payroll" name="payroll">
                        <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                            <div class="col-md-4 col-xl-4">
                                <div class="form-group">
                                    <label for="payrollCount">Размер выплаты</label>
                                    <input type="number" id="payrollCount" name="payrollCount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-8 col-xl-8">
                                <div class="form-group">
                                    <label for="payrollReason">Основание</label>
                                    <textarea id="payrollReason" name="payrollReason" class="form-control" required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                            <div class="col-md-12 col-xl-12">
                                <div class="form-group">
                                    <button type="submit" id="admitPayroll" name="admitPayroll" class="btn btn-primary">Подтвердить оплату</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row" style="border-left: #4e555b 2px solid; padding-left: 15px;">
                        <div class="col-md-12 col-xl-12">
                            <table style="width: 100%" id="payrollTable"  class="table table-bordered table-hover display nowrap margin-top-10 dataTable table-responsive table-striped">
                                <thead></thead>
                                <tbody></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>