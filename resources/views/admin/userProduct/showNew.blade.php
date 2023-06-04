@extends('layouts.admin', ['title' => '商品管理/カテゴリ'])
@section('main-content')

<link href="{{ asset('public/css/userProduct/index.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.userProduct.create') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th style="width : 80px"></th>
                    <th style="width: 12%">ブランド名</th>
                    <th style="width: 20%">商品名</th>
                    <th style="width: 12%">カテゴリー</th>
                    <th style="width: 24%">オプション<br>（カラー/サイズ/素材）</th>
                    <th style="width: 10%">単価(円)</th>
                    <th style="width: 16%; min-width: 120px"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8" class="firstTD">　</td>
                </tr>
            @foreach($models as $i => $model)
                <tr>
                    <td>{{ $model->getProductID() }}</td>
                    <td>
                        <img src="{{$model->getImageUrlFirstFullPath('blank')}}" class="product_first_img" onclick="viewData({{ $model->id }})"/>
                    </td>
                    <td>{{ $model->brandName }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->getCategoryText() }}</td>
                    <td><?php echo $model->getOptionsText(); ?></td>
                    <td>{{ number_format($model->price) }}円</td>
                    <td>
                        <a href="{{ route('admin.userProduct.edit',['id'=>$model->id]) }}">
                            <img src="{{  url('public/img/img_03/pen.png') }}" alt='edit' style="width:28px"/>
                        </a>
                        <a href="{{ route('admin.userProduct.duplicate',['id'=>$model->id])}}">
                            <img src="{{  url('public/img/img_03/copy.png') }}" alt='edit' style="width:28px"/>
                        </a>
                        <a href="javascript:deleteData('{{ route('admin.userProduct.delete',['id'=>$model->id])}}')">
                            <img src="{{  url('public/img/img_03/delete.png') }}" alt='edit' style="width:28px"/>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $models->links() }}
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalAddQuestion" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 1400px">
            <div class="modal-content" style="width:1400px; min-height : calc(100vh - 80px)">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">商品情報</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddQuestion').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 px-2">
                        <div class="col-6 p-0 d-flex flex-column align-items-center">
                            <div class="img_pan_main">
                                <div class="swiper mySwiper" id="mySwiper_main">
                                    <div class="swiper-wrapper" id = "slide_img_pan_main">
                                        <div class="swiper-slide">
                                            <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                        </div>
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                            <div class="img_pan">
                                <div class="swiper mySwiper" id="mySwiper">
                                    <div class="swiper-wrapper" id = "slide_img_pan">
                                        <div class="swiper-slide">
                                            <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                        </div>
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            </div>
                            
                            <div class="m-4 d-flex justify-content-end" style="width : 400px" >
                                <a href="javascript:viewImageList()" class="font-weight-bold">もっと見る</a>
                            </div>
                        </div>
                        <div class="col-6 p-0 pr-4" id="info_pan">
                            <div class="row m-0 mt-3" id="productID">デザインTシャツブラック</div>
                            <div class="row m-0 mt-3" id="name">デザインTシャツブラック</div>
                            <div class="row m-0 mt-3" id="price">980デ</div>
                            <div class="row m-0 mt-3" >
                                <div class="col-3 p-0 d-flex">
                                    <div class="pr-2 change-count" onclick="changeCount(-1)">-</div>
                                    <input type="text" id = "count_product" value = "0">
                                    <div class="pl-2 change-count" onclick="changeCount(1)">+</div>
                                </div>
                                <div class="col-3 p-0">
                                    <input type="button" class="btn btn-primary" value="カートに追加">
                                </div>
                                <div class="col-1 p-0">
                                    <img src="{{ url('public/img/img_03/tag_off.png') }}" alt="" class="tag" id="tag_1" onclick="setSave(1)" >
                                    <img src="{{ url('public/img/img_03/tag_on.png') }}" alt="" class="tag" id="tag_2" onclick="setSave(0)" style="display: none">
                                </div>
                            </div>
                            <div class="row m-0 mt-4" id="product_detail">商品説明</div>
                            <div class="row m-0 mt-3" id="detail">デザインTシャツブラック</div>
                            <div class="row m-0 mt-3" id="product_info">商品詳細</div>
                            <div class="row m-0 mt-3 info">
                                <div class="col-3 p-0">ブランド名</div>
                                <div class="col-9 p-0">デザインク</div>
                            </div>
                            <div class="row m-0 mt-3 info">
                                <div class="col-3 p-0">sku</div>
                                <div class="col-9 p-0">123-4567</div>
                            </div>
                            <div class="mt-3" id="options">
                                <div class="row m-0 mt-3 info">
                                    <div class="col-3 p-0">デザイ</div>
                                    <div class="col-9 p-0">デザイ-1</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal -->
    <div class="modal fade" id="modalImageViewList" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 900px; min-width: 900px;">
            <div class="modal-content" style="width:900px; min-height: 360px; background-color: #f1f2ff; border : 0; box-shadow: 5px 5px 10px grey;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">商品画像</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalImageViewList').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class = "user_product_img_pan">
                        <div class="user_product_img_first" id = "userProductImage_div_0">
                            <img src = "" id="userProductImage_0" alt = "img" onclick="viewImage(this.src)">
                        </div>
                    @php
                        for ($i = 1; $i < 18; $i ++) {
                            $style = "display:none";
                            $src = "";
                    @endphp
                            <div id = "userProductImage_div_{{$i}}" class="sub_image_pan" style = "{{$style}}">
                                <img src = "{{$src}}" id="userProductImage_{{$i}}" alt = "img" class="view_image" onclick="viewImage(this.src)">
                            </div>
                    @php
                        }
                    @endphp
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal -->
    <div class="modal fade" id="modalImageView" tabindex="-1" role="dialog" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 640px; min-width: 640px">
            <div class="modal-content" style="width:640px;">
                <div class="modal-body">
                    <img src="" alt="" class="img_view" id="img_view">

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        var product_id = 0;
        function viewData(id) {
            product_id = id;
            $.get(`/admin/userProduct/show/${id}`, function(data) {
                const obj = JSON.parse(data);
                console.log(obj);
                $('#productID').html(obj.productID);
                // $('.main_img').attr('src', obj.main_img);
                let rlt = "";
                for (let i = 0; i < 18; i ++) {
                    $("#userProductImage_div_" + i).css('display', 'none');
                    $("#userProductImage_" + i).attr('src', "");
                }

                obj.img_urls.forEach((e, i) => {
                    if (e.state !== '') {
                        $("#userProductImage_div_" + i).css('display', 'none');
                        return;
                    }
                    rlt += `<div class="swiper-slide"><img src="${e.url}" alt="" ></div>`;
                    $("#userProductImage_" + i).attr('src', e.url);
                    $("#userProductImage_div_" + i).css('display', 'block');
                })
                $('#slide_img_pan_main').html(rlt);
                $('#slide_img_pan').html(rlt);

                $('#name').html(obj.name);
                $('#price').html(obj.price + '円');
                $('#detail').html(obj.detail);
                $('#brandName').html(obj.brandName);
                $('#sku').html(obj.sku);
                rlt = "";
                obj.options.forEach((e, i) => {
                    rlt += `<div class="row m-0 mt-3 info"><div class="col-3 p-0">${e.name}</div><div class="col-9 p-0">${e.descriptions}</div></div>`;
                })
                $('#options').html(rlt);
                if (obj.tag === true) {
                    $("#tag_1").css('display', 'none');
                    $("#tag_2").css('display', 'block');
                }
                else {
                    $("#tag_1").css('display', 'block');
                    $("#tag_2").css('display', 'none');
                }
                $('#modalAddQuestion').modal('toggle');
            })

        }

        const viewImageList = () => {
            
            $('#modalImageViewList').modal('toggle');
        }

        const viewImage = (src) => {
            $("#img_view").attr("src", src);
            $('#modalImageView').modal('toggle');
        }

        setSave = (flag) => {
            $.get(`/admin/userProduct/setTag`, {'product_id': product_id, 'flag': flag}, function(data) {
                if (flag === 1) {
                    $("#tag_1").css('display', 'none');
                    $("#tag_2").css('display', 'block');
                }
                else {
                    $("#tag_1").css('display', 'block');
                    $("#tag_2").css('display', 'none');
                }
            });
        }

        const changeCount = (val) => {
            let rlt = $("#count_product").val();
            rlt = parseInt(rlt);
            if (typeof val != "number") {
                rlt = 0;
            }
            rlt += val;
            if (rlt < 0) rlt = 0;
            $("#count_product").val(rlt);
        }

        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;

        }

        var swiper = new Swiper("#mySwiper_main", {
            slidesPerView: 1,
            loop: true,
            spaceBetween: 30,
            freeMode: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
        
        var swiper = new Swiper("#mySwiper", {
            slidesPerView: 4,
            loop: true,
            spaceBetween: 30,
            freeMode: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
        });
        viewData({{$id}});
    </script>
@endsection