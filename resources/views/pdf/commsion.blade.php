<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<!---------------------------------------------- style for the pdf file --------------------------------------------->
<style>
    body {
        font-family: initial;
    }

    h1 {
        font-family: initial;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        overflow: hidden;
        border: none;
    }

    table tr th[data-content='$']
    {
        width: 25% !important;
    }

    table tr th[data-content='#']
    {
        width: 10% !important;
    }

    table tr th[data-content='a']
    {
        width: 10% !important;
    }

    th {
        background-color: gray;
        padding: 1%;
    }

    /*======================================== new css =========================================*/
    .title {
        border-bottom: 1px black solid;
        text-align: center;
        line-height: 1;
        margin: 1% 40%;
    }

    .col-6 {
        width: 50%;
    }

    .eng{
        text-align: end;
    }

    .right{
        text-align: right;
    }

</style>
<body>
    <!---------------------------------------------- header --------------------------------------------->

    <img src={{ public_path('frontend/assets/img/logo.png') }} width="30%" height="auto">
    <div class="title">
        <h3><b> فاتورة </b></h3>
        <h3><b> INVOICE </b></h3>
    </div>


    <div class="container">
           <table style="direction:rtl">
                <tr>
                    <th class="right" data-content='a'> البائع</th>
                    <th data-content='$'><center></center></th>
                    <th class="eng" data-content='a'> Vendor</th>
                    <th style="background-color:white;" data-content='#'></th>
                    <th style="background-color:white;" data-content='#'></th>
                    <th class="right" data-content='a'>المشتري</th>
                    <th data-content='$'><center></center></th>
                    <th class="eng" data-content='a'>Purchaser</th>
                </tr>
                <tr>
                    <td class="right">فاتورة رقم </td>
                    <td><center>{{$bill_number}}</center></td>
                    <td class="eng">INVOICE #</td>
                    <th style="background-color:white;"></th>
                    <th style="background-color:white;"></th>
                    <td class="right"> الشركة </td>
                    <td><center>{{$shop->name}}</center></td>
                    <td class="eng">COMPANY</td>
                </tr>
                <tr>
                    <td class="right"> التاريخ </td>
                    <td><center>{{$created_at}}</center></td>
                    <td class="eng">DATE</td>
                    <th style="background-color:white;"></th>
                    <th style="background-color:white;"></th>
                    <td class="right"> المسؤول </td>
                    <td><center></center></td>
                    <td class="eng">RESPONSIBLE</td>
                </tr>
                <tr>
                    <td class="right"> قدمت من </td>
                    <td><center>شركة نواة الربط لتقنية المعلومات</center></td>
                    <td class="eng"> OFFERED BY </td>
                    <th style="background-color:white;"></th>
                    <th style="background-color:white;"></th>
                    <td class="right"> رقم الهاتف </td>
                    <td><center> {{$shop->phone}} </center></td>
                    <td class="eng">.PHONE NO</td>
                </tr>
                <tr>
                    <td class="right"> </td>
                    <td><center></center></td>
                    <td class="eng"></td>
                    <th style="background-color:white;"></th>
                    <th style="background-color:white;"></th>
                    <td class="right"> العنوان </td>
                    <td><center> {{$shop->address}} </center></td>
                    <td class="eng"> ADDRESS </td>
                </tr>
                <tr>
                    <td class="right"> الرقم الضريبي </td>
                    <td><center></center></td>
                    <td class="eng"> .VAT NO </td>
                    <th style="background-color:white;"></th>
                    <th style="background-color:white;"></th>
                    <td class="right"> الرقم الضريبي </td>
                    <td><center> {{$shop->commerical_number}} </center></td>
                    <td class="eng"> .VAT NO </td>
                </tr>
            </table>

            <table style="margin-top:3%; text-align:center">
                <tr>
                    <th>#</th>
                    <th>البند <br>ITEM</th>
                    <th> إجمالي المبيعات غير شامل الضريبة <br> Total Salles Bef. Vat</th>
                    <th> قيمة العمولة <br> Commission (15%)</th>
                    <th>ضريبة القيمة المضافة <br>VAT(15%)</th>
                    <th> الإجمالي شامل الضريبة <br>TOTAL AFTER VAT</th>
                </tr>
                 @php $key = 0; @endphp
                @foreach($shop_orders as $order)
                <tr>
                    <td> {{$key+1}} </td>
                    <td> خدمات تسويقية للطلب رقم {{$order->id}} <br> Marketing Services</td>
                    <td> {{$order->merchant_will_get}} </td>
                    <td> {{$order->commsion_value}} </td>
                    <td> {{$order->vatcommsionfororder}} </td>
                    <td> {{$order->vatwithcommsionfororder}} </td>
                </tr>
                @php $key++; @endphp
                @endforeach
            </table>

            <hr>
            <table style="width:50%; float: right;text-align: center;margin-top:3%">
            <tr>
            <th style="background-color:white !important"> المجموع قبل الضريبة <br> Total Before Vat </th>
            <td> {{$totalcommsion}} </td>
            </tr>
            <tr>
            <th style="background-color:white !important"> قيمة الضريبة <br> Vat Amount (15%)</th>
            <td> {{$totalvat}} </td>
            </tr>
            <tr>
            <th style="background-color:white !important"> المجموع بعد الضريبة <br> Total After Vat </th>
            <td> {{$totalvatwithcommsion}} </td>
            </tr>
            </table>
    </div>


</body>
</html>
