@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.css">
<link href="{{ asset('public/css/userProduct.css') }}" rel="stylesheet">

<div class="product_mana_pan">
    <h2 class="product_add_title">{{ $caption }}</h2>
    <form class="" id="userProduct" method="post" action="{{ route('admin.userProduct.save') }}" enctype="multipart/form-data" onsubmit="return checkData()">
        @csrf

        <div class="row m-0">
            <div class="col-8">
                <div class="row m-0">
                    <div class="form_pan">
                        <h4>画像</h4>
                        <div class = "row m-0 flex flex-row">
                            <div class="user_product_img_first">
                                <img src = "{{url('public/img/img_03/grid_list.png')}}" alt = "img" class = "add_image">
                            </div>
                            <div class = "user_product_img_pan">
                            @php
                                $listImageURL = $model->getImageUrls();
                                for ($i = 0; $i < 20; $i ++) {
                                    $style = "";
                                    $src = "";
                                    if ($i >= count($listImageURL)) $style = "display:none";
                                    else $src = url('public/user_product/' . $listImageURL[$i]);
                            @endphp
                                    <div id = "userProductImage_div_{{$i}}" style = "{{$style}}">
                                        <img src = "{{$src}}" id="userProductImage_{{$i}}" alt = "img" class="view_image">
                                        <img src = "{{url('public/img/ic_delete.png')}}" onclick="deleteImage({{$i}})" alt = "img" class = "delete_image">
                                    </div>
                            @php
                                }
                            @endphp
                                <div>werwe
                                    <img src = "{{url('public/img/img_03/add_plus.png')}}" id="img_upload_img" alt = "img" class = "add_image">
                                </div>
                                <input type="hidden" name="img_urls" id="img_urls" value="{{$model->img_urls}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-0 mt-4">
                    <div class="col-7 p-0 pr-2">
                        <div class="form_pan">
                            <h4 >商品情報</h4>
                            <div class="row m-0 mt-3">
                                <div class="col-5 p-0 pr-2">
                                    <h5 class="font-weight-bold">商品ID</h5>
                                    <input type = "text" class = "form-control" value={{ $productID }} readonly>
                                </div>
                                <div class="col-7 p-0 pl-2">
                                    <h5 class="font-weight-bold">ブランド名</h5>
                                    <input type = "text" class = "form-control" name = "brandName" value="{{$model->brandName}}" required>
                                </div>
                            </div>
                            <div class="row m-0 mt-3">
                                <h5 class="font-weight-bold">商品名</h5>
                                <input type = "text" class = "form-control" name = "name" value="{{$model->name}}" required>
                            </div>
                            <div class="row m-0 mt-3">
                                <h5 class="font-weight-bold">SKU</h5>
                                <input type = "text" class = "form-control" name = "sku" value="{{$model->sku}}" required>
                            </div>
                        </div>

                        <div class="form_pan mt-4">
                            <h4 >商品説明</h4>
                            <div class="row m-0 mt-3">
                                <textarea class = "form-control" name = "detail" style = "height : 84px" required>{{$model->detail}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 p-0 pl-2">
                        <div class="form_pan">
                            <div class="row m-0 justify-content-between">
                                <h4 >金額</h4>
                                <input type="checkbox" class="switch_1" name="flagPrice2" {{$model->flagPrice2}}>
                            </div>
                            <div class="row m-0 mt-3">
                                <div class="col-6 p-0 pr-2">
                                    <h6 class="text-center">例;希望小売価格</h6>
                                    <input type = "text" class = "form-control text-right" name = "price" value="{{$model->price}}" required>
                                </div>
                                <div class="col-6 p-0 pl-2">
                                    <h6 class="text-center">例;セール価格</h6>
                                    <input type = "text" class = "form-control text-right" name = "price2" value="{{$model->price2}}" disable>
                                </div>
                            </div>
                        </div>

                        <div class="form_pan mt-4">
                            <h5 class="font-weight-bold">カテゴリー</h5>
                            <div class="row m-0 mt-3">
                                <div class="user_product_catetory_pan">
                                    <input type="button" value = "+" onclick="viewModal()">
                                </div>
                                <input type="hidden" name="category_ids" id="category_ids" value="{{$model->getCategoryIds_()}}">
                            </div>
                            <h5 class="font-weight-bold mt-4">オプション</h5>
                            <div class="row m-0 mt-3 flex-column">
                                <div class="user_product_option_pan">
                                    <div class="option_row">
                                        <div class="option_name"><div></div></div>
                                        <div class="option_description"><div></div></div>
                                    </div>
                                </div>
                                <img src = "{{url('public/img/img_03/add_plus.png')}}" id="img_add_option" alt = "img" class = "add_option">
                                <input type="hidden" name="options" id="options" value="{{ $model->options }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="form_pan" id = "form_add_option">
                    <h4 class="text-center">オプションの登録</h4>
                    <div class="row m-0 mt-3">
                        <h5 class="font-weight-bold">オプション名</h5>
                        <input type = "text" id="option_name" class = "form-control">
                    </div>
                    <div class="row m-0 mt-3">
                        <h5 class="font-weight-bold">オプション内容の入力</h5>
                        <input type = "text" id="option_description_tag" class = "form-control-tag">
                    </div>
                    <div class="row mt-3 justify-content-center" style="align-items: baseline;">
                        <input type="button" class="btn btn-outline-primary mr-3" value="キャンセル"  id = "cancel_option">
                        <input type="button" class="btn btn-primary ml-3" value="登録" id = "add_option">
                    </div>
                </div>
                <div class="form_pan mt-4">
                    <h4 >バーコード</h4>
                    <div class="row m-0 mt-3  justify-content-between align-items-center">
                        <img src = "{{url('public/img/img_03/barcode.png')}}" alt = "barcode" class="barcode_img">
                        <input type = "text" class = "form-control" name = "barcode" value="{{$model->barcode}}" required style="width : calc(100% - 60px)">
                    </div>
                </div>
                <div class="form_pan mt-4">
                    <h4 >メモ</h4>
                    <div class="row m-0 mt-3">
                        <textarea class = "form-control" name = "memo" style = "height : 84px">{{$model->memo}}</textarea>
                    </div>
                </div>
                <!-- <div class="form_pan mt-4">
                    <div class="row m-0 mt-3">
                        <h5 >在庫</h5>
                        <input type = "text" class = "form-control" name = "stock" value="{{$model->stock}}">
                    </div>
                    <div class="row m-0 mt-3">
                        <h5 >在庫数の指定</h5>
                        <input type="checkbox" class="switch_1" name="stockLimit" {{ $model->stockLimit }}>
                    </div>
                    <div class="row m-0 mt-3">
                        <h5 >在庫</h5>
                        <input type = "text" class = "form-control" name = "stock" value="{{$model->stock}}">
                    </div>
                </div> -->

                <div class="form_pan mt-4">
                    <div class="row m-0 mt-3 justify-content-between">
                        <h5 >非表示にする</h5>
                        <input type="checkbox" class="switch_1" name="isDisplay" {{$model->isDisplay}}>
                    </div>
                    <div class="row m-0 mt-3 justify-content-center">
                        <input type="button" class="btn btn-outline-primary mr-3" value="キャンセル"  onclick="location.href='{{route('admin.userProducts')}}'">
                        <input type="submit" class="btn btn-primary ml-3" value="保存" >
                    </div>
                </div>
            </div>
        </div>


        <input type = "hidden" name = "id" value = "{{$model->id}}"/>

    </form>

    <!-- Modal -->
    <div class="modal fade" id="modalAddQuestion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="">
            <div class="modal-content" style="width:400px;;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">カテゴリー選択</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddQuestion').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div>
                            <input type="checkbox" id = "categoryCheckAll" class="switch_2">
                            <label class="col-form-label"  id = "categoryLabelAll">All Categories</label>
                        </div>
                    </div>
                    <div class="dropQuestion">
                        <div class="category_pan">
                            @foreach($listCategory as $item)
                            <div>
                                <input type="checkbox" id = "categoryCheck_{{$item->id}}" class="switch_2">
                                <label class="col-form-label"  id = "categoryLabel_{{$item->id}}">{{$item->name}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  onclick="$('#modalAddQuestion').modal('toggle')">閉じる</button>
                    <button type="button" class="btn btn-primary" id="btnSaveCategory">OK</button>
                </div>
                <input type="hidden" id="container-id">
            </div>
        </div>
    </div>
</div>

@endsection

<input type = "hidden" id="listCategoryId" value="{{$model->getCategoryIds_()}}">

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.js"></script>

<script>

    var options = [];
    var listCategoryId = [];





    // formatText = () => {
    //     $('[class="input_material"]').each(function() {
    //         $(this).css('width', ($(this).val().length + 1) * 2 + 'ch');
    //     })
    // }


    $(document).ready(function() {


        $('#userProduct' ).bind( 'keypress keydown keyup', function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
        // formatText();

        var tmp = $('#listCategoryId').val().split('_');

        for (let i = 1; i < tmp.length - 1; i ++) {
            listCategoryId.push(tmp[i]);
        }

        console.log($('#options').val());


        options = JSON.parse($('#options').val());

        elementDisable('[class^="form_pan"]', 1, false);
        elementDisable('#form_add_option', 0.5, true);


        makeCategoryPan();
        makeOptionPan();

    });



    $(document).on('keydown', '.user_product_material_pan input', function() {
        $(this).css('width', ($(this).val().length + 1) * 2 + 'ch');
    })

    $(document).on('click',"#img_upload_img", function(){
        var input = document.createElement('input');
        input.type = 'file';
        let id = getNesImageId();
        if (id < 0) {
            alert("画像を挿入できません。");
            return;
        }
        input.onchange = e => {
            var file = e.target.files[0];
            var formData = new FormData();
            var filePath = "public/user_product/";
            formData.append('file', file);
            formData.append('filePath', filePath);

            var hostUrl = "{{url('/')}}";
            var postUrl = hostUrl + '/api/v1/client/uploadImgWithPath';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: formData,
                contentType : false,
                enctype: 'multipart/form-data',
                cache: false,
                processData : false,
                success : function (data, status) {
                    const filePathFull = hostUrl + '/' + filePath + data;
                    $("#userProductImage_" + id).attr('src', filePathFull);
                    $("#userProductImage_div_" + id).css('display', 'block');
                    $("#userProductImage_delete_" + id).css('display', 'block');
                    // $("#userProductImageId_" + id).val(data);
                }
            });
        }
        input.click();
    });

    getNesImageId = () => {

        for (let i = 0; i < 20; i ++) {
            if ($('#userProductImage_div_' + i).css('display') == 'none') {
                return i;
            }
        }

        return -1;
    }

    deleteImage = (id) => {
        for (let i = id; i < 19; i ++) {
            $('#userProductImage_div_' + i).css('display', $('#userProductImage_div_' + (i + 1) ).css('display'));
            $('#userProductImage_' + i).attr('src', $('#userProductImage_' + (i + 1) ).attr('src'));

            $('#userProductImage_div_' + (i + 1) ).css('display', 'none');
            $('#userProductImage_' + (i + 1) ).attr('src', "");
        }
    }

    viewModal = () => {

        let cnt = 0;
        $('[id^="categoryCheck_"]').each(function() {
            cnt ++;
            $(this).prop('checked', false);
        });

        for (let i = 0; i < listCategoryId.length; i ++) {
            $('#categoryCheck_' + listCategoryId[i]).prop('checked', true);
        }

        if (cnt == listCategoryId.length) {
            $('#categoryCheckAll').prop('checked', true);
        }

        if ($('#modalAddQuestion').modal) {
            $('#modalAddQuestion').modal('toggle');
        }
    }


    $(document).on('click',"#img_add_option", function(){
        elementDisable('[class^="form_pan"]', 0.5, true);
        elementDisable('#form_add_option', 1, false);

        new Tagify(document.getElementById('option_description_tag'));

    });

    $(document).on('click', "#add_option", function() {

        const name = $('#option_name').val();
        const description = $('#option_description_tag').val();
        if (name.length === 0) {
            alert("オプション名を入力する必要があります。");
            $('#option_name').focus();
            return;
        }

        if (description.length === 0) {
            alert("オプションの内容を入力する必要があります。");
            $('#option_description_tag').focus();
            return;
        }

        const values = JSON.parse(description);

        const rlt = [];
        values.forEach(e => {
            rlt.push(e.value);
        });

        options.push({
            name:name,
            description:rlt
        })

        makeOptionPan();
        elementDisable('[class^="form_pan"]', 1, false);
        elementDisable('#form_add_option', 0.5, true);
    })

    $(document).on('click',"#cancel_option", function(){
        elementDisable('[class^="form_pan"]', 1, false);
        elementDisable('#form_add_option', 0.5, true);
    });



    elementDisable = (elementName, opacity, flag) => {
        $(elementName).each(function() {
            if (flag === false) {
                $(this).css('box-shadow', '0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22)');
            }
            else {
                $(this).css('box-shadow', 'none');
            }
        })

        $(elementName).find('*').each(function() {

            $(this).css('opacity', opacity);
            $(this).attr('disabled', flag);
        })
    }

    addMaterialInput = () => {
        let rlt = "";
        $('[class="input_material"]').each(function() {
            let val = $(this).val();
            rlt += `<input type = 'text' class='input_material' value='${val}'>`;
        });
        rlt += `<input type = 'text' class='input_material' value=''>`;
        rlt += `<input type="button" value= "+" onclick="addMaterialInput()">`;

        $('.user_product_material_pan').html(rlt);
        formatText();
    }



    makeCategoryPan = () => {

        let rlt = "";
        for (let i = 0; i < listCategoryId.length; i ++) {

            let txt = $('#categoryLabel_' + listCategoryId[i]).text()
            rlt += `<div>${txt}</div>`;
        }
        rlt += "<input type='button' value = '+' onclick='viewModal()'>";
        $('.user_product_catetory_pan').html(rlt);

    }

    array2string  = (arr) => {

        let rlt = "";
        arr.forEach((e, i) => {
            if (i > 0) rlt += ", ";
            rlt += e;
        });

        return rlt;
    }

    makeOptionPan = () => {

        let rlt = "";
        options.forEach( e => {
            let tmp = "";
            tmp += "<div class='option_row'>";
            tmp += `<div class="option_name">${e.name}</div>`;
            tmp += `<div class="option_description">${array2string(e.description)}</div>`;
            tmp += "</div>";
            rlt += tmp;
        })
        $('.user_product_option_pan').html(rlt);
    }

    $(document).on('click', '#categoryCheckAll', function() {

        const state = $('#categoryCheckAll').prop('checked');
        console.log(state);
        $('[id^="categoryCheck_"]').each(function() {
            $(this).prop('checked', state);
        });
    });

    $(document).on('change', '[id^="categoryCheck_"]', function() {
        $('#categoryCheckAll').prop('checked', true);
        $('[id^="categoryCheck_"]').each(function() {
            if ($(this).prop('checked') == false) {
                $('#categoryCheckAll').prop('checked', false);
            }
        });
    });

    $(document).on('click', '#btnSaveCategory', function() {
        listCategoryId = [];
        $('[id^="categoryCheck_"]').each(function() {
            if ($(this).prop('checked') == true) {
                let id = $(this).attr('id');
                let category_id = id.split('_')[1];
                listCategoryId.push(category_id);
            }
        });

        makeCategoryPan();

        if ($('#modalAddQuestion').modal) {
            $('#modalAddQuestion').modal('toggle');
        }
    })

    checkData = () => {


        let rlt = "_"
        for (let i = 0; i < 20; i ++) {
            if ($('#userProductImage_div_' + i).css('display') == 'none') break;
            let src = $('#userProductImage_' + i).attr('src');
            src = src.replaceAll('\\', '/')
            let listTmp = src.split("/");
            rlt += listTmp[listTmp.length - 1] + '_';
        }

        $("#img_urls").val(rlt);

        rlt = '_';
        for (let i = 0; i < listCategoryId.length; i ++) {
            rlt += listCategoryId[i] + '_';
        }

        $("#category_ids").val(rlt);

        rlt = "_m_";
        $('[class="input_material"]').each(function() {
            let val = $(this).val();
            if (val.length > 0) rlt += val + '_m_';
        });
        $('#materials').val(rlt);

        $('#options').val(JSON.stringify(options));

        return true;

    }




</script>



@endsection
