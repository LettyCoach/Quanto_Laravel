<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>QUANTO</title>

    <style>
        body {
            margin: 0 !important;
            padding: 0 !important;
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
            border-radius: 10px;
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
            min-height: 80px;
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

        .product-image-full {
            width: 100%;
            height: 300px;
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
                height: 180px;
            }
        }

        label {
            font-weight: 600;
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

        .btn-outline-quanto {
            color: #000000;
            border-radius: 5px;
            padding: 5px 15px;
            border: solid 2px #3a7ad9;
        }

        .btn-outline-quanto:hover {
            border: solid 2px #1155bb;
        }

        .selectDelivery {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        .selectDelivery+div {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        .selectDelivery:checked+div {
            outline: 2px solid #1155bb;
        }

        .selectDelivery:focus+div {
            outline: 2px solid #1155bb;
        }

        .flatpickr-input[readonly] {
            background: #ffffff;
        }

        input[type='radio'] {
            transform: scale(2);
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
</head>

<body style="background-color:  <?php echo $query->background_color; ?>; min-height: 100vh">

    <div class="position-fixed" style="z-index: 1000;right:40px; top:30px">
        <a class="btn btn-quanto py-3" style="padding-left:4.5rem; padding-right:4.5rem;" href="mypage/{{ $query->token }}">{{session('customer_name')}}さん</a>
    </div>
    <div class="container" style="max-width:1200px;">
        <div class="row">
            <div class="col brand">
                <div class="brand-logo text-center">
                    <img src="<?php echo $query->profile_path; ?>" alt="brand-logo">
                </div>
                <div class="brand-name text-center text-dark fw-5">{{$query->title}}</div>
            </div>
        </div>

        <div class="row py-5">

            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="card mb-5" style="border-radius:13px;">
                    <div class="card-header text-center" style="background:#DAE3F3;border-top-left-radius:13px;border-top-right-radius:13px;">
                        <h4>ご注文内容</h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="row cart-item-list p-3" id="item-list" style="margin: 0 auto;color: black; ">

                            <div class="col">
                                <?php
                                $units = 0;
                                if ($session_cart) {
                                    foreach ($session_cart as $key => $each) {
                                        if ($each['quantity'] > 0) {
                                            $units += $each['quantity'];
                                ?>

                                            <div class="row">
                                                <div class="col">
                                                    {!! $each['products'] !!} x {{$each['quantity']}}
                                                </div>
                                                <div class="col">


                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="update-quantity-left-minus btn btn-outline-primary btn-number" data-type="minus" data-field="" data-key="{{$each['id']}}">
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                            </button>
                                                        </span>
                                                        <input type="text" id="quantity_{{$each['id']}}" name="quantity_{{$each['id']}}" class="form-control input-number" value="{{$each['quantity']}}" min="1" max="100" style="text-align: center">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="update-quantity-right-plus btn btn-outline-primary btn-number" data-type="plus" data-field="" data-key="{{$each['id']}}">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>

                                            <hr>
                                        <?php }
                                    }
                                    if ($units < 1) {
                                        ?>
                                        <script>
                                            window.location.href = "show/<?php echo $query->token; ?>";
                                        </script>
                                <?php
                                    }
                                }
                                ?>

                                <div class="row text-center item-line" style="max-width: 450px; margin: 0 auto; background-color: #6962FF; padding: 12px; border-radius: 13px">
                                    <div class="col text-light d-none d-sm-block">トータル</div>
                                    <div class="col">
                                        <div class="items_count text-light text-nowrap" id="items_count">
                                            <?php
                                            echo $units . ' 点';
                                            //if ($session_cart) {
                                            //echo  count($session_cart) . ' 点';
                                            //} else {
                                            //echo 0 . ' 点';
                                            //}
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col">

                                    </div>
                                    <div class="col">
                                        <div class="total_result text-light text-nowrap" id="total_result">
                                            <?php
                                            if ($session_cart) {
                                                $total = 0;
                                                foreach ($session_cart as $key => $count) {
                                                    $total += ($count['price'] * $count['quantity']);
                                                }
                                                echo number_format($total) . ' 円';
                                            } else {
                                                echo 0 . ' 円';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                    @csrf
                    @if (Session::has('error_message'))
                    <script>
                        $(document).ready(function() {
                            $('html,body').animate({
                                scrollTop: $("#creditCard").offset().top
                            });
                        });
                    </script>
                    @endif
                    @php
                    $surveySettings = isset($query['settings']) ? json_decode($query['settings']) : [];
                    $delivery_methods = isset($surveySettings->delivery_methods) ? json_decode($surveySettings->delivery_methods) : array();
                    $pay_methods = isset($surveySettings->pay_methods) ? json_decode($surveySettings->pay_methods) : array();
                    @endphp
                    @if(count($delivery_methods)>0)
                    <div class="card mb-5" style="border-radius:13px;">
                        <div class="card-header text-center position-relative" style="background:#DAE3F3;border-top-left-radius:13px;border-top-right-radius:13px;">
                            <h4>受け取り方法</h4>
                        </div>
                        <div class="card-body p-5">
                            <div class="row d-flex justify-content-center">
                                @if ( in_array('店舗受取り' ,$delivery_methods)==true)
                                <div class="col-6 col-sm-4 text-center my-2">
                                    <label>
                                        <input type="radio" class="selectDelivery required" name="delivery_method" value="1" @if(count($delivery_methods)==1) checked @endif>
                                        <div class="shadow-lg p-4" style="width:120px; border-radius:10px;">
                                            <span>Pick Up</span>
                                            <div class="shadow-lg p-1 mt-2" style=" border-radius:10px;">
                                                <div class="w-100">
                                                    <img src="public/img/shop_icon.png" style="height:60px" alt="店舗受け取り">
                                                </div>
                                                <span style="font-size: 8px;">ピックアップ</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endif
                                @if ( in_array('デリバリー' ,$delivery_methods)==true)
                                <div class="col-6 col-sm-4 text-center my-2">
                                    <label>
                                        <input type="radio" class="selectDelivery required" name="delivery_method" value="2" @if(count($delivery_methods)==1) checked @endif>
                                        <div class="shadow-lg p-4" style="width:120px; border-radius:10px;">
                                            <span>Delivery</span>
                                            <div class="shadow-lg p-1 mt-2" style=" border-radius:10px;">
                                                <div class="w-100">
                                                    <img src="public/img/bike_icon.png" style="margin-top:5px; margin-bottom:5px; height:50px" alt="デリバリー">
                                                </div>
                                                <span style="font-size: 8px;">デリバリー</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endif
                                @if ( in_array('通販' ,$delivery_methods)==true)
                                <div class="col-6 col-sm-4 text-center my-2">
                                    <label>
                                        <input type="radio" class="selectDelivery required" name="delivery_method" value="3" @if(count($delivery_methods)==1) checked @endif>
                                        <div class="shadow-lg p-4" style="width:120px; border-radius:10px;">
                                            <span>Ecommerce</span>
                                            <div class="shadow-lg p-1 mt-2" style=" border-radius:10px;">
                                                <div class="w-100">
                                                    <img src="public/img/cart_icon.png" style=" height:60px" alt="通販">
                                                </div>
                                                <span style="font-size: 8px;">通販</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @endif
                            </div>
                            <div class="form-group row py-2" id="dateTime" @if(count($delivery_methods)==1 && in_array('通販' ,$delivery_methods)==false) style="display: flex;" @else style="display: none;" @endif>
                                <label for="email" class="col-12 col-form-label text-center my-3">ご希望のお時間帯をお選びください。</label>
                                <div class="col-6">
                                    <input type="text" class="form-control" id="date" style="width:140px; float:right;" placeholder="日付" name="date" value="{{  old('date') }}">
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" style="width:140px;" id="time" name="time" value="{{  old('time') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card mb-5" style="border-radius:13px;">
                        <div class="card-header text-center position-relative" style="background:#DAE3F3;border-top-left-radius:13px;border-top-right-radius:13px;">
                            <h4>お客様情報</h4>
                            @if(count($delivery_methods)==1 && in_array('店舗受取り' ,$delivery_methods)==true)
                            @else
                            <button type="button" class="btn btn-outline-quanto position-absolute" style="right:10px; top:10px" id="shippingAddress">発送先情報</button>
                            @endif
                        </div>
                        <div class="card-body p-5">
                            <div class="form-group row py-2">
                                <label for="email" class="col-sm-3 col-form-label">メールアドレス</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="test@mail.com" required value="{{  $customer->email }}">
                                </div>
                            </div>

                            <div class="form-group row py-2">
                                <label for="name" class="col-sm-3 col-form-label">お名前</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="山田 太郎" required value="{{ $customer->name }}">
                                </div>
                            </div>

                            <div class="form-group row py-2 mb-5">
                                <label for="name" class="col-sm-3 col-form-label">フリガナ</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kana" id="kana" placeholder="ヤマダタロウ" required value="{{ $customer->kana }}">
                                </div>
                            </div>
                            @if(count($addresses) > 0)
                            @foreach ($addresses as $address)
                            @if($address->type==1)
                            <div class="form-check mb-5 d-flex pe-2">
                                <div class="me-2">
                                    <input class="form-check-billing" type="radio" name="billingAddress" value="{{ $address->id }}" @if ($address->status==1) checked @endif >
                                </div>
                                <div>
                                    <label class="form-check-label ms-3">
                                        {{ $address->address }}{{ $address->address2 }}{{ $address->address3 }}
                                    </label>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            <div class="form-check mb-5 d-flex pe-2">
                                <div class="me-2">
                                    <input class="form-check-billing" type="radio" name="billingAddress" value="0" id="newBillingAddress">
                                </div>
                                <div>
                                    <label class="form-check-label ms-3">
                                        新規ご請求先を追加する
                                    </label>
                                </div>
                            </div>
                            @endif
                            <div id="billingDetails" @if(count($addresses)> 0) style="display: none;" @else style="display: block;" @endif>
                                <div class="form-group row py-2">
                                    <label for="postcode" class="col-sm-3 col-form-label">郵便番号</label>
                                    <div class="col-sm-9 d-flex">
                                        <input type="text" class="form-control d-inline me-2" style="width:55px; margin-top:2px" pattern="[0-9]{3,3}" maxlength="3" name="postcodeFirst" id="postcodeFirst" placeholder="111" value="{{old('postcodeFirst')}}" @if(count($addresses) < 1) required @endif>
                                        <input type="text" class="form-control d-inline me-3" style="width:65px; margin-top:2px" pattern="[0-9]{4,4}" maxlength="4" name="postcodeLast" id="postcodeLast" placeholder="1111" value="{{old('postcodeLast')}}" @if(count($addresses) < 1) required @endif>
                                        <button type="button" class="btn btn-outline-quanto d-inline mt-0" id="postcodeSearch">検索する</button>
                                    </div>
                                </div>

                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label">住所</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="address" id="address" placeholder="都道府県 市区町村" value="{{old('address')}}" @if(count($addresses) < 1) required @endif>
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="address2" id="address2" placeholder="番地・部屋番号など" value="{{old('address2')}}" @if(count($addresses) < 1) required @endif>
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="address3" id="address3" placeholder="建物名・マンション名（任意）" value="{{old('address3')}}">
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="cell" class="col-sm-3 col-form-label">電話番号</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cell" name="phone" placeholder="03-1234-5678" value="{{old('phone')}}" @if(count($addresses) < 1) required @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5" style="border-radius:13px; display:none;" id="shippingDetails">
                        <div class="card-header text-center position-relative" style="background:#DAE3F3;border-top-left-radius:13px;border-top-right-radius:13px;">
                            <h4>発送先情報</h4>
                        </div>
                        <div class="card-body p-5">
                            <div class="form-check mb-5 d-flex pe-2">
                                <div class="me-2">
                                    <input class="form-check-shipping" type="radio" name="shippingAddress" value="same" checked>
                                </div>
                                <div>
                                    <label class="form-check-label ms-3">
                                        ご注文者と同じ
                                    </label>
                                </div>
                            </div>
                            @foreach ($addresses as $address)
                            @if($address->type == 2)
                            <div class="form-check mb-5 d-flex pe-2">
                                <div class="me-2">
                                    <input class="form-check-shipping" type="radio" name="shippingAddress" value="{{ $address->id }}">
                                </div>
                                <div>
                                    <label class="form-check-label ms-3">
                                        {{ $address->address }}{{ $address->address2 }}{{ $address->address3 }}
                                    </label>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            <div class="form-check mb-5 d-flex pe-2">
                                <div class="me-2">
                                    <input class="form-check-shipping" type="radio" name="shippingAddress" value="0" id="newAddress">
                                </div>
                                <div>
                                    <label class="form-check-label ms-3">
                                        新規お届け先を追加する
                                    </label>
                                </div>
                            </div>
                            <div id="newAddressDetails" style="display: none;">
                                <div class="form-group row py-2">
                                    <label for="postcode" class="col-sm-3 col-form-label">郵便番号</label>
                                    <div class="col-sm-9 d-flex">
                                        <input type="text" class="form-control d-inline me-2" style="width:55px; margin-top:2px" pattern="[0-9]{3,3}" maxlength="3" name="postcodeFirstNew" id="postcodeFirstNew" placeholder="111" value="{{old('postcodeFirstNew')}}">
                                        <input type="text" class="form-control d-inline me-3" style="width:65px; margin-top:2px" pattern="[0-9]{4,4}" maxlength="4" name="postcodeLastNew" id="postcodeLastNew" placeholder="1111" value="{{old('postcodeLastNew')}}">
                                        <button type="button" class="btn btn-outline-quanto d-inline mt-0" id="postcodeSearchNew">検索する</button>
                                    </div>
                                </div>

                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label">住所</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressNew" id="addressNew" placeholder="都道府県 市区町村" value="{{old('addressNew')}}">
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressNew2" id="addressNew2" placeholder="番地・部屋番号など" value="{{old('addressNew2')}}">
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="address" class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="addressNew3" id="addressNew3" placeholder="建物名・マンション名（任意）" value="{{old('addressNew3')}}">
                                    </div>
                                </div>
                                <div class="form-group row py-2">
                                    <label for="name" class="col-sm-3 col-form-label">お名前</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="nameNew" id="nameNew" placeholder="山田 太郎" value="{{ old('nameNew') }}">
                                    </div>
                                </div>

                                <div class="form-group row py-2">
                                    <label for="name" class="col-sm-3 col-form-label">フリガナ</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="kanaNew" id="kanaNew" placeholder="ヤマダタロウ" value="{{ old('kanaNew') }}">
                                    </div>
                                </div>

                                <div class="form-group row py-2">
                                    <label for="cell" class="col-sm-3 col-form-label">ご連絡先</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="cellNew" name="phoneNew" placeholder="03-1234-5678" value="{{old('phoneNew')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5" style="border-radius:13px;">
                        <div class="card-header text-center position-relative" style="background:#DAE3F3;border-top-left-radius:13px;border-top-right-radius:13px;">
                            <h4>お支払い情報</h4>
                            @if(count($pay_methods)>1 && in_array('クレジットカード' ,$pay_methods)==true)
                            <button type="button" class="btn btn-outline-quanto position-absolute" id="changeMethod" style="right:10px; top:10px">変更する</button>
                            @endif
                        </div>
                        <div class="card-body p-5">
                            @if (in_array('クレジットカード' ,$pay_methods)==true)
                            <div id="creditCard">
                                @foreach ($cards as $card)
                                <div class="form-check mb-5">
                                    <input class="form-check-card" type="radio" name="card" value="{{ $card->id }}" @if ($card->status==1) checked @endif >
                                    <label class="form-check-label ms-3">
                                        XXXX-XXXX-XXXX-{{ $card->last }} {{ $card->expiry }}
                                    </label>
                                </div>
                                @endforeach
                                <div @if(count($cards) < 1) style="display: none;" @else style="display: block;" @endif>
                                    <div class="form-check mb-5">
                                        <input class="form-check-card" type="radio" name="card" value="0" id="newCard">
                                        <label class="form-check-label ms-3">
                                            新規カード登録
                                        </label>
                                    </div>
                                </div>
                                <div id="cardDetails" @if(count($cards)> 0) style="display: none;" @else style="display: block;" @endif>
                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>カード名義</label>
                                            <input name="card_name" class='form-control' size='4' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>カード番号</label> <input autocomplete='off' class='form-control card-number' size='20' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>セキュリティーコード</label> <input autocomplete='off' type="password" class='form-control card-cvc' placeholder='例） 311' size='4' type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>有効期限（月）</label> <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>有効期限（年）</label> <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="mt-5 mb-2" id="payMethods" @if(in_array('クレジットカード' ,$pay_methods)==true) style="display: none;" @else style="display: block;" @endif>
                                @foreach ($pay_methods as $pay_method)
                                <div class="form-check mb-5">
                                    <input class="form-check-method" type="radio" name="pay_method" value="{{ $pay_method }}">
                                    <label class="form-check-label ms-3">
                                        {{ $pay_method }}
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <div class='form-row row'>
                                <div class='col-md-12 error form-group hide'>
                                    <div class='alert-danger alert'>お支払い情報を確認の上再入力してください。</div>
                                </div>
                            </div>
                            @if (Session::has('error_message'))
                            <div class="col-12 my-4" id="errorBox">
                                <div class="card border border-2 p-5 text-center" style="border-radius:15px;">
                                    <div>
                                        <div class="mx-auto mb-3" style="border-radius:50%; background:#fd0e0e; color:#ffffff;font-weight:600; font-size:24px; width:40px; height:40px;padding-top:4px">X</div>
                                    </div>
                                    <h4 class="mb-1">何らか事情で決済が出来ませんでした。</h4>
                                    <h4 class="mb-1">お手数ですが、</h4>
                                    <h4 class="mb-1">再度お手続きをお願い致します。</h4>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-12 text-center mt-5">
                                    <input type="hidden" name="pay_total" id="pay_total" value="<?php if ($session_cart) {
                                                                                                    $total = 0;
                                                                                                    foreach ($session_cart as $key => $count) {
                                                                                                        $total += ($count['price'] * $count['quantity']);
                                                                                                    }
                                                                                                    echo $total;
                                                                                                } else {
                                                                                                    echo 0;
                                                                                                } ?>">
                                    <button class="btn btn-quanto px-5 py-3" type="submit">ご注文を確定する<span class="d-none" disabled>
                                            (
                                            <?php
                                            if ($session_cart) {
                                                $total = 0;
                                                foreach ($session_cart as $key => $count) {
                                                    $total += ($count['price'] * $count['quantity']);
                                                }
                                                echo '<span id="totalAmount">' . $total . '</span>';
                                            } else {
                                                echo '<span id="totalAmount">' . 0 . '</span>';
                                            }
                                            ?>
                                            ) 円</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>




    </div>








    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        $("#creditCard").click(function() {
            $("#errorBox").hide();
        });
        $("#payMethods").click(function() {
            $("#errorBox").hide();
        });
    </script>
    <script>
        var quantitiy = 0;
        $(document.body).on('click', '.update-quantity-right-plus', function(e) {
            e.preventDefault();
            var item_id = $(this).data("key");
            var quantity = parseInt($('#quantity_' + item_id).val());
            $('#quantity_' + item_id).val(quantity + 1);
            var data = {
                'product_id': item_id,
                'survey_id': <?php echo $query->id; ?>,
                'product_q': quantity + 1,
            };
            var url = 'update';
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
                    $('#pay_total').val(response.cart);
                    $('#totalAmount').html(response.cart);
                    $('#item-list').html(response.list);
                },
                error: function(response) {

                },
            });
        });

        $(document.body).on('click', '.update-quantity-left-minus', function(e) {
            e.preventDefault();
            var item_id = $(this).data("key");
            var quantity = parseInt($('#quantity_' + item_id).val());
            if (quantity > 0) {
                $('#quantity_' + item_id).val(quantity - 1);

                var data = {
                    'product_id': item_id,
                    'survey_id': <?php echo $query->id; ?>,
                    'product_q': quantity - 1,
                };
                var url = 'update';
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
                        if (response.units > 0) {
                            $('#pay_total').val(response.cart);
                            $('#totalAmount').html(response.cart);
                            $('#item-list').html(response.list);
                        } else {
                            window.location.href = "show/<?php echo $query->token; ?>";
                        }
                    },
                    error: function(response) {

                    },
                });

            }
        });
    </script>

    <script>
        $(document.body).on('click', '#postcodeSearch', function(e) {
            e.preventDefault();
            var data = {
                'postcodeFirst': $('#postcodeFirst').val(),
                'postcodeLast': $('#postcodeLast').val(),
            };
            var url = 'customer/postcode';
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
                        $('#address').val(response.prefecture + response.city + response.street);
                    } else {
                        $('#postcodeError').show();
                    }

                },
                error: function(response) {},
            });
        });
    </script>
    <script>
        $(document.body).on('click', '#postcodeSearchNew', function(e) {
            e.preventDefault();
            var data = {
                'postcodeFirst': $('#postcodeFirstNew').val(),
                'postcodeLast': $('#postcodeLastNew').val(),
            };
            var url = 'customer/postcode';
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
                        $('#addressNew').val(response.prefecture + response.city + response.street);
                    } else {
                        $('#postcodeError').show();
                    }

                },
                error: function(response) {},
            });
        });
    </script>
    <script>
        $(document.body).on('click', '.selectDelivery', function() {

            if ($(this).val() == 1) {
                $('#shippingDetails').hide();
            }
            if ($(this).val() < 3) {
                $('#dateTime').show();
            } else {
                $('#dateTime').hide();
            }
            if ($(this).val() < 2) {
                $('#shippingAddress').hide();
            } else {
                $('#shippingAddress').show();
            }
        });
    </script>
    <script>
        $(document.body).on('click', '#shippingAddress', function() {
            if ($('#shippingDetails').is(":visible") == true) {
                $('#shippingDetails').hide();
            } else {
                $('#shippingDetails').show();
                $('html, body').animate({
                    scrollTop: $("#shippingDetails").offset().top
                }, 1000);
            }

        });
    </script>
    <script>
        $(document.body).on('click', '.form-check-billing', function() {
            if ($('#newBillingAddress').is(":checked") == true) {
                $('#billingDetails').show();
                $("#postcodeFirst").prop('required', true);
                $("#postcodeLast").prop('required', true);
                $("#address").prop('required', true);
                $("#address2").prop('required', true);
                $("#cell").prop('required', true);
            } else {
                $('#billingDetails').hide();
                $("#postcodeFirst").prop('required', false);
                $("#postcodeLast").prop('required', false);
                $("#address").prop('required', false);
                $("#address2").prop('required', false);
                $("#cell").prop('required', false);
            }
        });
    </script>
    <script>
        $(document.body).on('click', '.form-check-shipping', function() {
            if ($('#newAddress').is(":checked") == true) {
                $('#newAddressDetails').show();
                $("#postcodeFirstNew").prop('required', true);
                $("#postcodeLastNew").prop('required', true);
                $("#addressNew").prop('required', true);
                $("#addressNew2").prop('required', true);
                $("#nameNew").prop('required', true);
                $("#kanaNew").prop('required', true);
                $("#cellNew").prop('required', true);
            } else {
                $('#newAddressDetails').hide();
                $("#postcodeFirst").prop('required', false);
                $("#postcodeLast").prop('required', false);
                $("#address").prop('required', false);
                $("#address2").prop('required', false);
                $("#nameNew").prop('required', false);
                $("#kanaNew").prop('required', false);
                $("#cellNew").prop('required', false);
            }
        });
    </script>
    <script>
        $(document.body).on('click', '.form-check-card', function() {
            if ($('#newCard').is(":checked") == true) {
                $('#cardDetails').show();
            } else {
                $('#cardDetails').hide();
            }
        });
    </script>
    <script>
        $(document.body).on('click', '#changeMethod', function() {
            $('#creditCard').hide();
            $('#changeMethod').hide();
            $('#payMethods').show();
            $('.error')
                .addClass('hide')
                .find('.alert')
                // .text(response.error.message);
                .text("");
        });
    </script>
    <script>
        $(document.body).on('click', '.form-check-method', function() {
            if ($(this).val() == 'クレジットカード') {
                $('#payMethods').hide();
                $('#creditCard').show();
                $('#changeMethod').show();
                $(this).prop('checked', false);
                $('.form-check-card').prop('checked', false);
                $('.error')
                    .addClass('hide')
                    .find('.alert')
                    // .text(response.error.message);
                    .text("");
            } else {
                $('#creditCard').hide();
                $('#changeMethod').hide();
                $('#payMethods').show();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
    <script>
        $("#date").flatpickr({
            minDate: "today",
            defaultDate: "today",
            dateFormat: "Y年m月d日",
            'locale': 'ja'
        });
    </script>
    <script>
        $("#time").flatpickr({
            noCalendar: true,
            enableTime: true,
            defaultDate: new Date().setHours(new Date().getHours() + 1),
            dateFormat: "H時i分",
            'locale': 'ja'
        });
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LezQzwjAAAAAMcKp4NYwErVAwFIHLiWFx1qWTJT"></script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function() {

            /*------------------------------------------
            --------------------------------------------
            Stripe Payment Code
            --------------------------------------------
            --------------------------------------------*/

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('6LezQzwjAAAAAMcKp4NYwErVAwFIHLiWFx1qWTJT', {
                        action: 'submit'
                    }).then(function(token) {
                        if (!$("input[name='delivery_method']:checked").val()) {
                            $('.error')
                                .removeClass('hide')
                                .find('.alert')
                                // .text(response.error.message);
                                .text("受け取り方法選択してください。");
                        } else {

                            if ($('#cardDetails').is(":visible") == true) {
                                var $form = $(".require-validation"),
                                    inputSelector = ['input[type=email]', 'input[type=password]',
                                        'input[type=text]', 'input[type=file]', 'input[type=radio]',
                                        'textarea'
                                    ].join(', '),
                                    $inputs = $form.find('.required').find(inputSelector),
                                    $errorMessage = $form.find('div.error'),
                                    valid = true;
                                $errorMessage.addClass('hide');

                                $('.has-error').removeClass('has-error');
                                $inputs.each(function(i, el) {
                                    var $input = $(el);
                                    if ($input.val() === '') {
                                        $input.parent().addClass('has-error');
                                        $errorMessage.removeClass('hide');
                                        e.preventDefault();
                                    }
                                });

                                if (!$form.data('cc-on-file')) {
                                    e.preventDefault();
                                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                                    Stripe.createToken({
                                        number: $('.card-number').val(),
                                        cvc: $('.card-cvc').val(),
                                        exp_month: $('.card-expiry-month').val(),
                                        exp_year: $('.card-expiry-year').val()
                                    }, stripeResponseHandler);
                                }
                            } else {
                                if (!$("input[name='pay_method']:checked").val()) {
                                    if (!$("input[name='card']:checked").val()) {
                                        $('.error')
                                            .removeClass('hide')
                                            .find('.alert')
                                            // .text(response.error.message);
                                            .text("お支払い方法選択してください。");
                                    } else {
                                        $('.error')
                                            .addClass('hide')
                                            .find('.alert')
                                            // .text(response.error.message);
                                            .text("");
                                        $('#payment-form').get(0).submit();
                                    }

                                } else {
                                    $('.error')
                                        .addClass('hide')
                                        .find('.alert')
                                        // .text(response.error.message);
                                        .text("");
                                    $('#payment-form').get(0).submit();
                                }
                            }
                        }
                    });
                });
            });

            /*------------------------------------------
            --------------------------------------------
            Stripe Response Handler
            --------------------------------------------
            --------------------------------------------*/
            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        // .text(response.error.message);
                        .text("カード情報が正しくありません。");
                } else {
                    /* token contains id, last4, and card type */
                    var token = response['id'];

                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.get(0).submit();
                }
            }

        });
    </script>

</body>

</html>