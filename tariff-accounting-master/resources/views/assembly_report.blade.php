<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
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
        .text-danger{
            color: red;
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
                            Сборка<br>
                            Создан: {{ date('Y-m-d',strtotime(now())) }}<br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
        <h5>{{ __('messages.semi_product_cost') }}</h5>
    <table cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td>#</td>
            <td>{{ __('messages.name') }}</td>
            <td>{{ __('messages.Quantity short') }}</td>
            <td>{{ __('messages.Price') }}</td>
            <td>{{ __('messages.warehouse') }}</td>
            <td>{{ __('messages.Sum') }}</td>
        </tr>
        @foreach($report_products_with_price as $item)
            <tr class="item">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ ($item['product']) ? $item['product']->name : '' }}</td>
                <td>
                    {{ number_format($item['quantity'],3,',',' ') }} {{ $item['product'] ? $item['product']->measurement ? $item['product']->measurement->name : '': '' }}
                </td>
                @if($item['status'])
                    <td>{{ number_format($item['price'],2,',',' ') }} {{ $item['currency'] ? $item['currency']->symbol : '' }}</td>
                    <td>{{ ($item['warehouse']) ? $item['warehouse']->name : '' }}</td>
                    <td>{{ number_format($item['quantity'] * $item['price'],2,',',' ') }} {{ $item['currency'] ? $item['currency']->symbol : '' }}</td>
                @else
                    <td colspan="3"  class="text-center"><span class="text-danger">{{ $item['message'] }}</span></td>
                @endif
            </tr>
        @endforeach
        @php
            $sum = 0;
            foreach ($report_products_with_price as $item){
                if ($item['status']){
                    $sum +=$item['price'] * $item['quantity'];
                }
            }
        @endphp
    </table>
    <br>
    <h5>{{ __('messages.cost_material') }}</h5>
    <table cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td>#</td>
            <td>{{ __('messages.name') }}</td>
            <td>{{ __('messages.Quantity short') }}</td>
            <td>{{ __('messages.Price') }}</td>
            <td>{{ __('messages.warehouse') }}</td>
            <td>{{ __('messages.Sum') }}</td>
        </tr>
        @foreach($report_materials_with_price as $item)
            <tr class="item">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ ($item['material']) ? $item['material']->name : '' }}</td>
                <td>
                    {{ number_format($item['quantity'],3,',',' ') }} {{ $item['material'] ? $item['material']->measurement ? $item['material']->measurement->name : '': '' }}
                </td>
                @if($item['status'])
                    <td>{{ number_format($item['price'],2,',',' ') }} {{ $item['currency'] ? $item['currency']->symbol : '' }}</td>
                    <td>{{ ($item['warehouse']) ? $item['warehouse']->name : '' }}</td>
                    <td>{{ number_format($item['quantity'] * $item['price'],2,',',' ') }} {{ $item['currency'] ? $item['currency']->symbol : '' }}</td>
                @else
                    <td colspan="3"  class="text-center"><span class="text-danger">{{ $item['message'] }}</span></td>
                @endif
            </tr>
        @endforeach
        @php
            $sum = 0;
            foreach ($report_materials_with_price as $item){
                if ($item['status']){
                    $sum +=$item['price'] * $item['quantity'];
                }
            }
        @endphp
        <tr class="total">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="last" colspan="2">
                {{ __('messages.total') }}: {{ number_format($sum,2,',',' ') }} {{ config('app.currency') }}
            </td>
        </tr>
    </table>
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
