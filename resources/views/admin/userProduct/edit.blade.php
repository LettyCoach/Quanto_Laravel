@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script> -->
<link href="{{ asset('public/css/userProduct.css') }}" rel="stylesheet">
@php
    $QId = "";
    if ($model->id > 0)
        $QId = $model->id + 100000;
@endphp

<div class="row">
    <div class="form_pan">
        <form class="" id="userProduct" method="post" action="{{ route('admin.userProduct.save') }}" enctype="multipart/form-data" onsubmit="return checkData()">
            @csrf
    
            <h3 class = "text-center mt-3 font-weight-bold">商品登録</h3>
            <div class = "row mt-3">商品ID</div>
            <div class = "row">
                <input type = "text" class = "form-control" value="Q{{$QId}}" readonly>
            </div>
            <div class = "row mt-3">ブランド名</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "brandName" value="{{$model->brandName}}" required>
            </div>
            <div class = "row mt-3">商品名</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "name" value="{{$model->name}}" required>
            </div>
            <div class = "row mt-3">SKU</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "sku" value="{{$model->sku}}" required>
            </div>
            <div class = "row mt-3">金額</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "price" value="{{$model->price}}" required>
            </div>
            <div class = "row mt-3">画像</div>
            <div class = "row">
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
                    <div>
                        <img src = "{{url('public/img/ic_add.png')}}" id="img_upload_img" alt = "img" class = "add_image">
                    </div>
                    <input type="hidden" name="img_urls" id="img_urls" value="{{$model->img_urls}}">
                </div>
                
            </div>
            <div class = "row mt-3">商品説明</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "detail" value="{{$model->detail}}" required>
            </div>
            <div class = "row mt-3">カテゴリー</div>
            <div class = "row">
                <div class="user_product_catetory_pan">
                    <input type="button" value = "+" onclick="viewModal()">
                </div>
                    <input type="hidden" name="category_ids" id="category_ids" value="{{$model->category_ids}}">
            </div>
            <div class= "row mt-4">
                <div class="col-6">
                    <div class = "row mr-1">カラー</div>
                    <div class = "row mr-1">
                        <select class = "form-control" name="color_id">
                            @foreach($listUPColor as $item)
                            <option value= '{{$item->id}}' {{$item->id == $model->color_id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class = "row ml-1">サイズ</div>
                    <div class = "row ml-1">
                        <select class = "form-control" name="size_id">
                            @foreach($listUPSize as $item)
                            <option value= '{{$item->id}}' {{$item->id == $model->size_id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class = "row mt-3">素材</div>
            <div class = "row">
                @php
                    $listMaterial = $model->getMaterials();
                @endphp
                <div class="user_product_material_pan">
                    @foreach($listMaterial as $item)
                    <input type = "text" class="input_material" value="{{$item}}">
                    @endforeach
                    <input type="button" value= "+" onclick="addMaterialInput()">
                </div>
                    <input type="hidden" name="materials" id="materials" value="{{$model->materials}}">
            </div>

            <div class = "row mt-3">メモ</div>
            <div class = "row">
                <textarea class = "form-control" name = "memo" style = "height : 84px">{{$model->memo}}</textarea>
            </div>
            <div class = "row mt-3">在庫</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "stock" value="{{$model->stock}}">
            </div>
            <div class = "row mt-3" style = "display : flex; justify-content: space-between;">
                在庫数の指定
                <input type="checkbox" class="switch_1" name="stockLimit" {{ $model->stockLimit }}>
            </div>
            <div class = "row mt-3">Barcode</div>
            <div class = "row">
                <input type = "text" class = "form-control" name = "barcode" value="{{$model->barcode}}">
            </div>

            <div class = "row mt-3" style = "display : flex; justify-content: space-between;">
                非表示にする
                <input type="checkbox" class="switch_1" name="isDisplay" {{$model->isDisplay}}>
            </div>
            <div class="row mt-3 justify-content-center" style="align-items: baseline;">
                <input type="submit" class="btn btn-primary mr-3" value="保存" >
                <input type="button" class="btn btn-danger ml-3" value="キャンセル" onclick="location.href='{{route('admin.userProducts')}}'">
            </div>
            <input type = "hidden" name = "id" value = "{{$model->id}}"/>
        </form>
    </div>

    
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
                        <label class="col-form-label"></label>
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

<input type = "hidden" id="listCategoryId" value="{{$model->category_ids}}">

@section('js')
<script>
    
    var listCategoryId = [];
    var tmp = $('#listCategoryId').val().split('_');
    
    for (let i = 1; i < tmp.length - 1; i ++) {
        listCategoryId.push(tmp[i]);
    }

    

    $('#userProduct' ).bind( 'keypress keydown keyup', function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    formatText = () => {
        $('[class="input_material"]').each(function() {
            $(this).css('width', ($(this).val().length + 1) * 2 + 'ch');
        })
    }
    

    $(document).ready(function() {
        formatText();
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

        $('[id^="categoryCheck_"]').each(function() {
            $(this).prop('checked', false);
        });

        for (let i = 0; i < listCategoryId.length; i ++) {
            $('#categoryCheck_' + listCategoryId[i]).prop('checked', true);
        }

        if ($('#modalAddQuestion').modal) {
            $('#modalAddQuestion').modal('toggle');
        }
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

        return true;

    }


    makeCategoryPan();


</script>



@endsection
