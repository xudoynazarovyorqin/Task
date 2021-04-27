<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }


        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td.last {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
        .clientInfoos, .sellerInfoos{
            display: flex;
            justify-content: space-between;
        }
    </style>

</head>
<body>

<div class="invoice-box" style="position: relative">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="6">
                <table>
                    <tr>
                        <td class="title">
                            Накладная
                        </td>

                        <td>
                            Сборка #: {{ $assembly->id }}<br>
                            Создан: {{ date('Y-m-d',strtotime($assembly->created_at)) }}<br>
                        </td>

                        <td class="barcode">
                            {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG((string)$assembly->id, 'EAN13', 2, 50, array(1,1,1), true)}}" alt="barcode" /> --}}
                            {!! DNS1D::getBarcodeSVG((string)$assembly->id, "C128", 2, 50) !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="6">
                <table>
                    <tr>
                        <td>
                            <div> <strong>Статус: </strong> {{ ($assembly->state) ? $assembly->state->state : '' }} </div>
                            <div> <strong>Приоритет: </strong> {{ ($assembly->priority) ? $assembly->priority->name : '' }}</div>
                            <div> <strong>Производства почему : </strong> <?php
                                switch ($assembly->assemblyable_type){
                                    case 'orders': echo 'Заказы на производство'; break;
                                    case 'assembly': echo 'Сборки'; break;
                                    case null: echo 'Для склад'; break;
                                }
                                ?> </div>
                            <div><strong>Дата от:</strong> {{ $assembly->begin_date ? date('Y-m-d',strtotime($assembly->begin_date)) : '' }}  до: {{ $assembly->end_date ? date('Y-m-d',strtotime($assembly->end_date)) : '' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <th class="center">#</th>
            <td>Продукт</td>
            <td>Кол-во</td>
            <td>Готов</td>
            <td>Брак</td>
        </tr>

        @foreach($assembly->items as $item)
            <?php
                $total_defect = $item->defect_products()->sum('quantity');
            ?>
            <tr class="item @if($loop->last) last @endif" >
                <td >{{ $loop->iteration }}</td>
                <td >{{ ($item->product) ? $item->product->name : '' }}</td>
                <td >{{ $item->quantity }} {{  ($item->product) ? ($item->product->measurement) ? $item->product->measurement->name  : '' : ''}}</td>
                <td >{{ $item->ready }} {{  ($item->product) ? ($item->product->measurement) ? $item->product->measurement->name  : '' : ''}}</td>
                <td >{{ $total_defect }} {{  ($item->product) ? ($item->product->measurement) ? $item->product->measurement->name  : '' : ''}}</td>
            </tr>
        @endforeach

    </table>
    <br>
    <div class="agentsInfoWrapper">
        <div class="clientInfoos">
            <div class="clientMainInfo">
                <span>Клиент: </span>

                <span></span>
            </div>
            <div class="clientSign">
                <span> Подпись __________ </span> <sub>М.П</sub>
            </div>
        </div>
        <hr>
        <div class="sellerInfoos">
            <div class="sellertMainInfo">
                <span>Сдал: </span>
                <span>_______________________</span>
            </div>
            <div class="sellerSign">
                <span>Подпись __________</span> <sub>М.П</sub>
            </div>
        </div>
    </div>
</div>
</body>
</html>
