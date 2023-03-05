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

        .load_content {
            cursor: pointer;
        }
    </style>
</head>

<body style="background-color: <?php echo $query->background_color; ?>; color: <?php echo $query->char_color; ?>; min-height: 100vh">

    <!-- Modal -->
    <div class="modal fade" id="resetModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            @if($status==200)
            <div class="modal-content radius-top radius-bottom" id="resetDiv" style="background:#DAE3F3; padding:30px; ">
                <div class="modal-header text-center border-2 border-white d-block">
                    <h4 class="text-dark">パスワード再発行</h4>
                </div>
                <div class="modal-body d-block">
                    <div class="form-group row py-2">
                        <label for="name" class="col-sm-3 col-form-label">パスワード</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="loginPassword" id="loginPassword" required value="">
                        </div>
                    </div>
                    <div class="form-group row py-2">
                        <label for="name" class="col-sm-3 col-form-label">再入力</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="retypePassword" id="retypePassword" required value="">
                        </div>
                    </div>
                    <div class="col-12" id="passwordError" style="display: none;">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3" role="alert">
                            パスワードが一致してません。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="col-12" id="resetError" style="display: none;">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3" role="alert">
                            パスワードの再発行できませんでした。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    </div>
                    <div class="row py-4">
                        <div class="col text-center">
                            <button id="resetButton" class="btn btn-quanto btn-sm px-5">送信</button>
                        </div>
                    </div>
                </div>

                <div class="border border-white w-100">
                </div>
            </div>
            <div class="modal-content radius-top radius-bottom" id="successDiv" style="background:#DAE3F3; padding:30px; display:none;">
                <div class="modal-header text-center d-block">
                    <h5 class="text-dark">パスワード変更が<br>完了致しました。</h5>
                </div>
                <div class="modal-body d-block">
                    <div class="row pb-1">
                        <div class="col text-center" id="backToTop">
                            <span class="text-primary d-block" style="cursor:pointer">トップへ戻る</span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-center d-block">
                    <h6 class="text-dark">Qunatoカスタマーサポート</h6>
                </div>
            </div>
            @else
            <div class="modal-content radius-top radius-bottom" style="background:#DAE3F3; padding:30px">
                <div class="modal-header text-center border-2 border-white d-block">
                    <h4 class="text-dark">パスワード再発行</h4>
                </div>
                <div class="modal-body d-block">
                    <div class="col-12">
                        <div class="alert alert-danger alert-dismissible d-flex align-content-center mt-3" role="alert">
                            URLが無効です。
                            <button type="button" class="btn-close align-self-center me-2 d-none" style="top:auto" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body d-block">
                            <div class="row pb-1">
                                <div class="col text-center" id="backToTop">
                                    <span class="text-primary d-block" style="cursor:pointer">トップへ戻る</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border border-white w-100">
                </div>
            </div>
            @endif
        </div>
    </div>


    <div class="container" id="body-content" style="max-width:1200px;margin:0 auto;">
        <div class="row">
            <div class="col brand">
                <div class="brand-logo text-center">
                    <img src="../../<?php echo $query->profile_path; ?>" alt="brand-logo">
                </div>
                <div class="brand-name text-center text-dark fw-5">{{$query->title}}</div>
            </div>
        </div>

    </div>







    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $('#resetModal').modal('toggle');
        });
    </script>

    <script>
        $("#backToTop").click(function() {
            window.location = "../../show/<?php echo $query->token ?>";
        });
    </script>

    <script>
        $('#resetButton').on("click", function(event) {
            event.preventDefault();
            $('#resetError').hide();
            $('#passwordError').hide();
            if ($("#loginPassword").val() == $("#retypePassword").val()) {
                var data = {
                    'id': <?php echo request()->get('id')  ?>,
                    'token': '<?php echo request()->get('token') ?>',
                    'password': $("#loginPassword").val(),
                };
                var url = '../password';
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
                            $('#resetDiv').hide();
                            $('#successDiv').show();
                        } else {
                            $('#resetError').show();
                        }
                    },
                    error: function(response) {

                    },
                });

            } else {
                $("#passwordError").show();
            }
        });
    </script>

</body>

</html>