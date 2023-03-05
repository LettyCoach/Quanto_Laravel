<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>PDF編集</title>
<style>
table{
    border-collapse: collapse;
    border-spacing: 0;
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
    <table>
        <tr>
            <th style="width: 40vw;">
            <th style="width: 20vw;">
            <th style="width: 40vw;">
        </tr>
        <tr>
            <td><div><img src="{{url('public/img/pdf_logo.png')}}" style="height:30;"></div></td>
            <td style="text-align:center;"><font size="5" style="border: solid 1px #dbdbdb; padding: 0 20px 0 20px">{{ $purpose }}</td>
            <td><div style="text-align:right;"><font size="2">発行日：{{ $date }}</div></td>
        </tr>
    </table>
    <table style="padding-top:10px;">
        <tr>
            <th style="width: 14vw;">
            <th style="width: 40vw;">
            <th style="width: 14vw;">
            <th style="width: 28vw;">
            <th style="width: 4vw;">
        <tr>
            <td colspan="2" rowspan="4" style="vertical-align: top;">
                <div style="text-decoration:underline;"><font size="5">{{ $name }}　様</div><br>
                <div><font size="2">支払方法：{{ $payment_method }}</div>
            </td>
            <td rowspan="3">
                @if ($profile_url != "")
                <img src="{{ url($profile_url) }}" style="padding-left:45px; width:30; height:30;">
                @endif
            </td>
            <td colspan="2"><font size="2"><div>請求書No,Q{{ $user->id + 1000 }}-{{ $survey->id }}-{{ $branch->count}}</div></td>
        </tr>
        <tr>
            <td colspan="2"><font size="3"><div>{{ $user->full_name }}</div></td>
        </tr>
        <tr>
            <td colspan="2"><font size="3"><div>{{ $invoice }}</div></td>
        </tr>
        <tr>
            <td rowspan="5" style="text-align:right; padding-right:10px;"><font size="2">住所</td>
            <td rowspan="5">
                <font size="2">〒{{ $user->zip_code }}<br>
                {{ $user->address }}<br>
                Tel：{{ $user->phone_number }}<br>
                {{ $member }}</font>
            </td>
            <td rowspan="5" style="vertical-align: top;"><img src="{{ $stamp_url }}" style="width:40; height:40; margin-top: 20px"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="border-bottom:double 3px; vertical-align: bottom;">ご請求金額</td>
            <td style="text-align:center; border-bottom:double 3px;"><font size="6">￥{{ number_format($total) }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"><font size="2">有効期限：{{ $expire }}</td>
        </tr>
    </table>
    <style>
        .hr1{
            border: none;
            border-bottom: 2px solid #4f7ac7;
            margin: 0;
            }
    </style>
    <hr class="hr1">

    <!--内容明細-->
    <style>
        .td1{
            border-bottom: 2px solid #f3f3f3;
            margin: 0;
            height: 40;
        }
    </style>
    <?php
        $_img_urls = json_decode($img_urls);
        $_titles = json_decode($titles);
        $_prices = json_decode($prices);
        $_quantities = json_decode($quantities);
        $_taxes = json_decode($taxes);
        $_count = count($_titles);
        $_question_code = json_decode($question_code);
        $_parent_code = json_decode($parentCategory);
        $_total_quantities = 0;
        $_total_10 = 0;
        $_total_8 = 0;
        $_taxe_10 = 0;
        $_taxe_8 = 0;
        $_subtotal_price = 0;//小計金額
        $_subtotal_count = 0;//小計個数
        $_pages = 0;
        $_new_page = 0;
        $_ranges = [];
        $_new_page = $_count;
    ?>
    <div style="text-align:center; padding-top:5px"><font size="4">内容明細</div>
    <!--内容明細１ページ目-->
    <table>
        <tr>
            <th style="width: 54vw;">
            <th style="width: 23vw;">
            <th style="width: 23vw;">
        </tr>
        <tr>
            <td style="border-bottom: 2px solid #f3f3f3;"><div style="text-align:center;">内容</div></td>
            <td style="border-bottom: 2px solid #f3f3f3;"><div style="text-align:center;">数量</div></td>
            <td style="border-bottom: 2px solid #f3f3f3;"><div style="text-align:center;">金額(円)</div></td>
        </tr>
        <?php for ($i = 0; $i < $_new_page; $i++) {
            if (empty($_prices[$i])){    
                $_prices[$i] = 0;
            }
            if (empty($_quantities[$i])){    
                $_quantities[$i] = 0;
            } 
            if ($_taxes[$i] == 10){
                $_total_10 += $_prices[$i] * $_quantities[$i];
            } else {
                $_total_8 += $_prices[$i] * $_quantities[$i];
            }
            $_total_quantities += $_quantities[$i];
            $_subtotal_price += $_prices[$i] * $_quantities[$i];
            $_subtotal_count += $_quantities[$i]; ?>
            <tr>
                <td class ="td1" style="padding-left:{{ child_range($i,$_question_code,$_parent_code,$_ranges) }};">
                    @if ($_img_urls[$i] != "")
                        <img src="{{ url($_img_urls[$i]) }}" width="40" height="40" style="vertical-align:middle; padding-top:9px;">
                        <span style="vertical-align:top; padding-left:10px">{{ $_titles[$i] }}</span>
                    @else
                        <div style="padding-left:55px">{{ $_titles[$i] }}</div>
                    @endif
                </td>
                <?php if ($_quantities[$i] == 0){ ?>
                    <td class="td1" style="text-align:center;">-</td>
                <?php }else { ?>
                    <td class="td1" style="text-align:center;"><font size="4">{{ $_quantities[$i] }}</div></td>
                <?php } ?>
                <?php if ($_prices[$i] == 0){ ?>
                    <td class="td1" style="text-align:right; padding-right:20px">-</td>
                <?php }else { ?>
                    <td class="td1" style="text-align:right;"><font size="5">{{ number_format($_prices[$i]*$_quantities[$i]) }}</div></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
    <!--内容明細フッター(合計)-->
    <table style="padding-top:5px">
        <tr>
            <th style="width: 54vw;">
            <th style="width: 23vw;">
            <th style="width: 23vw;">
        </tr>
        <tr>
            <td><div style="text-align:center; padding:right 50px"><font size="4">合計</font></div></td>
            <td><div style="text-align:center;"><font size="4">{{ $_total_quantities }}点</div></td>
            <td><div style="text-align:right;"><font size="5">{{ number_format($total) }}</div></td>
        </tr>
    </table>
    <hr class="hr1">
    <table>
        <tr>
            <th style="width: 58vw;">
            <th style="width: 24vw;">
            <th style="width: 10vw;">
            <th style="width: 10vw;">
            <th style="width: 4vw;">
        </tr>
        <?php if ($_total_10 != 0){ ?>
            <tr>
                <td><div style="text-align:center;">消費税(10%対象)</div></td>
                <td><div style="text-align:right;"><font size="4">{{ number_format($_total_10) }}</div></td>
                <td><div style="text-align:center;">円　内税</div></td>
                <td><div style="text-align:right;">{{ number_format(floor($_total_10 * 0.1)) }}</div></td>
                <td><div style="text-align:center;">円</div></td>
            </tr>
        <?php } 
        if ($_total_8 != 0){ ?>
            <tr>
                <td><div style="text-align:center;">消費税(8%対象)</div></td>
                <td><div style="text-align:right;"><font size="4">{{ number_format($_total_8) }}</div></td>
                <td><div style="text-align:center;">円　内税</div></td>
                <td><div style="text-align:right;">{{ number_format(floor($_total_8 * 0.08)) }}</div></td>
                <td><div style="text-align:center;">円</div></td>
            </tr>
        <?php } ?>
    </table>
    <hr style="border: none; border-bottom: 1px solid #4f7ac7; margin: 0; margin-bottom: 10;">
    <div style="border: solid 1px #dbdbdb; width: 100%;">
        <p style="margin: 0;"><font color="#dbdbdb">(備考)</font>{{ $note }}<p>
    </div>
    <div style="position:absolute; bottom: 0; left: 0; right: 0; text-align:center;"><img src="{{url('public/img/footer_logo.png')}}" height="30"></div>    
</body>
<?php
        //親子関係処理
        function child_range($i,$_question_code,$_parent_code,&$_ranges){

            $padding = 10;//ずらす幅

            if (!(isset($_ranges[$_question_code[$i]]))){
                if ($_parent_code[$i] == "なし"){
                    $_ranges[$_question_code[$i]] = 0;
                } elseif (isset($_ranges[$_parent_code[$i]])){
                    $_ranges[$_question_code[$i]] = $_ranges[$_parent_code[$i]] + $padding;
                } else {
                    $_ranges[$_question_code[$i]] = 0;
                }
            }
            return $_ranges[$_question_code[$i]];
        }
    ?>
</html>