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
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="6">
                <table>
                    <tr>
                        <td class="title">
                            Накладная
                        </td>


                        <td>
                            Отгрузка  #: {{ $shipment->id }}<br>
                            Создан: {{ date('Y-m-d',strtotime($shipment->created_at)) }}<br>
                        </td>
                        <td class="barcode">
                            {{-- <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG((string)$sale->id, 'EAN13', 2, 50, array(1,1,1), true)}}" alt="barcode" /> --}}
                            {!! DNS1D::getBarcodeSVG((string)$shipment->id, "C128", 2, 50) !!}
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
                            <div><strong>Клиент:</strong> {{ $shipment->client->name }}</div>
                            <div> <strong>Адрес: </strong> {{ $shipment->client->actual_address }}</div>
                            <div><strong>Телефон:</strong> {{ $shipment->client->phone }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <th class="center">#</th>
            <td>Продукт</td>
            <td>Склад</td>
            <td>Кол-во</td>
            <td>Цена</td>
            <td>Общая сумма</td>
        </tr>

        @foreach($shipment->shipment_products as $product)
            <tr class="item @if($loop->last) last @endif" >
                <td >{{ $loop->iteration }}</td>
                <td >{{ $product->product->name }}</td>
                <td >{{ ($product->warehouse) ? $product->warehouse->name : '' }}</td>
                <td >{{ $product->quantity }} {{ ($product->product) ? ($product->product->measurement) ? $product->product->measurement->name  : '' : ''}}</td>
                <td >{{ number_format($product->price,2,'.',' ') }} сум</td>
                <td >{{ number_format($product->price * $product->quantity,2,'.',' ') }} сум</td>
            </tr>
        @endforeach

        <tr class="total">
            <td class="last" colspan="5">Итого:</td>
            
            <td class="last">
                {{ number_format($shipment->total_amount,2,'.',' ') }} сум
            </td>
        </tr>
    </table>
    <br>

    <div class="agentsInfoWrapper">
        <div class="clientInfoos">
            <div class="clientMainInfo">
                <span>Клиент: {{ $shipment->client->name }}</span>
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
