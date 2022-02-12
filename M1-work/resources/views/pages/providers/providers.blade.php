@extends('layouts.app')

@section('content')


<div class="top-mini-menu p15">
    <a href="providers.html" class="actioneTopM">Поставщики</a>
</div>

<div class="main_c_centeer p15">
    <div class="blog-title">
        <ul class="ul-menu">
            <li>
                <a href="../../index.html"><i class=" fas fa-home"></i></a>
                <span class="d-bi  ">/</span>
            </li>
            <li>
                <a>Поставщики</a>
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
                        <th scope="col">Поставщики</th>
                        <th scope="col">Telfon</th>
                        <th scope="col">Дата создания</th>
                        <th scope="col">
                            <i class="fas fa-sliders-h "></i>
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">
                            <input type="text" class="form-control f-sForm" placeholder="#">
                        </th>
                        <th scope="col">
                            <input type="text" class="form-control f-sForm" placeholder="Наименование">
                        </th>

                        <th scope="col">
                            <input type="text" class="form-control f-sForm" placeholder="Поставщики">
                        </th>

                        <th scope="col">
                            <input type="text" class="form-control f-sForm" placeholder="Telfon">
                        </th>

                        <th scope="col">
                            <input type="text" class="form-control f-sForm  datepicker-here" id="my-date"
                                placeholder="'Ozgargan Sana" data-multiple-dates="3"
                                data-multiple-dates-separator=", " />
                        </th>

                        <th scope="col">
                            <i class="fas fa-sliders-h "></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">1</td>
                        <td>Cola suv</td>
                        <td>Azimov AA</td>
                        <td>+99899 557-95-55</td>
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


@endsection
