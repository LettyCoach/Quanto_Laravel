@extends('layouts.admin', ['title' => 'フォーム作成'])

@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('paper.invoice.new') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
            <tr>
                <th>No</th>
                <th>ユーザー名</th>
                <th>タイトル</th>
                <th>種類</th>
                <th>更新日</th>
                <th>担当者</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $i=0; ?>
            @foreach($papers as $paper)
                <?php
                    $clientHost = \Illuminate\Support\Facades\Config::get('constants.clientHost');
                ?>
                <?php $i++; ?>
                <tr>
                    <td class="text-center"><input type="hidden" id="user_id" value="{{ $paper->id }}">{{$i}}</td>
                    <td class="text-center">{{ "$userName" }}</td>
                    <td class="text-center">{{ $paper->subject }}</td>
                    <td class="text-center">{{ $paper->category }}</td>
                    <td class="text-center">{{ substr($paper->created_at,0,-9) }}</td>
                    <td class="text-center">{{ substr($paper->updated_at,0,-9) }}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ route('paper.invoice.edit',['id'=>$paper->id]) }}">編集</a>
                        <a class="btn btn-danger" href="javascript:deleteData('{{ route('paper.invoice.delete',['id'=>$paper->id]) }}')">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    
    <script>
        function deleteData(url) {

            if (window.confirm("本当に削除しますか？") == false) return;
            location.href = url;
            
        }
    </script>

@endsection
