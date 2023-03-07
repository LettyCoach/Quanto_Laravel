@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.userProductCategory.add') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>カテゴリ名</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($u_pCategories as $i => $u_pCategory)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $u_pCategory }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('admin.userProductCategory.edit',['id'=>$u_pCategory->id]) }}">編集</a>
                        <a class="btn btn-danger" href="{{ route('admin.userProductCategory.delete',['id'=>$u_pCategory->id]) }}">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $u_pCategories->links() }}
    </div>
    <div class="clip-toast">
        URLをコピーしました。
    </div>
    <style>
        .clip-toast {
            position: fixed;
            font-size: 12px;
            background: #00000088;
            width: fit-content;
            padding: 8px 16px;
            border-radius: 4px;
            right: 50px;
            bottom: 50px;
            opacity: 0;
            visibility: hidden;
            transition: all 300ms cubic-bezier(0.335, 0.01, 0.03, 1.36);
            color: white;
        }
        .open {
            opacity: 1;
            visibility: visible;
        }
    </style>
@endsection
