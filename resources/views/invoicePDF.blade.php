<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>

    <style>
        @font-face{
            font-family: yugothic-medium;
            font-style: normal;
            font-weight: normal;
            src:url('{{ storage_path('fonts/yugothic-medium.otf')}}');
        }
        @page {
            margin-top: 20px;
            margin-bottom: 10px;
        }
        body {
            font-family: yugothic-medium;
            line-height: 0.6;
        }
        table{
            border-collapse: collapse;
            border-spacing: 0;
        }
        table td,
        table td {
            font-family: yugothic-medium;
            word-break: break-word;
        }
    </style>
</head>
<body>
    
    <?php
        if (isset($user->settings)) { 
            $userSettings = json_decode($user->settings);
            $invoice = isset($userSettings->invoice) ? $userSettings->invoice : '';
            $member = isset($userSettings->member) ? $userSettings->member : '';
            $purpose = isset($userSettings->purpose) ? $userSettings->purpose : '';
            $payment_method = isset($userSettings->payment_method) ? $userSettings->payment_method : '';
            $stamp_url = isset($userSettings->stamp_url) ? url($userSettings->stamp_url) : '';
        } else { $invoice = ''; $member = ''; $purpose = ''; $payment_method = ''; $stamp_url = ''; } 
    
        if (isset($survey->settings)) { 
            $surveySettings = json_decode($survey->settings);
            $note = isset($surveySettings->note) ? $surveySettings->note : '';
            $member = isset($surveySettings->member) ? $surveySettings->member : $member;
            $purpose = isset($surveySettings->purpose) ? $surveySettings->purpose : $purpose;
            $payment_method = isset($surveySettings->payment_method) ? $surveySettings->payment_method : $payment_method;
        } else { $note = ''; }

        $profile_url = isset($user->profile_url) ? $user->profile_url : '';
    ?>
    
    <div style="position: fixed; bottom: 0px; width: 100%; text-align: center; padding-top: 10px; color: #000; margin-top: 20px; text-align: center;"><span
            style="font-size:16px; font-weight: 800;">POWERED BY</span> <span
            style="font-size:24px;font-weight: 800;">QUANTO</span>
    </div>

    <table style = "width : 100%">
        <tr>
            <td style="width : 30%; vertical-align : top">
                <div><img src="{{url('public/img/pdf_logo.png')}}" style="height:50px;"></div>
            </td>
            <td style="width : 40%">
                <div style="border: solid 1px #dbdbdb; text-align:center; padding : 10px; font-size:40px ">{{ $purpose }}</div>
            </td>
            <td style="width : 30%; vertical-align : top">
                <div style="text-align:right; ">発行日：{{ $date }}</div>
            </td>
        </tr>
    </table>

    <table style="padding-top:24px; width : 100%">
        <tr>
            <td style = "width : 50%">
                <div style="text-decoration:underline; font-size: 28px; padding-left: 16px">{{ $invoiceUserName }}　様</div><br>
                <div style="font-size: 20px">支払方法：{{ $payment_method }}</div>
            </td>
            <td style="width : 20%; text-align: right; padding-right: 10px">
                <img id="profile" alt="profile"
                    src="{{ url($profile_url) }}"
                    style="border: 1px solid grey; height:50px; width:50px" />
            </td>
            <td style = "width : 30%; font-size: 16px">
                <div>請求書No,{{ $invoiceNumber }}</div>
                <div>{{ $user->full_name }}</div>
                <div>{{ $invoice }}</div>
            </td>
        </tr>
    </table>

    
    <table style="padding-top:24px; width : 100%">
        <tr>
            <td style = "width : 15%; text-align: center; font-size: 16px; border-bottom: 1px solid grey">
                ご請求金額
            </td>
            <td style = "width : 35%; text-align: center; font-size: 40px; border-bottom: 1px solid grey">
                {{ number_format($totalMoney) }}円
            </td>
            <td style="width : 10%; text-align: right; padding-right: 10px; font-size: 16px">
                住所:
            </td>
            <td style = "width : 25%; font-size: 16px">
                <div>〒{{ $user->zip_code }}</div>
                <div>{{ $user->address }}</div>
                <div>Tel：{{ $user->phone_number }}</div>
            </td>
            <td style="width : 10%;">
                <img id="profile" alt="profile"
                    src="{{ $stamp_url }}"
                    style="border: 1px solid grey; height:60px; width:60px" />
            </td>
        </tr>
    </table>

    <table style="padding-top:24px; width : 100%">
        <tr>
            <td style = "width : 15%; font-size: 16px; border-bottom: 2px solid blue">
                有効期限　{{$expire}}
            </td>
        </tr>
    </table>

    <?php

        $line_first_page = 10;
        $line_per_page = 14;
        $pages = 0;
        $cntInvoiceItem = count($invoiceData);
        $sum = 0;
        $sumAll = 0;
        $cnt_per_page = 0;



        $addString = "";
        if ($cntInvoiceItem > $line_first_page){
            $pages = 1 + ceil(($cntInvoiceItem - $line_first_page) / $line_per_page);
            $addString = "1/$pages";
        }
        ?>

        <p style="font-size: 20px; text-align: center;">内容明細 {{$addString}}</p>

        <table cellpadding="1" cellspacing="0" style="width: 100%">
            <thead>
                <tr>
                    <td style = "font-size: 16px; text-align:center; width : 5%"></td>
                    <td style = "font-size: 16px; text-align:center; width : 45%">内容</td>
                    <td style = "font-size: 16px; text-align:center; width : 15%">単価</td>
                    <td style = "font-size: 16px; text-align:center; width : 15%">数量</td>
                    <td style = "font-size: 16px; text-align:center; width : 20%">金額(円)</td>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach($invoiceData as $i => $item) {

                    if ($i == $line_first_page || $i > $line_first_page && ($cntInvoiceItem - $line_first_page) % $line_per_page == 0) {

            ?>  
                <tr>
                    <td></td>
                    <td style = "text-align : center">小計</td>
                    <td></td>
                    <td style = "text-align : center">{{$cnt_per_page}} </td>
                    <td style = "text-align : right">{{ number_format($sum) }}円</td>
                </tr>
            </tbody>
        </table>
        
        <div style="page-break-after:always"><span style="display:none"> </span></div>

        <table style = "width : 100%">
            <tr>
                <td style="width : 30%; vertical-align : top">
                    <div><img src="{{url('public/img/pdf_logo.png')}}" style="height:50px;"></div>
                </td>
                <td style="width : 40%">
                    <div style="border: solid 1px #dbdbdb; text-align:center; padding : 10px; font-size:40px ">{{ $purpose }}</div>
                </td>
                <td style="width : 30%; vertical-align : top">
                    <div style="text-align:right; ">発行日：{{ $date }}</div>
                </td>
            </tr>
        </table>
        <hr style="border-top:2px solid blue">

        <p style="font-size: 20px; text-align: center;">内容明細 2/2</p>
        
        
        <table cellpadding="1" cellspacing="0" style="width: 100%">
            <thead>
                <tr style="border-bottom: 1px solid grey">
                    <td style = "font-size: 16px; text-align:center; width : 5%"></td>
                    <td style = "font-size: 16px; text-align:center; width : 45%">内容</td>
                    <td style = "font-size: 16px; text-align:center; width : 15%">単価</td>
                    <td style = "font-size: 16px; text-align:center; width : 15%">数量</td>
                    <td style = "font-size: 16px; text-align:center; width : 20%">金額(円)</td>
                </tr>
            </thead>
            <tbody>

            <?php
                        $sum = 0;
                        $cnt_per_page = 0;
                    }


                    $price = $item['price'];
                    $amount = $item['amount'];
                    $money = $price * $amount;
                    $sum += $money;
                    $sumAll += $money;
                    $cnt_per_page += $amount;
            ?>
            <tr style="border-bottom: 1px solid grey">
                <td style="align-items:center">
                    <img alt="product" src="{{ url($item['imgUrl']) }}" style="width:50px; height;50px" />
                </td>
                <td>{{$item['name']}}</td>
                <td style = "text-align : right"><span>{{number_format($price)}}円</span></td>
                <td style = "text-align : center">{{$amount}}</td>
                <td style = "text-align : right">{{ number_format($money) }}円</td>
            </tr>
            <?php
                }
            ?>

                <tr style="border-bottom: 1px solid grey;">
                    <td style=" height : 54px"></td>
                    <td style = "text-align : center">合計</td>
                    <td></td>
                    <td style = "text-align : center">{{$cnt_per_page}} </td>
                    <td style = "text-align : right">{{ number_format($sumAll) }}円</td>
                </tr>
                <tr style="padding-top: 16px; font-size: 12px; border-bottom: 1px solid grey">
                    <td style=" height : 54px"></td>
                    <td style = "text-align : center">消費税(8%対象)</td>
                    <td ></td>
                    <td ></td>
                    <td style = "text-align : right"> (内税){{ number_format($sumAll * 0.08) }}円</td>
                </tr>
            </tbody>
        </table>

        <textarea style="width:100%; height: 100px; border: 1px solid grey; padding: 5px; box-sizing: border-box; margin-top:20px; font-size: 20px;"
        placeholder="(備考)"></textarea>

</body>

</html>