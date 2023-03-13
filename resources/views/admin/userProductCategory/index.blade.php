@extends('layouts.admin', ['title' => '商品管理/カテゴリ'])
@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.userProductCategory.create') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>カテゴリ名</th>
                <th>メモ</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($listModel as $i => $model)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $model->name }}</td>
                    <td>{{ $model->other }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('admin.userProductCategory.edit',['id'=>$model->id]) }}">編集</a>
                        <a class="btn btn-danger" href="javascript:deleteData('{{ route('admin.userProductCategory.delete',['id'=>$model->id])}}')">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $listModel->links() }}
    </div>

    <script>
        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;
            
        }
    </script>
@endsection
