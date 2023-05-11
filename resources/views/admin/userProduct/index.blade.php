@extends('layouts.admin', ['title' => '商品管理/カテゴリ'])
@section('main-content')

<link href="{{ asset('public/css/userProduct/index.css') }}" rel="stylesheet">

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
                    <th style="width: 10%">単価</th>
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
                        <img src="{{url($model->getImageUrlFirst(true))}}" class="product_first_img" onclick="viewData({{ $model->id }})"/>
                    </td>
                    <td>{{ $model->brandName }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->getCategoryText() }}</td>
                    <td><?php echo $model->getOptionsText(); ?></td>
                    <td>{{ $model->price }}</td>
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
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width : 1200px">
            <div class="modal-content" style="width:1200px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">商品情報</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#modalAddQuestion').modal('toggle')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row m-0 px-2">
                        <div class="col-6 p-0 flex flex-column align-items-center">
                            <img src="{{  url('public/img/img_03/delete.png') }}" alt="" class="main_img">
                            <div class="img_pan">
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                                <img src="{{ url('public/img/img_03/delete.png') }}" alt="" >
                            </div>
                        </div>
                        <div class="col-6 p-0 pr-4" id="info_pan">
                            <div class="row m-0 mt-3" id="productID">デザインTシャツブラック</div>
                            <div class="row m-0 mt-3" id="name">デザインTシャツブラック</div>
                            <div class="row m-0 mt-3" id="price">980デ</div>
                            <div class="row m-0 mt-3" >
                                <div class="col-3 p-0 flex">
                                    <div class="pr-2" style="font-size: 24px">-</div>
                                    <input type="text" style="width : 32px">
                                    <div class="pl-2" style="font-size: 24px">+</div>
                                </div>
                                <div class="col-3 p-0">
                                    <input type="button" class="btn btn-primary" value="キャンセル">
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


    <script>
        var product_id = 0;
        function viewData(id) {
            product_id = id;
            $.get(`/admin/userProduct/show/${id}`, function(data) {
                const obj = JSON.parse(data);
                $('#productID').html(obj.productID);
                $('.main_img').attr('src', obj.main_img);
                $('#name').html(obj.name);
                $('#price').html(obj.price + '円');
                $('#detail').html(obj.detail);
                $('#brandName').html(obj.brandName);
                $('#sku').html(obj.sku);
                let rlt = "";
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

        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;

        }
    </script>
@endsection
