@extends('layouts.admin', ['title' => '商品管理/カテゴリ'])
@section('main-content')

<link href="{{ asset('public/css/userProductCategory/index.css') }}" rel="stylesheet">
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.userProductCategory.create') }}">新規作成</a>
            <input type="text" id="search_category" placeholder="カテゴリー" value="{{ $category }}" onchange="viewList(this.value)">
        </div>
    </div>
    <div class="mt-2 overflow-auto">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 120px">カバー画像</th>
                <th style="width: 12%">カテゴリー</th>
                <th>商品画像</th>
                <th style="width : 120px; min-width: 120px"></th>
            </tr>
            </thead>
            <tbody>

                <tr>
                    <td colspan="4" class="firstTD">　</td>
                </tr>
            @foreach($listModel as $i => $model)
                <tr>
                    <td><img src="{{$model->getImageUrlFullPath(true)}}" class="category_img"/></td>
                    <td>{{ $model->name }}</td>
                    <td>
                        <div class="product_img_pan">
                            @php
                                foreach ($model->productes as $i => $product) {
                                    if ($i > 9) break;
                            @endphp
                            <div>
                            <img src = "{{ $product->getImageUrlFirstFullPath('blank') }}">
                            <p>{{ $product->getShortName() }}</p>
                            </div>
                            @php
                                }
                            @endphp
                        </div>

                    </td>
                    <td>
                        <a href="{{ route('admin.userProductCategory.edit',['id'=>$model->id]) }}">
                            <img src="{{  url('public/img/img_03/pen.png') }}" alt='edit' style="width:28px"/>
                        </a>
                        <a href="{{ route('admin.userProductCategory.duplicate',['id'=>$model->id])}}">
                            <img src="{{  url('public/img/img_03/copy.png') }}" alt='edit' style="width:28px"/>
                        </a>
                        <a href="javascript:deleteData('{{ route('admin.userProductCategory.delete',['id'=>$model->id])}}')">
                            <img src="{{  url('public/img/img_03/delete.png') }}" alt='edit' style="width:28px"/>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $listModel->links() }}
    </div>

    <script>

        document.getElementById("search_category").select();

        const viewList = (value) => {
            location.href=`/admin/userProductCategories?category=${value}`;
        }

        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;

        }
    </script>
@endsection
