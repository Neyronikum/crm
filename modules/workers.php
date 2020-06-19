<div class="col-xl-6 col-lg-12 col-md-12 col-xs-12">

    <div class="box box-default" id="new_worker">
        <div class="box-header with-border">
            <h3 class="box-title">Сотрудники</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <div class="box-body">
            <form id="sale" role="form" method="POST">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group margin">
                            </select>
                            <span class="">
                                <button class="btn btn-info btn-flat btn-sm" data-toggle="modal" data-target="#addContragentModal">Добавить</button>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Реализация" type="text" name="realization" id="realization" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Закуп" type="text" name="purchase" id="purchase" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Оплата" type="text" name="pay" id="pay" class="form-control">
                                </div>
                                <div class="form-group">
                                    <input placeholder="Отсрочка" type="text" name="payment_delay" id="payment_delay" class="form-control">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <input name="NDS" type="radio" id="NDS" checked="" value="1">
                                        <label for="NDS">НДС 20%</label>
                                    </div>
                                    <div class="radio">
                                        <input name="NDS" type="radio" id="noNDS" value="2">
                                        <label for="noNDS">Без НДС</label>
                                    </div>
                                    <div class="radio">
                                        <input name="NDS" type="radio" id="cash" value="3">
                                        <label for="cash">Наличные</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Вес груза" type="text" name="weight" id="weight" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Комиссия" type="text" name="commission" id="commission" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="radio">
                                        <input name="logisticInsideSelect" type="radio" id="firstChose" data-value="1" value="1" checked="">
                                        <label for="firstChose">Доставка</label>
                                    </div>
                                    <div class="radio">
                                        <input name="logisticInsideSelect" type="radio" id="secondChose" data-value="2" value="2">
                                        <label for="secondChose">Самовывоз</label>
                                    </div>
                                    <div class="radio">
                                        <input name="logisticInsideSelect" type="radio" id="thirdChose" data-value="3" value="3">
                                        <label for="thirdChose">Особый расчёт</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sale_date">Дата отгрузки:</label>
                                    <input class="form-control" id="sale_date" type="date" name="sale_date"
                                        <? echo ( "value='" . date('Y-m-d') . "'"); ?> required>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="Особый расчёт" name="specialCalc" id="specialCalc" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input placeholder="Расстояние" type="number" name="distance" id="distance" class="form-control" required>
                                </div>
                            </div>
                            <!--                      <div class="col-md-6">-->
                            <!--                          <div class="form-group">-->
                            <!--                              <select name="car" id="car" class="form-control" required>-->
                            <!--                                  <option value="" disabled selected>Выберите машину</option>-->
                            <!---->
                            <!--                              </select>-->
                            <!--                          </div>-->
                            <!--                      </div>--><!---->
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea required class="form-control" style="width: 100%" name="comment" id="comment" rows="2" placeholder="Пояснение"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary" >Добавить</button>
                    </div>
                </div>





                <input type="hidden" name="fuel_consumption">
                <input type="hidden" name="profit">
                <input type="hidden" name="NDSOut">
                <input type="hidden" name="NDSIn">
                <input type="hidden" name="NDSToPay">
                <input type="hidden" name="logisticOutside">
                <input type="hidden" name="logisticInside">
                <input type="hidden" name="taxBase">
                <input type="hidden" name="taxProfit">
                <input type="hidden" name="cleanProfit">
                <input type="hidden" name="reward">
                <input type="hidden" name="profitability">

            </form>
            <div class="modal" id="sale_add" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Поздравляю</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Продажа добавлена успешно</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>