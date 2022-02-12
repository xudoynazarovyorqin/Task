@extends('layouts.app')

@section('content')

<div class="top-mini-menu p15">
    <a href="products.html" class="actioneTopM">Продукты</a>
    <a href="measurement.html">Ед. изм.</a>
    <a href="costs.html">Типы расходов</a>
    <a href="paymentTypes.html">Тип оплаты</a>
</div>

<div class="main_c_centeer p15">
    <div class="blog-title">
        <ul class="ul-menu">
            <li>
                <a href="../../index.html"><i class=" fas fa-home"></i></a>
                <span class="d-bi  ">/</span>
            </li>
            <li>
                <a>Продукты</a>
            </li>
        </ul>
        <div>

            <button type="button" class="btn my-btn" data-toggle="modal" data-target="#exampleModalCenter">
                <i class="fas fa-plus mr-1"></i> Создать
            </button>
        </div>
    </div>
    <!-- //////////// blog-title ///////////// -->

    <div class="main_c_centeer_big">
        <div class="table-my">

            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Наименование</th>
                        <th scope="col">Код</th>
                        <th scope="col">Ед. изм.</th>
                        <th scope="col">Дата создания</th>
                        <th scope="col">
                            <i class="fas fa-sliders-h "></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">1</td>
                        <td>kahs</td>
                        <td>11</td>
                        <td>шт</td>
                        <td>12.12.2020</td>
                        <td>
                            <div class="dropdown dropdown-setting">
                                <button class="" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">
                                        <i class="far fa-edit"></i>
                                        Изменить
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-trash-alt"></i>
                                        Удалить
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>


                </tbody>
            </table>

            <div class="pagination_my">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- //////////// main_c_centeer_big ///////////// -->
</div>

</div>

<!-- ///////////////////////////////////////////// end main_container center ////////////////////////////////// -->


<!-- start modal -->
<!-- Modal -->
<div class="my-modal wm-60 modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle"> Продукты </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <div class="row">
                    <div class="col-12 col-sm-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                            <label for="ismi">Наименование</label>
                            <input type="text" class="form-control" id="ismi" placeholder="Наименование">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="myId2">Код</label>
                            <input type="text" class="form-control" id="myId2" placeholder="Код">
                        </div>
                    </div>
                    <!-- <div class="col-12 col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="myId3">Telfon</label>
                            <input type="text" class="form-control" id="myId3" placeholder="Telfon">
                        </div>
                    </div> -->
                    <div class="col-12 col-sm-6 col-lg-6 col-xl-6">
                        <div class="form-group">
                            <label for="myId4">Ед. изм.</label>
                            <select class="form-control" id="myId4">
                                <option></option>
                                <option>Andijon asaka</option>
                            </select>
                        </div>
                    </div>
                </div>


            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn my-btn2" data-dismiss="modal">Закрыть</button>
            <button type="button" class="btn my-btn">Сохранить</button>
        </div>
    </div>
</div>
</div>

@endsection
