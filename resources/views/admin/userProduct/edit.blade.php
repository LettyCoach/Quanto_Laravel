@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.css">
<link href="{{ asset('public/css/userProduct/edit.css') }}" rel="stylesheet">
    
    @php
        $adminHost = Config::get('constants.adminHost');
    @endphp

<div class="product_mana_pan">
    <div style="display: flex; align-items:center; padding:12px">
        <h2 class="product_add_title" style="margin: 0">{{ $caption }}</h2>
        <input type = "text" class = "form-control" value="{{$adminHost}}/product-view/{{ $model->id }}" style="width: 480px; margin-left:24px" readonly>
    </div>
    
    <form class="" id="userProduct" method="post" action="{{ route('admin.userProduct.save') }}" enctype="multipart/form-data" onsubmit="return checkData()">
        @csrf

        <div class="row m-0">
            <div class="col-8">
                <div class="row m-0">
                    <div class="form_pan">
                        <h4>画像</h4>
                        <div class = "row m-0 flex flex-row justify-content-center">
                            <div class = "user_product_img_pan">
                                <div id = "userProductImage_div_0" class="user_product_img_first">
                                    <img src = "{{ url($model->getImageUrlFirst('add')) }}" id="userProductImage_0" alt = "img" class="upload_view_image" >
                                    <img src = "{{url('public/img/img_03/delete.png')}}" id="delete_image_0" onclick="deleteImage(0)" alt = "img" class = "delete_image">
                                </div>
                            @php
                                $listImageURL = $model->getImageUrls();
                                for ($i = 1; $i < 18; $i ++) {
                                    $style = "";
                                    $src = "";
                                    if ($i >= count($listImageURL)) $style = "display:none";
                                    else $src = $listImageURL[$i]['url'];
                            @endphp
                                    <div id = "userProductImage_div_{{$i}}" class="sub_image_pan" style = "{{$style}}">
                                        <img src = "{{url($src)}}" id="userProductImage_{{$i}}" alt = "img" class="view_image upload_view_image">
                                        <img src = "{{url('public/img/img_03/delete.png')}}" id="delete_image_{{$i}}" onclick="deleteImage({{$i}})" alt = "img" class = "delete_image">
                                    </div>
                            @php
                                }
                            @endphp
                                <div id="img_upload_img_div" class="sub_image_pan" style="display: none">
                                    <img src = "{{url('public/img/img_03/plus_img.png')}}" id="img_upload_img" alt = "img" class = "add_image">
                                </div>
                                <input type="hidden" name="img_urls" id="img_urls" value="{{$model->getImageUrls_JSON()}}">
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
                                <input type = "text" class = "form-control" name = "sku" value="{{$model->sku}}" >
                            </div>
                        </div>

                        <div class="form_pan mt-4">
                            <h4 >商品説明</h4>
                            <div class="row m-0 mt-3">
                                <textarea class = "form-control" name = "detail" id="detail" style = "height : 84px; max-height: 200px" required>{{$model->detail}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 p-0 pl-2">
                        <div class="form_pan">
                            <div class="row m-0 justify-content-between">
                                <h4 >金額</h4>
                                <input type="checkbox" class="switch_2" name="flagPrice2" {{$model->flagPrice2}}>
                            </div>
                            <div class="row m-0 mt-3">
                                <div class="col-6 p-0 pr-2">
                                    <input type = "text" class = "form-control text-center" placeholder="例;希望小売価格" name = "price_txt" value="{{$model->price_txt}}" required>
                                    <input type = "text" class = "form-control text-right mt-2" name = "price" value="{{$model->price}}" required>
                                </div>
                                <div class="col-6 p-0 pl-2">
                                    <input type = "text" class = "form-control text-center" placeholder="例;セール価格" name = "price2_txt" value="{{$model->price2_txt}}" disable>
                                    <input type = "text" class = "form-control text-right mt-2" name = "price2" value="{{$model->price2}}" disable>
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
                                <img src = "{{url('public/img/img_03/plus_img.png')}}" id="img_add_option" alt = "img" class = "add_option">
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
                        <input type = "text" class = "form-control" name = "barcode" value="{{$model->barcode}}"  style="width : calc(100% - 60px)">
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
                        <input type="checkbox" class="switch_2" name="stockLimit" {{ $model->stockLimit }}>
                    </div>
                    <div class="row m-0 mt-3">
                        <h5 >在庫</h5>
                        <input type = "text" class = "form-control" name = "stock" value="{{$model->stock}}">
                    </div>
                </div> -->

                <div class="form_pan mt-4">
                    <div class="row m-0 mt-3 justify-content-between">
                        <h5 >非表示にする</h5>
                        <input type="checkbox" class="switch_2" name="isDisplay" {{$model->isDisplay}}>
                    </div>
                    <div class="row m-0 mt-3 justify-content-center">
                        <input type="button" class="btn btn-outline-primary mr-3" value="キャンセル"  onclick="location.href='{{route('admin.userProducts')}}'">
                        <input type="submit" class="btn btn-primary ml-3" style="background-color:#6423FF" value="保存" >
                    </div>
                </div>
            </div>
        </div>


        <input type = "hidden" name = "id" value = "{{$model->id}}"/>

    </form>

    <!-- Modal -->
    <div class="modal fade" id="modalAddQuestion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="min-width: 600px; width: 600px">
            <div class="modal-content" style="width:600px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">カテゴリー選択</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddQuestion').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style=" display: flex; flex-direction: column; justify-content: center; ">
                    <div class="form-group" style="width: 480px; margin: 0 auto">
                        <div>
                            <input type="checkbox" id = "categoryCheckAll" class="switch_3">
                            <label class="col-form-label"  id = "categoryLabelAll">All Categories</label>
                        </div>
                    </div>
                    <div class="dropQuestion" style="width: 480px; margin: 0 auto">
                        <div class="category_pan">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  onclick="$('#modalAddQuestion').modal('toggle')">閉じる</button>
                    <button type="button" class="btn btn-primary" id="btnSaveCategory">OK</button>
                    <button type="button" class="btn btn-primary" id="btnViewAddCategory">カテゴリー追加</button>
                </div>
                <input type="hidden" id="categories" value="{{$categories}}">
                <input type="hidden" id="container-id">
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="min-width: 280px; width: 280px; min-hegith: 100px; height: 200px">
            <div class="modal-content" style="width:400px; min-height: 200px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">カテゴリー追加</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddCategory').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row m-0 p-0" >
                            <label class="col-form-label">カテゴリ名</label>
                        </div>
                        <div class="row m-0 p-0">
                            <input type = "text" class = "form-control p-3" name = "name" id="category_name" value="" required style="width: 100%; font-size: 18px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  onclick="$('#modalAddCategory').modal('toggle')">閉じる</button>
                    <button type="button" class="btn btn-primary" id="btnAddCategory">OK</button>
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
<script src="https://rawgit.com/jackmoore/autosize/master/dist/autosize.min.js"></script>

<script>

    const hostUrl = "{{url('/')}}";
    const blankUrl = "{{$model->getFullPath("", "blank")}}"
    const editUrl = "{{url('public/img/img_03/pen.png')}}"
    const delUrl = "{{url('public/img/img_03/delete.png')}}"
    var options = [];
    var listCategoryId = [];
    const fileLimit = 18;
    var listImageURL = [];

    $(document).ready(function() {


        $('#userProduct' ).bind( 'keypress keydown keyup', function(e) {
            if (e.keyCode == 13 && e.target.tagName !== "TEXTAREA") {
                e.preventDefault();
            }
        });

        
        listImageURL = JSON.parse($('#img_urls').val());
        displayImageList();

        var tmp = $('#listCategoryId').val().split('_');

        for (let i = 1; i < tmp.length - 1; i ++) {
            listCategoryId.push(tmp[i]);
        }

        [{"name":"Size","description":["size-1","size-2"]},{"name":"Color","description":["color-1","color-2","color-2"]},{"name":"Mat","description":["mat1"]},{"name":"other","description":["other1","other"]},{"name":"サイズ","description":["サイズ","wer"]}]
        options = JSON.parse($('#options').val());
        if (options.length === 0) {
            options = [{
                    name: "カラー",
                    description: []
                },{
                    name: "サイズ",
                    description: []
                },{
                    name: "素材",
                    description: []
                }
            ]
        }

        elementDisable('[class^="form_pan"]', 1, false);
        elementDisable('#form_add_option', 0.5, true);


        createCategoryPan();
        makeCategoryPan();
        makeOptionPan();
        displayAddImage();

        autosize(document.getElementById("detail"));

    });

    const displayImageList = () => {
        listImageURL.forEach((e, i) => {
            const id_div = `userProductImage_div_${i}`;

            const id_img = `userProductImage_${i}`;
            const id_del = `delete_image_${i}`;
            const url = `${hostUrl}/${e.url}`;
            $(`#${id_img}`).attr("src", url);

            console.log(e.state);
            let style_div = "block";
            let style_del = "block";
            if (e.state === "none") {
                style_div = "none";
            }
            if (e.state === "") {
                $(`#${id_div}`).addClass("view-delete-image");
            }
            if (e.state === "blank") {
                $(`#${id_div}`).removeClass("view-delete-image");
                style_del = "none";
            }

            $(`#${id_div}`).css("display", style_div);
            // $(`#${id_del}`).css("display", style_del);
        })
    }



    $(document).on('keydown', '.user_product_material_pan input', function() {
        $(this).css('width', ($(this).val().length + 1) * 2 + 'ch');
    })

    // $(document).on('click', '#userProductImage_div_0', function() {
    //     var input = document.createElement('input');
    //     input.type = 'file';

    //     input.onchange = e => {
    //         var file = e.target.files[0];
    //         var formData = new FormData();
    //         var filePath = "public/user_product/";
    //         formData.append('file', file);
    //         formData.append('filePath', filePath);

    //         var postUrl = hostUrl + '/api/v1/client/uploadImgWithPath';

    //         $.ajax({
    //             type: 'POST',
    //             url: postUrl,
    //             data: formData,
    //             contentType : false,
    //             enctype: 'multipart/form-data',
    //             cache: false,
    //             processData : false,
    //             success : function (data, status) {
    //                 const img_url = `${filePath}${data}`;
    //                 listImageURL[0] = {
    //                     name: data,
    //                     url: img_url,
    //                     state: ''
    //                 }
    //                 displayImageList();
    //             }
    //         });
    //     }
    //     input.click();
    // })
    
    $(document).on('click',".upload_view_image", function(){
        var input = document.createElement('input');
        input.type = 'file';
        input.multiple="true";

        let lastId = getNextImageId();

        let id = $(this).attr('id').split("_")[1];
        id = parseInt(id);

        id = Math.min(id, lastId);

        input.onchange = e => {

            var formData = new FormData();
            var filePath = "public/user_product/";
            formData.append('filePath', filePath);
            for (let i = 0; i < e.target.files.length; i ++) {
                if (id + i >= fileLimit) break;
                var file = e.target.files[i];
                console.log(file);
                formData.append('file[]', file);
            }

            var postUrl = hostUrl + '/api/v1/client/uploadImgWithPathes';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: formData,
                contentType : false,
                enctype: 'multipart/form-data',
                cache: false,
                processData : false,
                success : function (data, status) {
                    let fileNames = JSON.parse(data);
                    for (let i = 0; i < fileNames.length; i ++) {

                        const fileName = fileNames[i];
                        const img_url = `${filePath}${fileName}`;
                        listImageURL[id] = {
                            name: fileName,
                            url: img_url,
                            state: ''
                        }
                        id ++;
                    }
                    displayImageList();
                    displayAddImage();
                }
            });
        }
        input.click();
    });

    $(document).on('click',"#img_upload_img", function(){
        var input = document.createElement('input');
        input.type = 'file';
        input.multiple="true";

        let id = getNextImageId();
        if (id < 0) {
            alert("画像を挿入できません。");
            return;
        }
        input.onchange = e => {

            var formData = new FormData();
            var filePath = "public/user_product/";
            formData.append('filePath', filePath);
            for (let i = 0; i < e.target.files.length; i ++) {
                if (id + i >= fileLimit) break;
                var file = e.target.files[i];
                formData.append('file[]', file);
            }

            var postUrl = hostUrl + '/api/v1/client/uploadImgWithPathes';

            $.ajax({
                type: 'POST',
                url: postUrl,
                data: formData,
                contentType : false,
                enctype: 'multipart/form-data',
                cache: false,
                processData : false,
                success : function (data, status) {
                    let fileNames = JSON.parse(data);
                    for (let i = 0; i < fileNames.length; i ++) {

                        const fileName = fileNames[i];
                        const img_url = `${filePath}${fileName}`;
                        listImageURL[id] = {
                            name: fileName,
                            url: img_url,
                            state: ''
                        }
                        id ++;
                    }
                    displayImageList();
                    displayAddImage();
                }
            });
        }
        input.click();
    });

    getNextImageId = () => {

        for (let i = 0; i < listImageURL.length; i ++) {
            const state = listImageURL[i].state;
            if (state !== '') {
                return i;
            }
        }
        return -1;
    }

    isDisplayAddImag = () => {
        
        for (let i = 0; i < listImageURL.length; i ++) {
            const state = listImageURL[i].state;
            if (state === 'none') {
                return true;
            }
        }
        return false;
    }

    displayAddImage = () => {

        const flag = isDisplayAddImag();
        if (flag === false) {
            $('#img_upload_img_div').css('display', 'none');
        }
        else {
            $('#img_upload_img_div').css('display', 'block');
        }
    }

    deleteImage = (id) => {

        const obj = listImageURL[id];
        const length = listImageURL.length;
        for (i = id + 1; i < length; i++) {
            listImageURL[i - 1] = listImageURL[i];
            if (i - 1 < 10 && listImageURL[i - 1].state !== '') {
                listImageURL[i - 1].state = "blank";
            }
        }
        listImageURL[length - 1] = {
            name: "",
            url: blankUrl,
            state: 'none'
        };
       
        displayImageList();
        displayAddImage();
    }

    createCategoryPan = () => {

        const categories = JSON.parse($('#categories').val());
        
        $('.category_pan').html("");
        categories.forEach(e => {
            let str = "";
            str += "<div style='width:90%; display:flex; justify-content:space-between;'>";
            str += "<div>";
            str += `<input type="checkbox" id = "categoryCheck_${e.id}" class="switch_3">`;
            str += `<label class="col-form-label"  id = "categoryLabel_${e.id}">${e.name}</label>`;
            str += "</div>"
            str += `<div style="width:20px; height:20px">`;
            str += `<img src = "${editUrl}" style="width:20px; height:20px; cursor:pointer" onclick="editCategory(${e.id})" alt = "img" >`;
            str += `<img src = "${delUrl}" style="width:20px; height:20px; cursor:pointer" onclick="deleteCategory(${e.id})" alt = "img" >`;
            str += "</div>"
            str += "</div>"
            $('.category_pan').append(str);
        })
    }


    checkCategories = () => {

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
    }

    viewModal = () => {

        checkCategories();

        if ($('#modalAddQuestion').modal) {
            $('#modalAddQuestion').modal('toggle');
        }
    }

    var option_id = 0;

    $(document).on('click',"#img_add_option", function(){
        option_id = -1;
        $('#option_name').val('');
        $('#option_description_tag').val('');
        elementDisable('[class^="form_pan"]', 0.5, true);
        elementDisable('#form_add_option', 1, false);

        new Tagify(document.getElementById('option_description_tag'));
        $("html, body").animate({ scrollTop: 0 }, "slow");
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

        if (option_id === -1) {
            options.push({
                name:name,
                description:rlt
            })
        }
        else {
            options[option_id] = {
                name:name,
                description:rlt
            }
        }
        

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

    editOption = (id) => {
        option_id = id;
        const option = options[id];
        $('#option_name').val(option.name);
        const rlt = [];
        option.description.forEach(e => {
            rlt.push({value : e});
        });
        $('#option_description_tag').val(JSON.stringify(rlt));
        elementDisable('[class^="form_pan"]', 0.5, true);
        elementDisable('#form_add_option', 1, false);

        new Tagify(document.getElementById('option_description_tag'));
        
        $("html, body").animate({ scrollTop: 0 }, "slow");

    }

    deleteOption = (id) => {
        if (window.confirm("本当に削除しますか？") === false) {
            return;
        }
        options.splice(id, 1);
        makeOptionPan();
    }

    makeOptionPan = () => {

        let rlt = "";
        options.forEach( (e, i) => {
            let tmp = "";
            tmp += "<div class='option_row'>";
            tmp += `<div class="option_name">${e.name}</div>`;
            tmp += `<div class="option_description">`;

            e.description.forEach(v => {
                tmp += `<div>${v}</div>`;
            })

            tmp += `</div>`;
            tmp += `<div class="option_edit"><img src = "${editUrl}" onclick="editOption(${i})" alt = "img" ></div>`;
            tmp += `<div class="option_delete"><img src = "${delUrl}" onclick="deleteOption(${i})" alt = "img" ></div>`;
            tmp += "</div>";
            rlt += tmp;
        })
        $('.user_product_option_pan').html(rlt);
    }

    $(document).on('click', '#categoryCheckAll', function() {

        const state = $('#categoryCheckAll').prop('checked');
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

    $(document).on('mouseenter', '.view-delete-image', function (e) {
        $(this).find(".delete_image").css("display", 'block');

    }).on('mouseleave', '.view-delete-image', function (e) {
        $(this).find(".delete_image").css("display", 'none');
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
    
    var category_id = 0;

    $(document).on('click', '#btnAddCategory', function() {
        
        const id = category_id;
        const name = $('#category_name').val();
        $.get("/admin/userProductCategory/add", {"id" : id, "name" : name}, function(data) {
            
            if (data.state === "SUCCESS") {

                console.log(data.categories);
                $("#categories").val(data.categories);
                createCategoryPan();
                checkCategories();

                if ($('#modalAddCategory').modal) {
                  $('#modalAddCategory').modal('toggle');
                }
                
            }
        })
    })

    editCategory = (id) => {
        category_id = id;
        const txt = $('#categoryLabel_' + id).text();
        $('#category_name').val(txt);
        $('#modalAddCategory').modal('toggle');
    }
    
    deleteCategory = (id) => {
        if (window.confirm("本当に削除しますか？") === false) {
            return;
        }
        
        $.get("/admin/userProductCategory/remove", {"id" : id,}, function(data) {
            
            if (data.state === "SUCCESS") {

                $("#categories").val(data.categories);
                createCategoryPan();
                checkCategories();
            }
        })
    }

    $(document).on('click', '#btnViewAddCategory', function() {
        category_id = 0;
        $('#category_name').val('');
        $('#modalAddCategory').modal('toggle');
    })

    checkData = () => {

        $("#img_urls").val(JSON.stringify(listImageURL));

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
