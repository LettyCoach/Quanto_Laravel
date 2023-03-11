<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice</title>
    <link href="{{ asset('public/css/invoicePDF') }}" rel="stylesheet">
    <link href="{{ asset('public/css/pdf.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/pdf_table.css') }}" rel="stylesheet">

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
            background-color:white;
        }
        
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        table th,
        table td {
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

        <div class="footer" style="padding-top: 10px; color: #000; margin-top: 20px; text-align: center;"><span
                style="font-size:16px; font-weight: 800;">POWERED BY</span> <span
                style="font-size:24px;font-weight: 800;">QUANTO</span>
        </div>

        <table>
            <td class="pro33"><img id="logo" src="{{url('public/img/pdf_logo.png')}}" style="height:50px;" /></td>
            <td class="pro33 p2 text-center t6">{{ $purpose }}</td>
            <td class="pro33 t1 text-center">発行日：{{ $date }}</td>
        </table>
                
        <table class="mb1">
            <tr>
                <td class="pro60">
                    <div id="uNameDiv" class="t4 uline-grey pb-1">{{ $invoiceUserName }}　様</div>
                    <div id="uMethodDiv" class="uline-grey pb5 text-left t2 ufit"> 支払方法：{{ $payment_method }}</div>
                </td>
                <td class="flex-between p2">
                    <div class="p2">
                    @if ($profile_url != "")
                        <img id="profile" alt="profile"
                            src="{{ url($profile_url) }}"
                            style="border-style:solid; border-width:1px; height:50px; width:50px" />
                    @endif
                    </div>
                    <div class="t1">
                        <div>請求書No,{{ $invoiceNumber }}</div>
                        <div>{{ $user->full_name }}</div>
                        <div>{{ $invoice }}</div>
                    </div>
                </td>
            </tr>
        </table>
        
                <div class="flex pro100">
                    <div class="flex-between pro60">
                        <div class="flex-end pb3">
                            <div class="uline-grey pb1">
                                <span class="t1">ご請求金額&nbsp;&nbsp;</span>
                                <span class="t5 b4" style="vertical-align: 3px;">{{ number_format($totalMoney) }}円&nbsp;</span>
                            </div>
                        </div>

                    </div>

                    <div class="flex-between t1 pro40">
                        <div class="flex-center p2 text-center w60">
                            <p>住所</p>
                        </div>
                        <div class="flex p2">
                            <div>
                                <div>〒{{ $user->zip_code }}</div>
                                <div>{{ $user->address }}</div>
                                <div>Tel：{{ $user->phone_number }}</div>
                            </div>
                        </div>
                        <div class="flex-center p3">
                            <img alt="stamp" id="stamp" src="{{ $stamp_url }}" style="height:70px; width:70px" />
                        </div>
                    </div>
                </div>



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



                <div>
                    <div class="t1"> 有効期間 <input id="eDate" class="input2 w200" value="2023年3月10日"></div>
                    <hr style="border-top:2px solid blue">
                    <p class="text-center t2">内容明細 {{$addString}}</p>
                </div>
                <div id="main_table">
                    <table cellpadding="1" cellspacing="0" class="main-table">
                        <thead>
                            <tr>
                                <th class="th1" style = "width : 50%">内容</th>
                                <th class="th2" style = "width : 15%">単価</th>
                                <th class="th3" style = "width : 15%">数量</th>
                                <th class="th4" style = "width : 20%">金額(円)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($invoiceData as $i => $item) {

                                if ($i == $line_first_page || $i > $line_first_page && ($cntInvoiceItem - $line_first_page) % $line_per_page == 0) {

                        ?>  
                                
                            <tr>
                                <td class="td-r1">小計</td>
                                <td></td>
                                <td class="td-r3">{{$cnt_per_page}} </td>
                                <td class="td-r4">{{ number_format($sum) }}円</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="page-break-after:always"><span style="display:none"></span></div>
            
                
                <div class="flex-between mb1">
                    <div class="pro33"><img id="logo" src="{{url('public/img/pdf_logo.png')}}" style="height:50px;" /></div>
                    <div class="pro33 flex-center text-center p2 text-center t6 b8">{{ $purpose }}</div>
                    <div class="pro33 text-right t1 b2 text-center">発行日：{{ $date }}</div>
                </div>
                <div>
                    <hr style="border-top:2px solid blue">
                    <p class="text-center t2">内容明細 2/2</p>
                </div>
                <div id="main_table2">
                    <table cellpadding="1" cellspacing="0" class="main-table">
                        <thead>
                            <tr>
                                <th class="th1" style = "width : 50%">内容</th>
                                <th class="th2" style = "width : 15%">単価</th>
                                <th class="th3" style = "width : 15%">数量</th>
                                <th class="th4" style = "width : 20%">金額(円)</th>
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
                        <tr>
                            <td class="td-a1" style="align-items:center">
                                <img alt="product" src="{{ url($item['imgUrl']) }}" class="td-a1-d2-img" />
                                {{$item['name']}}
                            </td>
                            <td style = "text-align : right"><span>{{number_format($price)}}円</span></td>
                            <td class="td-a3">{{$amount}}</td>
                            <td class="td-a4">{{ number_format($money) }}円</td>
                        </tr>
                        <?php
                            }
                        ?>

                            <tr>
                                <td class="td-r1">合計</td>
                                <td></td>
                                <td class="td-r3">{{$cnt_per_page}} </td>
                                <td class="td-r4">{{ number_format($sumAll) }}円</td>
                            </tr>
                            <tr>
                                <td class="td-t1" id="reduce_pro_td">消費税(8%対象)</td>
                                <td></td>
                                <td class="td-t3"> </td>
                                <td class="td-t4"> (内税){{ number_format($sumAll * 0.08) }}円</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            
            <textarea style="width:100%; height: 100px; border: 1px solid grey; padding: 5px; box-sizing: border-box; margin-top:20px; font-size: 20px;"
                placeholder="(備考)"></textarea>
</body>

</html>