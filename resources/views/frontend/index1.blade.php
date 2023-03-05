<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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

<body style="background-color: #ffeac7; color: {{$query->char_color}}; min-height: 100vh">



    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/show/cart" method="post">
                        @csrf
                        <div class="product-image text-dark"></div>
                        <div class="product-title text-dark"></div>
                        <div class="product-description text-dark">Item Description</div>


                        <div class="counter py-2">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-left-minus btn btn-outline-primary btn-number"
                                        data-type="minus" data-field="">
                                        <span class="glyphicon glyphicon-minus">-</span>
                                    </button>
                                </span>
                                <input type="text" id="quantity" name="quantity" class="form-control input-number"
                                    value="1" min="1" max="100" style="text-align: center">
                                <span class="input-group-btn">
                                    <button type="button" class="quantity-right-plus btn btn-outline-primary btn-number"
                                        data-type="plus" data-field="">
                                        <span class="glyphicon glyphicon-plus">+</span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="row py-4">
                            <div class="col text-center">
                                <input type="hidden" id="product_name" name="product_name" value=''>
                                <input type="hidden" id="product_name" name="product_image" value=''>
                                <input type="hidden" id="product_q" name="product_q" value=''>
                                <input type="hidden" id="product_p" name="product_p" value=''>
                                <button type="submit" class="btn btn-primary btn-sm">Add to cart</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <div class="col brand py-4">
                <div class="brand-logo text-center">
                    <img src="/<?php echo $query->profile_path; ?>" alt="brand-logo">
                </div>
                <div class="brand-name text-center text-dark fw-5">{{$query->title}}</div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 95px;">
            <?php
            foreach($query['questions'] as $key => $question){
                foreach($query['answers'] as $key => $answer){
                    if($question->id == $answer->question_id){ ?>

            <a class="col-5 col-lg-4 col-md-4 text-center pro-card load_content" data-title="{{$answer->title}}"
                data-image="{{$answer->file_url}}" data-price="{{$answer->value}}" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">
                <div class="title" style="color: {{$query->answer_char_color}}; text-align: left">
                    {!! $answer->title !!}
                </div>

                <div class="image-holder">
                    <img class="image" src="/{{$answer->file_url}}" alt="">
                </div>
            </a>

            <?php }
                }
            }
            ?>
        </div>

        <div class="total">

            <div class="row cart-item-list"
                style="max-width: 450px; margin: 0 auto; background-color: white; color: black; padding: 5px; border-radius: 13px; display: none">
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
                                    <button type="button" class="update-quantity-left-minus btn btn-outline-primary btn-number"
                                        data-type="minus" data-field="" data-key="{{$each['id']}}">
                                        <span class="glyphicon glyphicon-minus">-</span>
                                    </button>
                                </span>
                                <input type="text" id="quantity_{{$each['id']}}" name="quantity_{{$each['id']}}"
                                    class="form-control input-number" value="{{$each['quantity']}}" min="1" max="100"
                                    style="text-align: center">
                                <span class="input-group-btn">
                                    <button type="button" class="update-quantity-right-plus btn btn-outline-primary btn-number"
                                        data-type="plus" data-field="" data-key="{{$each['id']}}">
                                        <span class="glyphicon glyphicon-plus">+</span>
                                    </button>
                                </span>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <?php }
                            }
                        ?>
                </div>

                <div class="col-12 text-center">
                    <a href="/checkout" class="btn btn-sm btn-primary">Checkout</a>
                </div>

            </div>

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
                    <a class="btn view-card-btn">View the cart</a>
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
                var quantity = parseInt($('#quantity_'+item_id).val());
                $('#quantity_'+item_id).val(quantity + 1);
            });

            $('.update-quantity-left-minus').click(function (e) {
                e.preventDefault();
                var item_id = $(this).data("key");
                var quantity = parseInt($('#quantity_'+item_id).val());
                if (quantity > 0) {
                    $('#quantity_'+item_id).val(quantity - 1);
                }
            });

            
        });

    </script>

    <script>
        $(document).ready(function () {
            $('.view-card-btn').click(function (e) {
                e.preventDefault();
                $('.item-line').hide();
                $('.cart-item-list').toggle();
            });

            
        });

    </script>
</body>

</html>
