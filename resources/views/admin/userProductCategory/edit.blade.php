@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
    <link href="{{ asset('public/css/userProductCategory/edit.css') }}" rel="stylesheet">

    <div class="main_pan">
        <h2 class="main_title">{{ $caption }}</h2>
        <div class="row m-0 justify-content-center">
            <form class="" id="survey" method="post" action="{{ route('admin.userProductCategory.save') }}"
                enctype="multipart/form-data" onsubmit="return checkData()">
                <div class="form_pan" style="width: 720px">
                    @csrf
                    <div class="row m-0 px-4 mt-4">
                        <div class="col-3 p-0">
                            <label class="col-form-label mr-1">カバー画像</label>
                        </div>
                        <div class="col-9 p-0">
                            <img src="{{ $model->getImageUrlFullPath() }}" id="main_img" class="main_img" alt="img">
                            <input type="hidden" id="main_img_url" name="main_img_url" value="{{ $model->main_img_url }}">
                        </div>
                    </div>
                    <div class="row m-0 px-4 mt-4">
                        <div class="col-3 p-0">
                            <label class="col-form-label mr-1">カテゴリ名</label>
                        </div>
                        <div class="col-9 p-0">
                            <input type="text" class="form-control" name="name" value="{{ $model->name }}" required>
                        </div>
                    </div>
                    <div class="row m-0 px-4 mt-4">
                        <div class="col-3 p-0">
                            <label class="col-form-label mr-1">サブカテゴリ名</label>
                        </div>
                        <div class="col-9 p-0">
                            <input type="text" class="form-control" name="sub_name" value="{{ $model->sub_name }}">
                        </div>
                    </div>
                    <div class="row m-0 px-4 mt-4">
                        <div class="col-3 p-0">
                            <label class="col-form-label mr-1">商品一覧</label>
                        </div>
                        <div class="col-9 p-0">
                            <div class="user_product_img_pan">

                            </div>
                        </div>
                    </div>
                    <div class="row m-0 px-4 mt-5">
                        <div class="col-3 p-0">
                            <label class="col-form-label mr-1">登録商品から選択：</label>
                        </div>
                        <div class="col-7 p-0 " id="select_pan">

                        </div>
                        <div class="col-2 p-0 flex justify-content-center align-items-baseline">
                            <img src="{{ url('public/img/img_03/plus_img.png') }}" id="img_add_product" alt="img"
                                class="add_product" onclick="addProduct()">
                        </div>
                    </div>
                    <div class="row m-0 px-4 mt-5 justify-content-center">
                        <input type="button" class="btn btn-outline-primary mr-3" value="キャンセル"
                            onclick="location.href='{{ route('admin.userProductCategories') }}'">
                        <input type="submit" class="btn btn-primary ml-3" value="保存" style="background-color: #6423FF">
                    </div>

                    <input type="hidden" name="id" value="{{ $model->id }}" />
                    <input type="hidden" name="productes" id="productes" value="{{ $model->getProductes() }}" />
                    <input type="hidden" name="productes_all" id="productes_all"
                        value="{{ $model->getProductesAll() }}" />
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var productes = [];
        var productes_all = [];


        checkData = () => {

            $('#productes').val(JSON.stringify(productes));
            return true;

        }


        $(document).ready(function() {
            readProductes();
            displayProductImages();
            initSelectProduct();
        });

        makeSelectProduct = () => {
            let rlt = "";
            rlt += `<select class="f-control f-dropdown" id = "selectProduct" placeholder="商品選択" size=10`;
            rlt += `<option value=""> </option>`;
            productes_all.forEach((e, i) => {
                if (productes.filter(p => p.id === e.id).length > 0) return;
                rlt += `<option value="${e.id}" data-image="${e.img_url}">${e.name}</option>`;
            })

            rlt += `</select>`

            $('#select_pan').html(rlt);
        }

        addProduct = () => {
            const id = $('#selectProduct').val();
            if (id == '' || id == null || id == undefined) {
                alert("商品を選択してください。");
                return;
            }

            const product = productes_all.find(e => e.id == id);

            productes.push(product);
            $('#select_pan *').remove();
            displayProductImages();
            initSelectProduct();
        }

        initSelectProduct = () => {

            makeSelectProduct();
            $.fn.mySelectDropdown = function(options) {
                return this.each(function() {
                    var $this = $(this);

                    $this.each(function() {
                        var dropdown = $("<div />").addClass("f-dropdown selectDropdown");
                        dropdown.css('--max-scroll', 12);

                        if ($(this).is(':disabled'))
                            dropdown.addClass('disabled');


                        $(this).wrap(dropdown);

                        console.log(dropdown)

                        var label = $("<span />").append($("<span />")
                            .text($(this).attr("placeholder"))).insertAfter($(this));
                        var list = $("<ul />");

                        $(this)
                            .find("option")
                            .each(function() {
                                var image = $(this).data('image');
                                if (image) {
                                    list.append($("<li />").append(
                                        $("<a />").attr('data-val', $(this).val())
                                        .html(
                                            $("<span />").append($(this).text())
                                        ).prepend('<img src="' + image + '">')
                                    ));
                                } else if ($(this).val() != '') {
                                    list.append($("<li />").append(
                                        $("<a />").attr('data-val', $(this).val())
                                        .html(
                                            $("<span />").append($(this).text())
                                        )
                                    ));
                                }
                            });

                        list.insertAfter($(this));

                        if ($(this).find("option:selected").length > 0 && $(this).find(
                                "option:selected").val() != '') {
                            list.find('li a[data-val="' + $(this).find("option:selected").val() +
                                '"]').parent().addClass("active");
                            $(this).parent().addClass("filled");
                            label.html(list.find("li.active a").html());
                        }
                    });

                    if (!$(this).is(':disabled')) {
                        $(this).parent().on("click", "ul li a", function(e) {
                            e.preventDefault();
                            var dropdown = $(this).parent().parent().parent();
                            var active = $(this).parent().hasClass("active");
                            var label = active ?
                                $('<span />').text(dropdown.find("select").attr("placeholder")) :
                                $(this).html();

                            dropdown.find("option").prop("selected", false);
                            dropdown.find("ul li").removeClass("active");

                            dropdown.toggleClass("filled", !active);
                            dropdown.children("span").html(label);

                            if (!active) {
                                dropdown
                                    .find('option[value="' + $(this).attr('data-val') + '"]')
                                    .prop("selected", true);
                                $(this).parent().addClass("active");
                            }

                            dropdown.removeClass("open");
                        });

                        $this.parent().on("click", "> span", function(e) {
                            var self = $(this).parent();
                            // self.css('height', '500px')
                            self.toggleClass("open");
                        });

                        $(document).on("click touchstart", function(e) {
                            var dropdown = $this.parent();
                            if (dropdown !== e.target && !dropdown.has(e.target).length) {
                                dropdown.removeClass("open");
                            }
                        });
                    }
                });
            };


            $('select.f-dropdown').mySelectDropdown();
        }

        $(document).on('click', '#main_img', function() {
            var input = document.createElement('input');
            input.type = 'file';

            input.onchange = e => {
                var file = e.target.files[0];
                var formData = new FormData();
                var filePath = "public/user_product_category/";
                formData.append('file', file);
                formData.append('filePath', filePath);

                var hostUrl = "{{ url('/') }}";
                var postUrl = hostUrl + '/api/v1/client/uploadImgWithPath';

                $.ajax({
                    type: 'POST',
                    url: postUrl,
                    data: formData,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    cache: false,
                    processData: false,
                    success: function(data, status) {
                        const filePathFull = hostUrl + '/' + filePath + data;
                        $("#main_img").attr('src', filePathFull);
                        $("#main_img_url").val(data);
                    }
                });
            }
            input.click();
        })


        $(document).on('mouseenter', '.userProductItem', function() {
            const imgId = $(this).attr('id') + "_del";
            $(`#${imgId}`).css('display', 'block');
        })

        $(document).on('mouseleave', '.userProductItem', function() {
            const imgId = $(this).attr('id') + "_del";
            $(`#${imgId}`).css('display', 'none');
        })

        $(document).on('mouseenter', '.delete_image', function() {
            const imgId = $(this).attr('id') + "_del";
            $(`#${imgId}`).css('display', 'block');
        })

        $(document).on('mouseleave', '.delete_image', function() {
            const imgId = $(this).attr('id') + "_del";
            $(`#${imgId}`).css('display', 'none');
        })

        readProductes = () => {

            productes = JSON.parse($('#productes').val());
            productes_all = JSON.parse($('#productes_all').val());
        }

        displayProductImages = () => {
            let rlt = "";

            productes.forEach((e, i) => {
                if (i >= 10) return;
                rlt += `<div id = "userProductImage_div_${i}" class="userProductItem">`;
                rlt += `<img src = "${e.img_url}" id="userProductImage_${i}" alt = "img" class="view_image">`;
                rlt +=
                    `<img src = "{{ url('public/img/img_03/delete.png') }}"  id="userProductImage_div_${i}_del" onclick="deleteImage(${i})" alt = "img" class = "delete_image" style="display:none">`;
                rlt += `</div>`
            })

            $('.user_product_img_pan').html(rlt);
        }


        deleteImage = (i) => {

            productes.splice(i, 1);
            $('#select_pan *').remove();
            displayProductImages();
            initSelectProduct();
        }
    </script>
@endsection
