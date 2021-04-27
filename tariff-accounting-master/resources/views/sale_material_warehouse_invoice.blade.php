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
            /* box-shadow: 0 0 10px rgba(0, 0, 0, .15); */
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
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border: 1px solid #eee;
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
     <span style="position: absolute; right: 0; top: 0; font-size: 9px">
        {{ date('Y-m-d',strtotime(now())) }}
    </span>
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="6">
                <table>
                    <tr>
                        <td class="title">
                            Накладная
                        </td>

                        <td>
                            Заказ #: {{ $sale->id }}<br>
                            Клиент: {{ $client_name }}<br>
                        </td>

                        <td class="barcode">
                            {!! DNS1D::getBarcodeSVG((string)$sale->id, "C128", 2, 50) !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>#</td>
            <td>Название сырья</td>
            <td>Кол-во</td>
            <td>Склад</td>
        </tr>

        @foreach($sale_material_warehouses as $sale_material_warehouse)
            <tr class="item" >
                <td >{{ $loop->iteration }}</td>
                <td >{{ ($sale_material_warehouse['material']) ? $sale_material_warehouse['material']['name'] : '' }}</td>
                <td > {{ $sale_material_warehouse['quantity'] }} {{  ($sale_material_warehouse['material'] && $sale_material_warehouse['material']['measurement']) ? $sale_material_warehouse['material']['measurement']['name']  : '' }}</td>
                <td > {{ ($sale_material_warehouse['warehouse']) ? $sale_material_warehouse['warehouse']['name'] : '' }}</td>
            </tr>
        @endforeach
    </table>
    <br>
    <div class="agentsInfoWrapper">
        <div class="clientInfoos">
            <div class="clientMainInfo">
                <span>Склад:</span>
                <span>_______________________</span>
            </div>
            <div class="clientSign">
                <span> Подпись __________ </span>
            </div>
        </div>
        <hr>
        <div class="sellerInfoos">
            <div class="sellertMainInfo">
                <span>Принел: </span>
                <span>_______________________</span>
            </div>
            <div class="sellerSign">
                <span>Подпись __________</span>
            </div>
        </div>
    </div>
</div>
</body>
</html>