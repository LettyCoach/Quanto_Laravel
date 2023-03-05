@extends('layouts.admin', ['title' => '注文一覧'])
@section('js')
<script>
    $('#payment_status').change(function() {
        if ($(this).val() == 0) {
            $(this).css({
                'backgroundColor': '#FF0000',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 1) {
            $(this).css({
                'backgroundColor': '#6565FF',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 2) {
            $(this).css({
                'backgroundColor': '#FFA500',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 3) {
            $(this).css({
                'backgroundColor': '#FA72F4',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 4) {
            $(this).css({
                'backgroundColor': '#FEE38C',
                'color': '#000000'
            });
        }
    });
</script>
<script>
    $('#accept_status').change(function() {
        if ($(this).val() == 0) {
            $(this).css({
                'backgroundColor': '#FA72F4',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 1) {
            $(this).css({
                'backgroundColor': '#6565FF',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 2) {
            $(this).css({
                'backgroundColor': '#23C0C9',
                'color': '#ffffff'
            });
        }
        if ($(this).val() == 3) {
            $(this).css({
                'backgroundColor': '#FF0000',
                'color': '#ffffff'
            });
        }
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
            $('#orderModalLabel').html('注文ID：' + 'Q' + $(this).parent().data("profile") + '-' + $(this).parent().data("id") + '　　設問タイトル：' + $(this).parent().data("survey"));
            $('#orderId').val($(this).parent().data("id"));
            var order_id = $(this).parent().data("id");
            var data = {
                'order_id': order_id
            };
            var url = '<?php echo env('APP_URL')  ?>admin/orders/get';
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
                    $('#address').html(response.order.shipping_address + response.order.shipping_address2 + response.order.shipping_address3);
                    $('#phone').html(response.order.shipping_phone);
                    $('#items').html(response.items);
                    $('#units').html(response.order.units);
                    $('#total').html(response.total);
                    if (response.order.payment_status == null) {
                        $('#payment_status').val(0);
                    } else {
                        $('#payment_status').val(response.order.payment_status);
                    }
                    if (response.order.accept_status == null) {
                        $('#accept_status').val(0);
                    } else {
                        $('#accept_status').val(response.order.accept_status);
                    }
                    if (response.order.payment_status == 0) {
                        $('#pay-status').html('<span style="color:red"> </span><span style="color:red">NG</span>');
                        $('#payment_status').css({
                            'backgroundColor': '#FF0000',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.payment_status == 1) {
                        $('#pay-status').html('<span style="color:red">◎ </span><span style="color:#6565FF">承認済</span>');
                        $('#payment_status').css({
                            'backgroundColor': '#6565FF',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.payment_status == 2) {
                        $('#pay-status').html('<span style="color:red">● </span><span style="color:orange">対応中</span>');
                        $('#payment_status').css({
                            'backgroundColor': '#FFA500',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.payment_status == 3) {
                        $('#pay-status').html('<span style="color:gray">▲ </span><span>未対応</span>');
                        $('#payment_status').css({
                            'backgroundColor': '#FA72F4',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.payment_status == 4) {
                        $('#pay-status').html('<span style="color:red"> </span><span style="background:yellow; padding:2px">保留</span>');
                        $('#payment_status').css({
                            'backgroundColor': '#FEE38C',
                            'color': '#000000'
                        });
                    }
                    if (response.order.accept_status == 0) {
                        $('#accept_status').css({
                            'backgroundColor': '#FA72F4',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.accept_status == 1) {
                        $('#accept_status').css({
                            'backgroundColor': '#6565FF',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.accept_status == 2) {
                        $('#accept_status').css({
                            'backgroundColor': '#23C0C9',
                            'color': '#ffffff'
                        });
                    }
                    if (response.order.accept_status == 3) {
                        $('#accept_status').css({
                            'backgroundColor': '#FF0000',
                            'color': '#ffffff'
                        });
                    }
                },
                error: function(response) {},
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".load_comments").click(function() {
            $('#commentModalLabel').html('');
            $('#allComments').html('');
            $('#commentModalLabel').html('注文ID：' + 'Q' + $(this).parent().data("profile") + '-' + $(this).parent().data("id") + '　　設問タイトル：' + $(this).parent().data("survey"));
            $('#commentOrderId').val($(this).parent().data("id"));
            var order_id = $(this).parent().data("id");
            var data = {
                'order_id': order_id
            };
            var url = '<?php echo env('APP_URL')  ?>admin/comments/get';
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
                    if (response.status == 200) {
                        $('#allComments').html(response.list);
                    }
                },
                error: function(response) {},
            });
        });
    });
</script>
<script>
    $(document.body).on('change', '.change', function(e) {
        e.preventDefault();
        var order_id = $('#orderId').val();
        var data = {
            'order_id': order_id,
            'payment_status': $('#payment_status').val(),
            'accept_status': $('#accept_status').val(),
        };
        var url = '<?php echo env('APP_URL')  ?>admin/orders/update';
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
                if (response.order.payment_status == 0) {
                    $('#payment-status-' + order_id).html('<span style="color:red"> </span><span style="color:red">NG</span>');
                }
                if (response.order.payment_status == 1) {
                    $('#payment-status-' + order_id).html('<span style="color:red">◎ </span><span style="color:green">承認済</span>');
                }
                if (response.order.payment_status == 2) {
                    $('#payment-status-' + order_id).html('<span style="color:red">● </span><span style="color:orange">対応中</span>');
                }
                if (response.order.payment_status == 3) {
                    $('#payment-status-' + order_id).html('<span style="color:gray">▲ </span><span>未対応</span>');
                }
                if (response.order.payment_status == 4) {
                    $('#payment-status-' + order_id).html('<span style="color:red"> </span><span style="background:yellow; padding:2px">保留</span>');
                }
                if (response.order.accept_status == 0) {
                    $('#accept-status-' + order_id).html('未対応');
                }
                if (response.order.accept_status == 1) {
                    $('#accept-status-' + order_id).html('<span style="color:blue">完了</span>');
                }
                if (response.order.accept_status == 2) {
                    $('#accept-status-' + order_id).html('<span style="color:green">出荷済</span>');
                }
                if (response.order.accept_status == 3) {
                    $('#accept-status-' + order_id).html('<span style="color:red">キャンセル</span>');
                }
            },
            error: function(response) {},
        });
    });
</script>
<script>
    $(document.body).on('click', '#saveComment', function(e) {
        e.preventDefault();
        var order_id = $('#commentOrderId').val();
        var content = $('#newComment').val();
        var data = {
            'order_id': order_id,
            'content': content
        };
        var url = '<?php echo env('APP_URL')  ?>admin/comments/update';
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
                if (response.status == 200) {
                    var data = {
                        'order_id': order_id
                    };
                    var url = '<?php echo env('APP_URL')  ?>admin/comments/get';
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
                            if (response.status == 200) {
                                $('#comment-' + order_id).val(content.substring(0, 8) + '。。。');
                                $('#newComment').val("");
                                $('#allComments').html(response.list);
                            }
                        },
                        error: function(response) {},
                    });
                }
            },
            error: function(response) {},
        });
    });
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
@endsection
@section('main-content')
<div class="mt-2 pb-5" style="overflow-x: scroll; overflow-y:hidden">

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


    <table class="table">
        <thead>
            <tr>
                <th class="text-center nowrap" style="min-width:160px !important;">ID</th>
                <th class="text-center nowrap">注文日時</th>
                <th class="text-center nowrap">引き取り日時</th>
                <th class="text-center nowrap">対応スタイル</th>
                <th class="text-cente nowrap">お名前</th>
                <th class="text-center nowrap">設問タイトル</th>
                <th class="text-center nowrap">点数</th>
                <th class="text-center nowrap">注文金額(円)</th>
                <th class="text-center nowrap" style="width:130px;">決済方法</th>
                <th class="text-center nowrap" style="width:130px;">注文ステータス</th>
                <th class="text-center nowrap" style="width:130px;">商品受渡し状況</th>
                <th class="text-center nowrap" style="width:130px;">メモ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr data-survey="{{ $order->survey_title}}" data-id="{{$order->id}}" data-profile="{{($order->user_id + 1000)}}">
                <td style="min-width:160px !important;" class="load_content nowrap" data-toggle="modal" data-target="#orderModal">
                    <div class="check-div">@if($order->seen==0)<span class="check">New</span>@endif</div> {{ 'Q' . ($order->user_id + 1000) }}-{{ $order->id }}
                </td>
                <td class="load_content text-center" data-toggle="modal" data-target="#orderModal">{{ date("m月d日",strtotime($order->created_at)) }}<br>{{ date("H:i",strtotime($order->created_at)) }}</td>
                <td class="load_content text-center" data-toggle="modal" data-target="#orderModal">@if($order->accept_method !=3) {{ date("m月d日",strtotime($order->accept_time)) }}<br>{{ date("H:i",strtotime($order->accept_time)) }} @endif</td>
                <td class="load_content" data-toggle="modal" data-target="#orderModal">
                    @if($order->accept_method==1)<div class="accept-shop text-center"> 店舗受け取り </div>@endif @if($order->accept_method==2)<div class="accept-delivary text-center"> デリバリー </div>@endif @if($order->accept_method==3)<div class="accept-post text-center"> 通販 </div>@endif
                </td>
                <td class="load_content nowrap" data-toggle="modal" data-target="#orderModal">{{ $order->name }}</td>
                <td class="load_content nowrap" data-toggle="modal" data-target="#orderModal">{{ Str::substr($order->survey_title, 0, 10)}}・・・</td>
                <td class="load_content text-center" data-toggle="modal" data-target="#orderModal">
                    <div class="circle">{{ $order->units }}</div>
                </td>
                <td class="load_content text-right pr-4" data-toggle="modal" data-target="#orderModal">{{ number_format($order->amount) }}円</td>
                <td class="load_content text-center nowrap" data-toggle="modal" data-target="#orderModal">@if($order->payment_method==1)クレジットカード@endif @if($order->payment_method==2)銀行振り込み@endif @if($order->payment_method==3)代引き交換@endif @if($order->payment_method==4)店舗受け取り@endif</td>
                <td class="load_content text-center" data-toggle="modal" data-target="#orderModal" id="payment-status-{{$order->id}}">
                    @if($order->payment_status==1)<span style="color:red">◎ </span><span style="color:green">承認済</span>
                    @elseif($order->payment_status==2)<span style="color:red">● </span><span style="color:orange">対応中</span>
                    @elseif($order->payment_status==3)<span style="color:gray">▲ </span><span>未対応</span>
                    @elseif($order->payment_status==4)<span style="color:red"> </span><span style="background:yellow; padding:2px">保留</span>
                    @else <span style="color:red">NG</span>@endif
                </td>
                <td class="load_content text-center" data-toggle="modal" data-target="#orderModal" id="accept-status-{{$order->id}}">
                    @if($order->accept_status==1)<span style="color:blue;">完了</span>
                    @elseif($order->accept_status==2)<span style="color:green">出荷済</span>
                    @elseif($order->accept_status==3)<span style="color:red">キャンセル</span>
                    @else未対応@endif
                </td>
                <td class="load_comments" data-toggle="modal" data-target="#commentModal"><textarea class="last-comment" disabled id="comment-{{$order->id}}">@if(!empty($order->comment)) {{ Str::substr($order->comment, 0, 8)}}・・・ @endif</textarea></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" name="orderId" id="orderId" value="">
            <div class="modal-body">
                <table>
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
                            メールアドレス、
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
                            注文ステータス
                        </th>
                        <td>
                            @if(Auth::user()->isAdmin())
                            <select class="form-control change" name="payment_status" id="payment_status">
                                <option value="1" style="color:white; background-color:#6565FF;">承認済</option>
                                <option value="2" style="color:white; background-color:#FFA500;">対応中</option>
                                <option value="3" style="color:white; background-color:#FA72F4;">未対応</option>
                                <option value="4" style="background-color:#FEE38C; color:black;">保留</option>
                                <option value="0" style="color:white; background-color:#FF0000;">NG</option>
                            </select>
                            @else
                            <div id="pay-status"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            商品受渡し状況
                        </th>
                        <td>
                            <select class="form-control change" name="accept_status" id="accept_status">
                                <option value="1" style="color:white; background-color:#6565FF;">完了</option>
                                <option value="2" style="color:white; background-color:#23C0C9;">出荷済</option>
                                <option value="0" style="color:white; background-color:#FA72F4;">未対応</option>
                                <option value="3" style="color:white; background-color:#FF0000;">キャンセル</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" name="commentOrderId" id="commentOrderId" value="">
            <div class="modal-body">
                <div id="allComments">

                </div>
                <div class="col-12 py-3">
                    <h4 class="text-center mb-3">メモ</h4>
                    <div class="col-12">
                        <textarea class="form-controller bg-white col-12" id="newComment" rows=10 style="font-size:1.2rem" placeholder="こちらから入力してください。"></textarea>
                    </div>


                </div>
            </div>
            <div class="modal-footer text-center d-block">
                <button class="btn btn-quanto" id="saveComment">保存する</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .btn-quanto {
        color: #ffffff;
        background: #6962ff;
        border-color: #6962ff;
    }

    .btn-quanto:hover {
        color: #ffffff;
        background: #4c46d5;
        border-color: #4c46d5;
    }

    .change option {
        display: inline-block;
        padding: 5px;
        margin: 5px;
    }

    .table td,
    .table th {
        vertical-align: middle;
        min-width: 100px;
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
        padding-top: 4px;
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
        min-width: 100px;
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
</style>
@endsection