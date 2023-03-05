<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>QUANTO</title>

    <style>
        body {
            margin: 0 !important;
            padding: 10px !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        .brand {
            margin: 40px auto !important;
        }

        .brand-logo img {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background-color: #ededed;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            border-radius: 100%;
        }

        .pro-card {
            background: #ededed;
            box-shadow: 5px 5px 10px rgb(0 0 0 / 30%);
            border-radius: 1em;
            margin: 15px auto;
            padding: 0;
            width: 100%;
            text-decoration: none;
            display: block;
        }

        .title {
            position: relative;
            width: 100%;
            padding: 15px;
            height: auto;
            min-height: 40px;
        }

        .image-holder {
            height: 300px;
        }

        .image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .total {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .total .view-card-btn {
            background-color: white;
            border-radius: 30px;
            color: #6859a3 !important;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
            font-size: 12px;

        }

        .total .total_result,
        .total .items_count {
            line-height: 38px;
        }

        .radius-top {
            border-top-left-radius: 1em;
            border-top-right-radius: 1em;
        }

        .radius-bottom {
            border-bottom-left-radius: 1em;
            border-bottom-right-radius: 1em;
        }

        .product-image {
            height: 450px;
        }

        .product-image-full {
            max-height: 450px;
        }

        .btn-close {
            opacity: 0.7;
        }

        .close {
            border: none;
            background-color: #fff;
            color: #000000;
            font-weight: 600;
            ;
            font-size: 20px;
        }

        .product-title {
            text-align: center;
            padding: 10px
        }

        .product-description {
            text-align: center;
            background: bisque;
            width: 200px;
            margin: 0 auto;
        }

        .counter {
            width: 113px;
            margin: 0 auto;
        }

        @media only screen and (max-width: 600px) {
            .pro-card {
                max-width: 100%;
            }

            .image-holder {
                height: 43.35vw;
            }

            .product-image {
                height: 300px;
            }
        }

        .tab {
            display: none;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 4px;
            margin-left: -2px;
            margin-right: -2px;
            background-color: #6962FF;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }

        /* 共通のスタイル */
        header {
            position: relative;
            width: 100%;
            min-height: 120px;
            padding: 0 10px;
            z-index: 999;
        }

        header .site-header .site-header-inner {
            position: relative;
            width: 1000px;
            margin: 0 auto;
            padding: 0;
        }

        @media only screen and (max-width: 1280px) {
            header .site-header .site-header-inner {
                width: 100%;
                padding: 0 10px;
            }
        }

        header .brand-wrapper {
            position: absolute;
            left: 70px;
            top: 0px;
            width: 80px;
            margin-right: 20px;
            text-align: center;
        }

        @media only screen and (max-width: 1280px) {
            header .brand-wrapper {
                position: relative;
                left: unset;
                top: unset;
                width: auto;
                text-align: left;
                margin: 0 0 30px;
            }
        }

        @media only screen and (max-width: 480px) {
            header .brand-wrapper {
                margin: 0 0 10px;
            }
        }

        header .brand-wrapper .brand {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            background-color: #ededed;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            background-position: center !important;
            border-radius: 100%;
        }

        header .brand-wrapper .brand-name {
            display: flex;
            justify-content: center;
            white-space: nowrap;
            font-size: 14px;
            line-height: 1.2;
            color: #000000;
        }

        header .brand-desc {
            width: -webkit-fit-content;
            width: -moz-fit-content;
            width: fit-content;
            margin: 0 auto 30px;
            padding: 10px 30px;
            text-align: center;
            font-size: 15px;
            line-height: 1.7;
            color: #000000;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);
        }


        header .btn-start {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 180px;
            height: 40px;
            margin: 0 auto 20px;
            font-size: 24px;
            font-weight: 700;
            background: #ffffff;
            border-radius: 25px;
            cursor: pointer;
            box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);
        }

        header .title-desc {
            margin: 70px 0 120px;
        }

        header .title-desc .title {
            margin: 0 auto 30px;
            text-align: center;
            font-size: 20px;
            color: #000000;
            max-width: 1000px;
        }

        header .title-desc .title span {
            display: inline-block;
            font-weight: 700;
            line-height: normal;
            width: auto;
            padding: 10px 30px;
            border-radius: 11px;
            background: #ffffff;
            box-shadow: 5px 5px 10px 0px rgba(0, 0, 0, 0.5);
        }

        header .title-desc .description {
            display: none;
            margin: 5px auto 0;
            max-width: 1000px;
            text-align: center;
            font-size: 20px;
            line-height: 1.3;
            color: #000000;
        }

        header .qrcode-area {
            text-align: center;
            margin-top: 70px;
            margin-top: 50px;
        }

        header .logo-img {
            width: 200px;
            margin: 100px auto 0px auto;
            position: fixed;
            bottom: 10px;
            left: calc(50% - 100px);
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 550px;
            }
        }

        @media only screen and (max-width: 480px) {
            header .brand-desc {
                width: 100%;
            }

            header .title-desc {
                margin: 50px 0 50px;
            }
        }

        .btn-quanto {
            color: #ffffff;
            background: #6962ff;
            border-color: #6962ff;
            border-radius: 30px;
            padding: 5px 15px
        }

        .btn-quanto:hover {
            color: #ffffff;
            background: #4c46d5;
            border-color: #4c46d5;
        }

        label {
            font-weight: 600;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
    <style>
        .change option {
            display: inline-block;
            padding: 5px;
            margin: 5px;
        }

        .table td,
        .table th {
            vertical-align: middle;
            min-width: 100px !important;
        }

        .check {
            background: red;
            color: #fff;
            padding: 1px 4px;
            margin-right: 5px;
        }

        .check-div {
            min-width: 40px;
            min-height: 20px;
            float: left;
            margin-right: 6px;
        }


        .circle {
            border: 1px solid black;
            height: 30px;
            width: 30px;
            padding-top: 2px;
            border-radius: 15px;
            margin: 0 auto;
        }

        textarea {
            resize: none;
            border: 1px solid #a9a6a6;
            background-color: #e8e8e9;
            font-size: 0.7rem;
            border-radius: 4px;
        }

        .accept-shop {
            background: #FFCCFF;
            padding: 3px 10px;
            width: 120px;
            border-radius: 4px;
        }

        .accept-delivary {
            background: #CCFFFF;
            padding: 3px 10px;
            width: 120px;
            border-radius: 4px;
        }

        .accept-post {
            background: beige;
            padding: 3px 10px;
            width: 120px;
            border-radius: 4px;
        }

        .nowrap {
            white-space: nowrap;
            overflow: hidden;
        }

        dl.search1 {
            position: relative;
            background-color: #fff;
            border: 1px solid #aaa;
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            -o-border-radius: 6px;
            -ms-border-radius: 6px;
            border-radius: 6px;
        }

        dl.search1 dt {
            margin-right: 40px;
            padding: 8px 0 8px 8px;
        }

        dl.search1 dt input {
            width: 100%;
            height: 24px;
            line-height: 24px;
            background: none;
            border: none;
            outline: none;
        }

        dl.search1 dd {
            position: absolute;
            top: 0;
            right: 0;
        }

        dl.search1 dd button {
            display: block;
            padding: 10px;
            background: none;
            border: none;
            outline: none;
        }

        dl.search1 dd button span {
            display: block;
            width: 20px;
            height: 20px;
            background: url('../public/img/search.png') no-repeat scroll -69px 0;
        }

        .load_content {
            cursor: pointer;
        }

        .load_comments {
            cursor: pointer;
        }

        .last-comment {
            cursor: pointer;
        }

        .modal-content {
            max-width: 800px;
        }

        .modal-body table {
            width: 90%;
            max-width: 600px;
            margin: 30px auto;
        }

        .modal-body table tr th,
        .modal-body table tr td {
            padding: 10px;
            border-bottom: 1px solid gray;
            min-width: 120px !important;
        }

        .modal-body table tr td p {
            margin-top: 1rem;
        }

        .modal-body table tr td textarea {
            resize: vertical;
            width: 100%;
            border: 1px solid #a9a6a6;
            background-color: #fff;
            font-size: 0.9rem;
            border-radius: 1px;
            margin-top: 5px;
            min-height: 80px;
            padding: 5px
        }

        .modal-body table tr td select {
            max-width: 150px;
        }

        h5 {
            font-size: 1.2rem;
        }
    </style>
</head>

<body style="background-color: <?php echo $query->background_color; ?>; color: #000000; min-height: 100vh">

    <div class="position-fixed" style="z-index: 1000;right:40px; top:30px">
        {{session('customer_name')}}さん　<a class="btn btn-quanto py-2 px-5" href="../show/{{ $query->token }}">マイページ</a>
    </div>
    <div class="container bg-white p-5 my-5 mx-auto border border-2" id="body-content" style="max-width:1200px; border-radius:30px; margin-top:100px !important">
        <div class="mt-2"">
            <div class=" row border-bottom mb-3">
            <div class="col-sm-4 py-2 px-4">
                <h5>お名前</h5>
            </div>
            <div class="col-sm-8 py-2 px-4">
                <h5>{{$customer->name}}</5>
            </div>
        </div>
        <div class="row border-bottom mb-3">
            <div class="col-sm-4 py-2 px-4">
                <h5>ご住所</h5>
            </div>
            <div class="col-sm-8 py-2 px-4">
                @if(!empty($address))
                <h5>{{$address->address}}{{$address->address2}}</5>
                    <h5>{{$address->address3}}</5>
                @else
                    <h5>未登録</5>
                @endif
            </div>
        </div>
        <div class="row border-bottom mb-3">
            <div class="col-sm-4 py-2 px-4">
                <h5>お電話番号</h5>
            </div>
            <div class="col-sm-8 py-2 px-4">
                @if(!empty($address))
                <h5>{{$address->phone}}</h5>
                @else
                <h5>未登録</5>
                @endif
            </div>
        </div>
        <div class="row border-bottom mb-3">
            <div class="col-sm-4 py-2 px-4">
                <h5>メール</h5>
            </div>
            <div class="col-sm-8 py-2 px-4">
                <h5>{{$customer->email}}</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 py-2 px-4">
                <h5>ステータス</h5>
            </div>
            <div class="col-sm-8 py-2 px-4">
                <h5>間もなく公開</h5>
            </div>
        </div>
    </div>
    </div>

    <div class="container bg-white p-5 my-5 mx-auto border border-2" id="body-content" style="max-width:1200px; border-radius:30px;">

        <form action="" name="search" method="GET">
            <div class="row">
                <div class="col-12 col-sm-8 col-md-5 col-lg-4">
                    <dl class="search1">
                        <dt><input type="text" name="search" value="{{ app('request')->input('search')}}" placeholder="検索" /></dt>
                        <dd><button><span></span></button></dd>
                    </dl>
                </div>
                <div class=" col-12 col-sm-4 col-md-4 col-lg-3">
                    <dl class="search1">
                        <dt><input type="text" name="range" id="datepicker" value="{{ app('request')->input('range')}}" placeholder="期間" /></dt>
                        <dd><button><span></span></button></dd>
                    </dl>
                </div>
            </div>
        </form>
        <div class="mt-2 pb-5" style="overflow-x: scroll; overflow-y:hidden">

            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center nowrap" style="width:100px !important;">ID</th>
                        <th class="text-center nowrap" style="width:50px !important;">注文日時</th>
                        <th class="text-center nowrap">タイトル</th>
                        <th class="text-center nowrap" style="width:50px !important;">点数</th>
                        <th class="text-center nowrap" style="width:120px !important;">注文金額(円)</th>
                        <th class="text-center nowrap" style="width:12px !important;">お支払い方法</th>
                        <th class="text-center nowrap" style="width:120px !important;">対応スタイル</th>
                        <th class="text-center nowrap" style="width:120px;">注文状況</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr data-survey="{{ $order->survey_title}}" data-id="{{$order->id}}" data-profile="{{($order->user_id + 1000)}}">
                        <td style="width:100px !important;" class="load_content nowrap" data-bs-toggle="modal" data-bs-target="#orderModal">
                            {{ 'Q' . ($order->user_id + 1000) }}-{{ $order->id }}
                        </td>
                        <td class="load_content text-center" data-bs-toggle="modal" data-bs-target="#orderModal">{{ date("m月d日",strtotime($order->created_at)) }}<br>{{ date("H:i",strtotime($order->created_at)) }}</td>
                        <td class="load_content nowrap" data-bs-toggle="modal" data-bs-target="#orderModal">{{ Str::substr($order->survey_title, 0, 20)}}・・・</td>
                        <td class="load_content text-center" data-bs-toggle="modal" data-bs-target="#orderModal">
                            <div class="circle">{{ $order->units }}</div>
                        </td>
                        <td class="load_content text-right pr-4 text-end" data-bs-toggle="modal" data-bs-target="#orderModal">{{ number_format($order->amount) }}円</td>
                        <td class="load_content text-center nowrap" data-bs-toggle="modal" data-bs-target="#orderModal">@if($order->payment_method==1)クレジットカード@endif @if($order->payment_method==2)銀行振り込み@endif @if($order->payment_method==3)代引き交換@endif @if($order->payment_method==4)店舗受け取り@endif</td>
                        <td class="load_content" data-bs-toggle="modal" data-bs-target="#orderModal">
                            @if($order->accept_method==1)<div class="accept-shop text-center"> 店舗受け取り </div>@endif @if($order->accept_method==2)<div class="accept-delivary text-center"> デリバリー </div>@endif @if($order->accept_method==3)<div class="accept-post text-center"> 通販 </div>@endif
                        </td>
                        <td class="load_content text-center" data-bs-toggle="modal" data-bs-target="#orderModal" id="accept-status-{{$order->id}}">
                            @if($order->accept_status==1)<span style="color:blue;">完了</span>
                            @elseif($order->accept_status==2)<span style="color:green">出荷済</span>
                            @elseif($order->accept_status==3)<span style="color:red">キャンセル</span>
                            @else処理中@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>

        <div class="position-fixed" style="z-index: 1000;right:40px; bottom:30px">
            <form action="{{ route('frontend.logout') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $query->token }}">
                <button type="submit" class="btn btn-quanto py-2 px-5">ログアウト</button>
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel"></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="orderId" id="orderId" value="">
                    <div class="modal-body">
                        <table>
                            <tr>
                                <th>
                                    タイトル
                                </th>
                                <td id="survey-title">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    対応スタイル
                                </th>
                                <td id="accept-method">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    お名前
                                </th>
                                <td id="name">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    メール
                                </th>
                                <td id="email">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    郵便番号
                                </th>
                                <td id="postcode">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    ご住所
                                </th>
                                <td id="address">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    電話番号
                                </th>
                                <td id="phone">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    商品
                                </th>
                                <td id="items">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    点数
                                </th>
                                <td id="units">

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    合計
                                </th>
                                <th id="total">

                                </th>
                            </tr>
                            <tr>
                                <th>
                                    注文状況
                                </th>
                                <td id="order-status">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script>
        $("#datepicker").flatpickr({
            mode: "range",
            minDate: "today",
            dateFormat: "Y-m-d",
            minDate: "2020-01-01",
            //disable: [
            //    function(date) {
            // disable every multiple of 8
            //  return !(date.getDate() % 8);
            //    }
            //],
            'locale': 'ja'
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".load_content").click(function() {
                $('#orderModalLabel').html('');
                $('#accept-method').html('');
                $('#name').html('');
                $('#email').html('');
                $('#postcode').html('');
                $('#address').html('');
                $('#phone').html('');
                $('#items').html('');
                $('#units').html('');
                $('#total').html('');
                $('#orderModalLabel').html('注文ID：' + 'Q' + $(this).parent().data("profile") + '-' + $(this).parent().data("id"));
                $("#survey-title").html($(this).parent().data("survey"))
                $('#orderId').val($(this).parent().data("id"));
                var order_id = $(this).parent().data("id");
                var data = {
                    'order_id': order_id
                };
                var url = '../customer/get';
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    dataType: 'json',
                    success: function(response) {
                        var accept_method = response.order.accept_method;
                        if (accept_method == 1) {
                            $('#accept-method').html('<div class="accept-shop text-center"> 店舗受け取り </div>');
                        }
                        if (accept_method == 2) {
                            $('#accept-method').html('<div class="accept-delivary text-center"> デリバリー </div>');
                        }
                        if (accept_method == 3) {
                            $('#accept-method').html('<div class="accept-post text-center"> 通販 </div>');
                        }
                        $('#name').html(response.order.name);
                        $('#email').html(response.order.email);
                        $('#postcode').html(response.order.postcode);
                        $('#address').html(response.order.address + response.order.address2 + response.order.address3);
                        $('#phone').html(response.order.phone);
                        $('#items').html(response.items);
                        $('#units').html(response.order.units);
                        $('#total').html(response.total);
                        if (response.order.accept_status == null) {
                            $('#order-status').html("処理中");
                        } else {
                            if (response.order.accept_status == 1) {
                                $('#order-status').html("処理中");
                            } else if (response.order.accept_status == 2) {
                                $('#order-status').html("出荷済");
                            } else if (response.order.accept_status == 3) {
                                $('#order-status').html("キャンセル");
                            } else {
                                $('#order-status').html("処理中");
                            }

                        }
                    },
                    error: function(response) {},
                });
            });
        });
    </script>

</body>

</html>