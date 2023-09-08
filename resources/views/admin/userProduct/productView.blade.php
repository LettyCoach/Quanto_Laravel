<!-- Fonts -->
<link href="{{ asset('public/css/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Styles -->
<link href="{{ asset('public/css/sb-admin-2.min.css') }}" rel="stylesheet">
<link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
<script src="{{ asset('public/js/easy-number-separator.js') }}"></script>

<link href="{{ asset('public/css/userProduct/index.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<div style="width: 1280px; margin:0 auto; padding-top:128px">
    <div class="row m-0 px-2">
        <div class="col-6 p-0 d-flex flex-column align-items-center">
            <div class="img_pan_main">
                <div class="swiper mySwiper" id="mySwiper_main">
                    <div class="swiper-wrapper" id="slide_img_pan_main">
                        @foreach ($model->getImageUrlsFullPath() as $key => $v)
                            <div class="swiper-slide">
                                <img src="{{ $v['url'] }}" alt=""
                                    onclick="selectMainSlide({{ $key }})">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <div class="img_pan">
                <div class="swiper mySwiper" id="mySwiper">
                    <div class="swiper-wrapper" id="slide_img_pan">

                        @foreach ($model->getImageUrlsFullPath() as $key => $v)
                            <div class="swiper-slide">
                                <img src="{{ $v['url'] }}" alt=""
                                    onclick="selectMainSlide({{ $key }})">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <div class="m-4 d-flex justify-content-end" style="width : 400px">
                <a href="javascript:viewImageList()" class="font-weight-bold">もっと見る</a>
            </div>
        </div>
        <div class="col-6 p-0 pr-4" id="info_pan">
            <div class="row m-0 mt-3" id="productID">{{ $model->getProductID() }}</div>
            <div class="row m-0 mt-3" id="name">{{ $model->name }}</div>
            <div class="row m-0 mt-3" id="price">{{ $model->price }}円</div>
            {{-- <div class="row m-0 mt-3">
                <div class="col-3 p-0 d-flex">
                    <div class="pr-2 change-count" onclick="changeCount(-1)">-</div>
                    <input type="text" id="count_product" value="0">
                    <div class="pl-2 change-count" onclick="changeCount(1)">+</div>
                </div>
                <div class="col-3 p-0">
                    <input type="button" class="btn btn-primary" value="カートに追加">
                </div>
                <div class="col-1 p-0">
                    <img src="{{ url('public/img/img_03/tag_off.png') }}" alt="" class="tag" id="tag_1"
                        onclick="setSave(1)">
                    <img src="{{ url('public/img/img_03/tag_on.png') }}" alt="" class="tag" id="tag_2"
                        onclick="setSave(0)" style="display: none">
                </div>
            </div> --}}
            <div class="row m-0 mt-4" id="product_detail">商品説明</div>
            <div class="row m-0 mt-3" id="detail" style="white-space: pre-line; max-height: 320px; overflow-y:auto">
                {{ $model->detail }}
            </div>
            <div class="row m-0 mt-3" id="product_info">商品詳細</div>
            <div class="row m-0 mt-3 info">
                <div class="col-3 p-0">ブランド名</div>
                <div class="col-9 p-0">{{ $model->brandName }}</div>
            </div>
            <div class="row m-0 mt-3 info">
                <div class="col-3 p-0">sku</div>
                <div class="col-9 p-0">{{ $model->sku }}</div>
            </div>
            <div class="mt-3" id="options">
                @foreach ($model->getOptionsArray() as $key => $v)
                    <div class="row m-0 mt-3 info">
                        <div class="col-3 p-0">{{ $v['name'] }}</div>
                        <div class="col-9 p-0">{{ $v['descriptions'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalImageViewList" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 900px; min-width: 900px;">
            <div class="modal-content"
                style="width:900px; min-height: 360px; background-color: #f1f2ff; border : 0; box-shadow: 5px 5px 10px grey;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">商品画像</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="$('#modalImageViewList').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="user_product_img_pan">
                        <div class="user_product_img_first" id="userProductImage_div_0">
                            <img src="{{ $model->getImageUrlsFullPath()[0]['url'] }}" id="userProductImage_0"
                                alt="img" onclick="viewImage(this.src)">
                        </div>

                        @for ($i = 1; $i < 18; $i++)
                            @php
                                $style = 'display:none';
                                $src = '';
                                if (isset($model->getImageUrlsFullPath()[$i]['url'])) {
                                    $src = $model->getImageUrlsFullPath()[$i]['url'];
                                    if ($model->getImageUrlsFullPath()[$i]['state'] === '') {
                                        $style = 'display:block';
                                    }
                                }
                            @endphp
                            <div id="userProductImage_div_{{ $i }}" class="sub_image_pan"
                                style="{{ $style }}">
                                <img src="{{ $src }}" id="userProductImage_{{ $i }}"
                                    alt="img" class="view_image" onclick="viewImage(this.src)">
                            </div>
                        @endfor
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/lib/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script src="{{ asset('public/js/lib/jquery.easing.min.js') }}"></script>
<script src="{{ asset('public/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('public/js/admin.js') }}"></script>
<script src="{{ asset('public/js/drag.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.2.0/papaparse.min.js"></script>
<script>
    var product_id = 0;
    var swiper_main;
    var swiper

    viewData();

    function viewData() {

        swiper_main = new Swiper("#mySwiper_main", {
            slidesPerView: 1,
            loop: true,
            spaceBetween: 30,
            freeMode: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        swiper = new Swiper("#mySwiper", {
            slidesPerView: 4,
            loop: true,
            spaceBetween: 10,
            freeMode: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

    }

    const selectMainSlide = (index) => {
        console.log(index)
        swiper_main.slideTo(index)
        // swiper_main.initialSlide = index;
    }

    const viewImageList = () => {

        $('#modalImageViewList').modal('toggle');
    }

    const viewImage = (src) => {
        $("#img_view").attr("src", src);
        $('#modalImageView').modal('toggle');
    }

    setSave = (flag) => {
        setTag(product_id, flag)
    }

    setTag = (product_id, flag) => {
        $.get(`/admin/userProduct/setTag`, {
            'product_id': product_id,
            'flag': flag
        }, function(data) {
            if (flag === 1) {
                $("#tag_1").css('display', 'none');
                $("#tag_2").css('display', 'block');
                $(`#tag_${product_id}_1`).css('display', 'none');
                $(`#tag_${product_id}_2`).css('display', 'block');
            } else {
                $("#tag_1").css('display', 'block');
                $("#tag_2").css('display', 'none');
                $(`#tag_${product_id}_1`).css('display', 'block');
                $(`#tag_${product_id}_2`).css('display', 'none');
            }
        });
    }

    const viewList = (value) => {
        location.href = `/admin/userProducts?keyword=${value}`;
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

    var csvData = {};

    $(document).on('click', '.csv-image', function() {
        var input = document.createElement('input');
        input.type = 'file';

        const id = $(this).attr('id');
        const encoding = id;

        input.onchange = e => {
            var file = e.target.files[0];

            Papa.parse(file, {
                encoding: encoding,
                header: true,
                complete: function(results) {

                    csvData = results;

                    $('.csv-modal-header').html(`CSVアップロード(${encoding})`);

                    $(".csv-table thead").html("");
                    $(".csv-table tbody").html("");

                    var row = $("<tr/>");
                    row.append($("<th/>").text());
                    $.each(results.meta.fields, function(i, t) {
                        row.append($("<th/>").text(t));
                    });
                    $(".csv-table thead").append(row);

                    $(".csv-table tbody").append($('<tr/>'));
                    $.each(results.data, function(i, el) {
                        var row = $("<tr/>");
                        row.append($("<td/>").text());
                        $.each(el, function(j, cell) {
                            var div = $('<div/>');
                            div = div.html(cell);
                            div = div.attr('class', 'div-td');
                            // if (cell !== "")
                            row.append($("<td/>").append(div));
                        });
                        $(".csv-table tbody").append(row);
                    });

                    $('#modalCSV').modal('toggle');
                }
            })
        }
        input.click();
    });

    $(document).on('click', '#csv_upload_cancel', function() {
        $('#modalCSV').modal('toggle');
    });

    $(document).on('click', '#csv_upload', function() {
        console.log(csvData)
        const _token = "{{ csrf_token() }}"
        $.post("{{ route('admin.userProduct.csv') }}", {
            _token: _token,
            csv: csvData
        }, function(data) {
            location.href = "{{ route('admin.userProducts') }}";
        })
        // $('#modalCSV').modal('toggle');
    });
</script>
