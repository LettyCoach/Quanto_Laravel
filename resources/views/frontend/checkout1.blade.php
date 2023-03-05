<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <title>QUANTO</title>

    <style>
        .brand-logo img {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
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
            margin: 15px;
            max-width: 30%;
            padding: 0;
            text-decoration: none
        }

        .title {
            position: relative;
            width: 100%;
            padding: 15px;
            height: 80px;
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
                max-width: 50%;
            }

            .image-holder {
                height: 180px;
            }
        }

    </style>
</head>

<body style="background-color: #ffeac7; min-height: 100vh">
    <div class="container py-5">

        <div class="row py-5">
            <div class="col-sm-12 col-md-7 col-lg-7">
                <form>
                    <div class="form-group row py-2">
                        <label for="email" class="col-sm-3 col-form-label">メールアドレス</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" placeholder="test@mail.com" required>
                        </div>
                    </div>

                    <div class="form-group row py-2">
                        <label for="name" class="col-sm-3 col-form-label">お名前</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="山田 太郎" required>
                        </div>
                    </div>

                    <div class="form-group row py-2">
                        <label for="postcode" class="col-sm-3 col-form-label">郵便番号</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="postcode" placeholder="111-1111">
                        </div>
                    </div>

                    <div class="form-group row py-2">
                        <label for="address" class="col-sm-3 col-form-label">住所</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="address" placeholder="">
                        </div>
                    </div>

                    <div class="form-group row py-2">
                        <label for="cell" class="col-sm-3 col-form-label">電話番号</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="cell" placeholder="03-1234-5678">
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default credit-card-box">
                            <div class="panel-heading display-table">
                                <h3 class="panel-title">Payment Details</h3>
                            </div>
                            <div class="panel-body">

                                @if (Session::has('success'))
                                <div class="alert alert-success text-center">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                                @endif

                                <form role="form" action="{{ route('stripe.post') }}" method="post"
                                    class="require-validation" data-cc-on-file="false"
                                    data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                                    @csrf

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group required'>
                                            <label class='control-label'>Name on Card</label> <input
                                                class='form-control' size='4' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 form-group card required'>
                                            <label class='control-label'>Card Number</label> <input autocomplete='off'
                                                class='form-control card-number' size='20' type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-xs-12 col-md-4 form-group cvc required'>
                                            <label class='control-label'>CVC</label> <input autocomplete='off'
                                                class='form-control card-cvc' placeholder='ex. 311' size='4'
                                                type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Month</label> <input
                                                class='form-control card-expiry-month' placeholder='MM' size='2'
                                                type='text'>
                                        </div>
                                        <div class='col-xs-12 col-md-4 form-group expiration required'>
                                            <label class='control-label'>Expiration Year</label> <input
                                                class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                                type='text'>
                                        </div>
                                    </div>

                                    <div class='form-row row'>
                                        <div class='col-md-12 error form-group hide'>
                                            <div class='alert-danger alert'>Please correct the errors and try
                                                again.</div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="pay_total" value="<?php if($session_cart){
                                                            $total = 0;
                                                            foreach($session_cart as $key => $count){
                                                                $total+=($count['price']*$count['quantity']);
                                                            }
                                                            echo $total;
                                                        }else{
                                                            echo 0;
                                                        } ?>">
                                            <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now
                                                (
                                                    <?php
                                                        if($session_cart){
                                                            $total = 0;
                                                            foreach($session_cart as $key => $count){
                                                                $total+=($count['price']*$count['quantity']);
                                                            }
                                                            echo $total;
                                                        }else{
                                                            echo 0;
                                                        }
                                                    ?>
                                                )</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-sm-12 col-md-5 col-lg-5">
                <div class="row cart-item-list"
                    style="margin: 0 auto; background-color: white; color: black; padding: 5px; border-radius: 13px;">
                    <div class="col">
                        <?php
                        if($session_cart){
                                foreach($session_cart as $key => $each){ ?>



                        <div class="row">
                            <div class="col">
                                {!! $each['products'] !!} x {{$each['quantity']}}
                            </div>
                            <div class="col">


                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button"
                                            class="update-quantity-left-minus btn btn-outline-primary btn-number"
                                            data-type="minus" data-field="" data-key="{{$each['id']}}">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" id="quantity_{{$each['id']}}" name="quantity_{{$each['id']}}"
                                        class="form-control input-number" value="{{$each['quantity']}}" min="1"
                                        max="100" style="text-align: center">
                                    <span class="input-group-btn">
                                        <button type="button"
                                            class="update-quantity-right-plus btn btn-outline-primary btn-number"
                                            data-type="plus" data-field="" data-key="{{$each['id']}}">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>

                            </div>
                        </div>

                        <hr>
                        <?php }
                            }
                        ?>

                        <div class="row text-center item-line"
                            style="max-width: 450px; margin: 0 auto; background-color: #6962FF; padding: 5px; border-radius: 13px">
                            <div class="col">
                                <div class="items_count text-light" id="items_count">
                                    <?php
                                    if($session_cart){
                                        echo count($session_cart) . ' Items';
                                    }else{
                                        echo 0 . ' Item';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col">

                            </div>
                            <div class="col">
                                <div class="total_result text-light" id="total_result">
                                    <?php
                                        if($session_cart){
                                            $total = 0;
                                            foreach($session_cart as $key => $count){
                                                $total+=($count['price']*$count['quantity']);
                                            }
                                            echo $total;
                                        }else{
                                            echo 0;
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







    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function () {
            $(".load_content").click(function () {
                console.log($(this).data("title"));
                $('.product-image').html('').prepend('<img class="product-image-full" src="/' + $(this)
                    .data("image") + '" />');
                $('.product-title').html('').prepend('<P>' + $(this).data("title") + '</P>');
                $('#product_name').val($(this).data("title"));
                $('#product_p').val($(this).data("price"));
                $('#product_q').val($('#quantity').val());
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            var quantitiy = 0;
            $('.quantity-right-plus').click(function (e) {
                e.preventDefault();
                var quantity = parseInt($('#quantity').val());
                $('#quantity').val(quantity + 1);
                $('#product_q').val($('#quantity').val());
            });

            $('.quantity-left-minus').click(function (e) {
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
        $(document).ready(function () {
            var quantitiy = 0;
            $('.update-quantity-right-plus').click(function (e) {
                e.preventDefault();
                var item_id = $(this).data("key");
                var quantity = parseInt($('#quantity_' + item_id).val());
                $('#quantity_' + item_id).val(quantity + 1);
            });

            $('.update-quantity-left-minus').click(function (e) {
                e.preventDefault();
                var item_id = $(this).data("key");
                var quantity = parseInt($('#quantity_' + item_id).val());
                if (quantity > 0) {
                    $('#quantity_' + item_id).val(quantity - 1);
                }
            });


        });

    </script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $(function () {

            /*------------------------------------------
            --------------------------------------------
            Stripe Payment Code
            --------------------------------------------
            --------------------------------------------*/

            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function (e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function (i, el) {
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
                        .text(response.error.message);
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
