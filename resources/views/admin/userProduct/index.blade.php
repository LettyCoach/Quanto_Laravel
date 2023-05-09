@extends('layouts.admin', ['title' => '商品管理/カテゴリ'])
@section('main-content')

    <style>
        td {
            vertical-align: middle !important;
            text-align : center;
            font-size : 12px;
        }
    </style>
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
                    <th style="width: 20%">ブランド名</th>
                    <th style="width: 30%">商品名</th>
                    <th style="width: 12%">カテゴリー</th>
                    <th style="width: 20%">オプション<br>（カラー/サイズ/素材）</th>
                    <th>単価</th>
                    <th style="width: 16%"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($models as $i => $model)
                <tr>
                    <td>{{ $model->getProductID() }}</td>
                    <td><img src="{{url($model->getImageUrlFirst())}}"  style="width : 50px; height : 50px"/></td>
                    <td>{{ $model->brandName }}</td>
                    <td>{{ $model->name }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $model->price }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('admin.userProduct.edit',['id'=>$model->id]) }}">編集</a>
                        <a class="btn btn-danger" href="javascript:deleteData('{{ route('admin.userProduct.delete',['id'=>$model->id])}}')">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $models->links() }}
    </div>


    <script>
        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;

        }
    </script>
@endsection
