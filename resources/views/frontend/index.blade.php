<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

        .loginClose {
            z-index: 5000 !important;
            right: -90px !important;
            top: -25px !important;
        }

        @media only screen and (max-width: 600px) {
            .loginClose {
                z-index: 5000 !important;
                right: 0 !important;
                top: -70px !important;
            }

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

        .load_content {
            cursor: pointer;
        }

        #loginModal {
            color: #000000 !important;
        }

        .cart_img {
            width: 48px;
            height: 48px;
        }
    </style>

</head>

<body style="background-color: <?php echo $query->background_color; ?>; color: <?php echo $query->char_color; ?>; min-height: 100vh">

    <div class="position-fixed" style="z-index: 1000;right:40px; top:30px">
        <button id="profileBtn" type="button" class="btn btn-quanto py-2 px-5">
            @if (session('customer_id'))
                {{ session('customer_name') }}さん
            @else
                ログイン
            @endif
        </button>
        <span id="loginClick" class="d-none" data-bs-toggle="modal" data-bs-target="#loginModal"></span>
    </div>


    <header id="header" class="header">
        <div class="site-header">
            <div class="site-header-inner">
                <div class="row">
                    <div class="col brand">
                        <div class="brand-logo text-center">
                            <img src="../<?php echo $query->profile_path; ?>" alt="brand-logo">
                        </div>
                        <div class="brand-name text-center text-dark fw-5"></div>
                    </div>
                </div>
                @if ($query->description)
                    <div id="brand-desc" class="brand-desc"
                        style="background-color: <?php echo $query->callout_color; ?>; color: <?php echo $query->char_color; ?>;">
                        {{ $query->brand_description }}</div>
                @endif
                <div id="title-desc" class="title-desc">
                    <h1 id="title" class="title"><span
                            style="background-color: <?php echo $query->callout_color; ?>; color: <?php echo $query->char_color; ?>;">{{ $query->title }}</span>
                    </h1>
                    <p id="description" class="description"
                        style="background-color: <?php echo $query->callout_color; ?>; color: <?php echo $query->char_color; ?>;">
                        {{ $query->description }}</p>
                </div>
                <div id="btn-start" class="btn-start"
                    style="background-color: <?php echo $query->callout_color; ?>; color: <?php echo $query->char_color; ?>;">START</div>
                <div id="qrcode-area" class="qrcode-area">
                    <?php $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(url()->current()); ?>
                    {!! $qrCode !!}
                    <br> <br>QRコードをスキャンすると<br>スマホからもオーダー出来ます。
                </div>
                <img class="logo-img" src="../front/assets/img/footer_logo.png" />
            </div>
        </div>
    </header>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content content position-relative  radius-top radius-bottom">
                <div class="modal-header border-bottom-0 position-absolute" style="z-index: 50;right:0">
                    <button type="button" class="btn-close bg-white p-3" data-bs-dismiss="modal" aria-label="Close"
                        style="border-radius:50%;"></button>
                </div>
                <div class="modal-body p-0">
                    <form action="../show/cart" method="post" id="addToCart">
                        @csrf
                        <div class="product-image text-dark p-0"></div>
                        <div class=" text-dark my-2">
                            <h4 class="product-title">

                            </h4>
                        </div>
                        <div class="product-description text-dark">個数</div>


                        <div class="counter py-2">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button"
                                        class="quantity-left-minus btn btn-outline-primary btn-number" data-type="minus"
                                        data-field="">
                                        <span class="glyphicon glyphicon-minus">-</span>
                                    </button>
                                </span>
                                <input type="text" id="quantity" name="quantity" class="form-control input-number"
                                    value="1" min="1" max="100" style="text-align: center">
                                <span class="input-group-btn">
                                    <button type="button"
                                        class="quantity-right-plus btn btn-outline-primary btn-number" data-type="plus"
                                        data-field="">
                                        <span class="glyphicon glyphicon-plus">+</span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="row py-4">
                            <div class="col text-center">
                                <input type="hidden" id="product_id" name="product_id" value=''>
                                <input type="hidden" id="product_img" name="product_img" value=''>
                                <input type="hidden" id="survey_id" name="survey_id" value=''>
                                <input type="hidden" id="product_name" name="product_name" value=''>
                                <input type="hidden" id="product_image" name="product_image" value=''>
                                <input type="hidden" id="product_q" name="product_q" value=''>
                                <input type="hidden" id="product_p" name="product_p" value=''>
                                <button type="submit" class="btn btn-quanto btn-sm">カートに入れる</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content radius-top radius-bottom" id="loginDiv"
                style="background:#DAE3F3; padding:30px">
                <div class="modal-header border-bottom-0 position-absolute loginClose">
                    <button type="button" class="btn-close bg-white p-3" data-bs-dismiss="modal" aria-label="Close"
                        style="border-radius:50%;"></button>
                </div>
                <div class="modal-header text-center border-2 border-white d-block">
                    <h4 class="text-dark">ログイン</h4>
                </div>
                <div class="modal-body d-block">
                    <h6 class="text-dark text-center my-3">ログインして購入する</h6>
                    <div class="form-group row py-2">
                        <label for="email" class="col-sm-3 col-form-label">メールアドレス</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="loginType" val="1">
                            <input type="email" class="form-control" id="loginEmail" name="loginEmail"
                                placeholder="email@mydomain.com" required value="">
                        </div>
                    </div>
                    <div class="form-group row py-2">
                        <label for="name" class="col-sm-3 col-form-label">パスワード</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="loginPassword" id="loginPassword"
                                required value="">
                        </div>
                    </div>
                    <div class="col-12" id="loginError" style="display: none;">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3"
                            role="alert">
                            ログイン情報が正しくありません。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto"
                                data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col text-center">
                            <button id="loginButton" class="btn btn-quanto btn-sm px-5">注文手続きへ</button>
                        </div>
                    </div>
                    <div class="row pb-1">
                        <div class="col text-center" id="resetBtn">
                            <span class="text-danger" style="cursor:pointer">パスワード忘れた方は、こちら</span>
                        </div>
                    </div>

                </div>

                <div class="border border-white w-100">
                </div>
                <h6 class="text-dark text-center my-4">"Quanto"のご利用は初めてですか？</h6>
                <div class="col text-center mb-3">
                    <h6 class="text-dark d-inline">新規登録は、</h6>
                    <button id="newButton" class="btn btn-quanto btn-sm px-5 d-inline">こちら</button>
                </div>
            </div>

            <div class="modal-content radius-top radius-bottom" id="resetDiv"
                style="background:#DAE3F3; padding:30px; display:none">
                <div class="modal-header border-bottom-0 position-absolute loginClose">
                    <button type="button" class="btn-close bg-white p-3" data-bs-dismiss="modal" aria-label="Close"
                        style="border-radius:50%;"></button>
                </div>
                <div class="modal-header text-center border-2 border-white d-block">
                    <h4 class="text-dark">パスワードの再申請</h4>
                </div>
                <div class="modal-body d-block">
                    <h6 class="text-dark text-center my-3">登録済の情報を入力する</h6>
                    <div class="form-group row py-2">
                        <label for="email" class="col-sm-3 col-form-label">メールアドレス</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="resetEmail" name="resetEmail"
                                placeholder="email@mydomain.com" required value="">
                        </div>
                    </div>
                    <div class="col-12" id="resetError" style="display: none;">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3"
                            role="alert">
                            こちらのメールアドレスが登録されてません。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto"
                                data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="col-12" id="resetSuccess" style="display: none;">
                        <div class="alert alert-success alert-dismissible d-flex align-content-center mt-3"
                            role="alert">
                            メールアドレス宛にパスワード再設定メールを送信しました。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto"
                                data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col text-center">
                            <button id="resetButton" class="btn btn-quanto btn-sm px-5">送信</button>
                        </div>
                    </div>
                    <div class="row pb-1">
                        <div class="col text-center" id="resetToLogin">
                            <span class="text-primary" style="cursor:pointer">ログインは、こちら</span>
                        </div>
                    </div>

                </div>

                <div class="border border-white w-100">
                </div>
                <h6 class="text-dark text-center my-4">"Quanto"のご利用は初めてですか？</h6>
                <div class="col text-center mb-3">
                    <h6 class="text-dark d-inline">新規登録は、</h6>
                    <button id="newButton" class="btn btn-quanto btn-sm px-5 d-inline">こちら</button>
                </div>
            </div>

            <div class="modal-content radius-top radius-bottom" id="registerDiv" style="display:none; padding:30px">
                <div class="modal-header border-bottom-0 position-absolute loginClose">
                    <button type="button" class="btn-close bg-white p-3" data-bs-dismiss="modal" aria-label="Close"
                        style="border-radius:50%;"></button>
                </div>
                <div class="modal-header text-center border-2 border-white d-block">
                    <h4 class="text-dark px-3 py-2 d-inline" style="background:#DAE3F3;">アカウントの作成</h4>
                </div>
                <div class="modal-body d-block">
                    <div class='row form-group py-2'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>メールアドレス</label>
                            <input name="registerEmail" id="registerEmail" class='form-control' type='email'>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                    </div>
                    <div class='row form-group py-2'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>パスワードをご入力ください。</label>
                            <input name="registerPassword" id="registerPassword" class='form-control'
                                type='password'>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                    </div>
                    <div class='row form-group py-2'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>再度パスワードをご入力ください。</label>
                            <input name="retypePassword" id="retypePassword" class='form-control' type='password'>
                            <div class="invalid-feedback" id="retypeError"></div>
                        </div>
                    </div>
                    <div class='row form-group py-2'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>お名前</label>
                            <input name="registerName" id="registerName" class='form-control' type='text'>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                    </div>
                    <div class='row form-group py-2'>
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>フリガナ</label>
                            <input name="registerKana" id="registerKana" class='form-control' type='text'>
                            <div class="invalid-feedback" id="kanaError"></div>
                        </div>
                    </div>
                    <div class="col-12" id="registerError" style="display: none;">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3"
                            role="alert" id="registerErrors">
                            入力情報に不備があります。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto"
                                data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col text-center">
                            <button id="registerButton" class="btn btn-quanto px-5">次へ進む</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="container" id="body-content" style="max-width:1200px; display:none; margin:0 auto;">
        <div class="row">
            <div class="col brand">
                <div class="brand-logo text-center">
                    <img src="../<?php echo $query->profile_path; ?>" alt="brand-logo">
                </div>
                <div class="brand-name text-center text-dark fw-5">{{ $query->title }}</div>
            </div>
        </div>

        <?php
        $tabcount = 0;
        foreach ($query['questions'] as $key => $question) {
        ?>
        <div class="tab">
            <div class="row my-3 text-center">
                <div style="color:black">
                    <h4>{!! $question->title !!}</h4>
                </div>
            </div>
            <div class="row" style="margin-bottom: 125px;">
                <?php
                    foreach ($query['answers'] as $key => $answer) {
                        if ($question->id == $answer->question_id) { ?>
                <div class="col-6 col-lg-4 col-md-4 ">
                    <a class="pro-card load_content" data-survey="{{ $query->id }}" data-id="{{ $answer->id }}"
                        data-title="{{ $answer->title }}" data-value={{ $answer->value }}
                        data-image="{{ $answer->file_url }}" data-price="{{ $answer->value }}"
                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <div class="title" class="mx-auto;" style="color: <?php echo $query->answer_char_color; ?>; text-align: left">
                            {!! $answer->title !!}({!! $answer->value !!}円)
                        </div>
                        <div class="image-holder">
                            <img class="image radius-bottom" src="../{{ $answer->file_url }}" alt="">
                        </div>
                    </a>
                </div>
                <?php }
                    }
                    ?>
            </div>
        </div>
        <?php
            $tabcount = $tabcount + 1;
        }
        ?>
        <!-- Circles which indicates the steps of the form: -->
        <div class="row mx-3" style="position:fixed; bottom:50px; left:0; right:0;">
            <div class="col-2"> <button class="btn btn-quanto px-4 px-sm-5 text-nowrap py-2 float-start me-5"
                    style="display:none" type="button" id="prevBtn" onclick="nextPrev(-1)">前へ</button></div>
            <div class="col-8 text-center pt-2">
                <div class="w-100 <?php if ($query->progress_status < 1) {
                    echo 'd-none';
                } ?>">
                    <?php
                    $width = 80 / $tabcount;
                    $step = 0;
                    while ($step < $tabcount) {
                    ?>
                    <div class="step" style="width:<?php echo $width; ?>%;"></div>
                    <?php
                        $step = $step + 1;
                    }
                    ?>
                </div>
            </div>
            <div class="col-2">
                <button class="btn btn-quanto px-4 px-sm-5 text-nowrap py-2 float-end ms-5" type="button"
                    id="nextBtn" onclick="nextPrev(1)">次へ</button>
                <button class="btn btn-quanto px-4 px-sm-5 text-nowrap py-2 float-end ms-5" style="display:none;"
                    type="button" id="startBtn" onclick="firstTab()">戻る</button>
            </div>
        </div>


        <div class="total" id="total">

            <div class="row cart-item-list" id="item-list"
                style="max-width: 480px; margin: 0 auto; background-color: white; color: black; padding: 5px; border-radius: 13px; display: none">
                <a class="text-decoration-none text-center w-100 text-black-50 pt-2 close-cart"
                    style="cursor:pointer;">
                    <h4>X</h4>
                </a>

                <div class="col py-3">
                    <?php
                    $units = 0;
                    if ($session_cart) {

                        foreach ($session_cart as $key => $each) {
                            $units = $units + $each['quantity'];
                    ?>

                    <div class="row">
                        <div class="col-2">
                            <img src="../{{ $each['img'] }}" alt="" class="cart_img">
                        </div>
                        <div class="col-6">
                            {!! $each['products'] !!}({!! $each['price'] !!}円) x {{ $each['quantity'] }}
                        </div>
                        <div class="col-4">


                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button"
                                        class="update-quantity-left-minus btn btn-outline-primary btn-number"
                                        data-type="minus" data-field="" data-key="{{ $each['id'] }}">
                                        <span class="glyphicon glyphicon-minus">-</span>
                                    </button>
                                </span>
                                <input type="text" id="quantity_{{ $each['id'] }}"
                                    name="quantity_{{ $each['id'] }}" class="form-control input-number"
                                    value="{{ $each['quantity'] }}" min="1" max="100"
                                    style="text-align: center">
                                <span class="input-group-btn">
                                    <button type="button"
                                        class="update-quantity-right-plus btn btn-outline-primary btn-number"
                                        data-type="plus" data-field="" data-key="{{ $each['id'] }}">
                                        <span class="glyphicon glyphicon-plus">+</span>
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <?php }
                        if ($units > 0) {
                        ?>
                    @if (session()->has('customer_id'))
                        <div class="col-12 text-center">
                            <a href="../checkout" class="btn btn-sm btn-quanto order-button">注文手続きへ</a>
                        </div>
                    @else
                        <div class="col-12 text-center">
                            <button id="login" class="btn btn-sm btn-quanto order-button" data-bs-toggle="modal"
                                data-bs-target="#loginModal">注文手続きへ</button>
                        </div>
                    @endif
                    <?php
                        }
                    } else {
                        ?>
                    <div class="col-12 text-center">
                        <p class="text-center">カートは空です。</p>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div id="cart-info" class="row text-center item-line"
                style="max-width: 450px; margin: 0 auto; background-color: #6962FF; padding: 5px; border-radius: 13px">
                <div class="col">
                    <div class="items_count text-light" id="items_count">
                        <?php
                        echo $units . ' 点';
                        // if ($session_cart) {
                        //echo  count($session_cart) . ' 点';
                        //} else {
                        //echo  0 . ' 点';
                        //}
                        ?>
                    </div>
                </div>
                <div class="col">
                    <a class="btn view-card-btn mt-1">カートを見る</a>
                </div>
                <div class="col">
                    <div class="total_result text-light" id="total_result">
                        <?php
                        if ($session_cart) {
                            $total = 0;
                            foreach ($session_cart as $key => $count) {
                                $total += $count['price'] * $count['quantity'];
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







    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        $("#profileBtn").click(function() {
            var data = {
                'check': 'status',
            };
            var url = '../customer/info';
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
                        window.location = "../mypage/<?php echo $query->token; ?>";
                    } else {
                        $('#loginButton').html('ログイン');
                        $('#loginType').val(2);
                        $('#loginClick').click();
                    }
                },
                error: function(response) {

                },
            });
        });
    </script>
    <script>
        $("#btn-start").click(function() {
            $('#header').hide();
            $('#body-content').toggle();
        });
    </script>
    <script>
        $(".loginClose").click(function() {
            $('#resetDiv').hide();
            $('#registerDiv').hide();
            $('#loginDiv').show();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".btn-close").click(function() {
                $('#quantity').val(1);
                setTimeout(function() {
                    $('#total').show();
                }, 50);
            });
            $(".load_content").click(function() {
                $('#total').hide();
                $('.product-image').html('').prepend(
                    '<img class="product-image-full image radius-top" src="../' + $(this)
                    .data("image") + '" />');
                $('.product-title').html('').prepend(
                    `<P> ${$(this).data("title")} (${$(this).data('value')}円)  </P>`);
                $('#product_id').val($(this).data("id"));
                $('#product_img').val($(this).data("image"));
                $('#survey_id').val($(this).data("survey"));
                $('#product_name').val($(this).data("title"));
                $('#product_p').val($(this).data("price"));
                $('#product_q').val($('#quantity').val());
            });
            if (performance.navigation.type == 2) {
                location.reload(true);
            }
        });
    </script>

    <script>
        $('#addToCart').on("submit", function(event) {
            event.preventDefault();
            var data = {
                'product_id': $('#product_id').val(),
                'product_img': $('#product_img').val(),
                'survey_id': $('#survey_id').val(),
                'product_name': $('#product_name').val(),
                'product_q': $('#product_q').val(),
                'product_p': $('#product_p').val(),
            };
            var url = '../show/cart';
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
                    $('#cart-info').html(response.cart);
                    $('#item-list').html(response.list);
                },
                error: function(response) {

                },
            });
            $('.btn-close').click();
        });
    </script>

    <script>
        $('#loginEmail').on("click", function(event) {
            event.preventDefault();
            $('#loginError').hide();
        });
    </script>
    <script>
        $('#loginPassword').on("click", function(event) {
            event.preventDefault();
            $('#loginError').hide();
        });
    </script>

    <script>
        $('#loginButton').on("click", function(event) {
            event.preventDefault();
            $('#loginError').hide();
            var data = {
                'loginEmail': $('#loginEmail').val(),
                'loginPassword': $('#loginPassword').val(),
            };
            var url = '../customer/login';
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
                        $('#loginError').hide();
                        if ($("#loginType").val() == 1) {
                            window.location = "../checkout";
                        } else {
                            $("#profileBtn").html("マイページ");
                            $('#loginModal').modal('toggle');
                        }

                    } else {
                        $('#loginError').show();
                    }

                },
                error: function(response) {

                },
            });

        });
    </script>

    <script>
        $('#resetButton').on("click", function(event) {
            event.preventDefault();
            $('#resetError').hide();
            $('#resetSuccess').hide();
            var data = {
                'resetEmail': $('#resetEmail').val(),
                'survey': '<?php echo $query->token; ?>',
            };
            var url = '../customer/forget';
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
                        $('#resetError').hide();
                        $('#resetEmail').val('');
                        $('#resetSuccess').show();
                    } else {
                        $('#resetSuccess').hide();
                        $('#resetError').show();
                    }

                },
                error: function(response) {

                },
            });

        });
    </script>

    <script>
        $('#registerButton').on("click", function(event) {
            event.preventDefault();
            $('#emailError').html();
            $('#passwordError').html();
            $('#retypeError').html();
            $('#nameError').html();
            $('#kanaError').html();
            $('#registerError').hide();
            var data = {
                'registerEmail': $('#registerEmail').val(),
                'registerPassword': $('#registerPassword').val(),
                'retypePassword': $('#retypePassword').val(),
                'registerName': $('#registerName').val(),
                'registerKana': $('#registerKana').val(),
            };
            var url = '../customer/register';
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
                        $('#emailError').html();
                        $('#passwordError').html();
                        $('#retypeError').html();
                        $('#nameError').html();
                        $('#kanaError').html();
                        $('#registerError').hide();
                        if ($("#loginType").val() == 1) {
                            window.location = "../checkout";
                        } else {
                            $("#profileBtn").html("マイページ");
                            $('#loginModal').modal('toggle');
                        }
                    } else {
                        $('#emailError').html(response.emailError);
                        $('#passwordError').html(response.passwordError);
                        $('#retypeError').html(response.retypeError);
                        $('#nameError').html(response.nameError);
                        $('#kanaError').html(response.kanaError);
                        $('#registerError').show();
                    }
                },
                error: function(response) {

                },
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            var quantitiy = 0;
            $('.quantity-right-plus').click(function(e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                $('#quantity').val(quantity + 1);
                $('#product_q').val($('#quantity').val());
            });

            $('.quantity-left-minus').click(function(e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                if (quantity > 0) {
                    $('#quantity').val(quantity - 1);
                    $('#product_q').val($('#quantity').val());
                }
            });
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
            var url = '../show/update';
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
                    $('#cart-info').html(response.cart);
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
                var url = '../show/update';
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
                        $('#cart-info').html(response.cart);
                        $('#item-list').html(response.list);
                    },
                    error: function(response) {

                    },
                });

            }
        });
    </script>

    <script>
        $(document.body).on('click', '.view-card-btn', function(e) {
            e.preventDefault();
            $('.item-line').hide();
            $('.cart-item-list').toggle();
        });
    </script>
    <script>
        $(document.body).on('click', '.close-cart', function(e) {
            e.preventDefault();
            $('.cart-item-list').hide();
            $('.item-line').toggle();
        });
    </script>
    <script>
        $(document.body).on('click', '#login', function(e) {
            e.preventDefault();
            $('#loginType').val(1);
            $('#total').hide();
        });
    </script>
    <script>
        $(document.body).on('click', '#newButton', function(e) {
            e.preventDefault();
            $('#loginDiv').hide();
            $('#resetDiv').hide();
            $('#registerDiv').toggle();
        });
    </script>
    <script>
        $(document.body).on('click', '#resetBtn', function(e) {
            e.preventDefault();
            $('#loginDiv').hide();
            $('#registerDiv').hide();
            $('#resetDiv').toggle();
        });
    </script>
    <script>
        $(document.body).on('click', '#resetToLogin', function(e) {
            e.preventDefault();
            $('#registerDiv').hide();
            $('#resetDiv').hide();
            $('#loginDiv').toggle();
        });
    </script>
    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").style.display = "none";
                document.getElementById("startBtn").style.display = "inline";
            } else {
                document.getElementById("nextBtn").style.display = "inline";
                document.getElementById("startBtn").style.display = "none";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function firstTab() {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = 0;
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                if (i >= n) {
                    x[i].className = x[i].className.replace(" active", "");
                }
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>
</body>

</html>
