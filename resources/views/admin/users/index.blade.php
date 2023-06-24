@extends('layouts.admin', ['title' => 'ユーザー'])
@section('main-content')
    <link href="{{ asset('public/css/common.css') }}" rel="stylesheet">

    <div class="bg-white p-3">
        <div class="row py-2 d-flex align-items-center">
            <div class="col text-lg font-weight-bold d-flex flex-row align-items-center">
                <p class="m-0 ml-4" style="width: 120px">顧客一覧</p>

                <div class="form-group has-search m-0">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text" class="form-control" placeholder="">
                </div>


            </div>
            <div class="col"></div>
            <div class="col d-flex justify-content-end">
                <a class="btn btn-primary font-weight-bold" href="{{ route('admin.user.create') }}"
                    style="background-color: #6423FF">新規作成</a>
            </div>

        </div>
        <div class="table-container">
            <table class="table data_table">
                <thead>
                    <tr>
                        <th>ユーザID</th>
                        <th>会社名</th>
                        <th>担当者名</th>
                        <th>連絡先</th>
                        <th>メールアドレス</th>
                        <th>お支払い方法</th>
                        <th>サービス開始日</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->getQID() }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->getSettingInfo('member') }}</td>
                            <td>{{ $user->getSettingInfo('phone_number') }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->getSettingInfo('payment_method') }}</td>
                            <td>{{ $user->getCreatedDate() }}</td>
                            <td>
                                <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">
                                    <img src="{{ asset('public/img/img_03/edit_client.png') }}" alt="">
                                </a>
                            </td>
                            <td>
                                <form method="GET" action="{{ route('admin.user.delete', ['id' => $user->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn delete-button p-0">
                                        <img src="{{ asset('public/img/img_03/delete_client.png') }}" alt="">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $users->links() }}
        </div>
    </div>
@endsection


@section('js')
    <script>
        $(".delete-button").click(function(e) {
            e.preventDefault();
            if (confirm("本当に削除しますか？")) {
                $(e.target).closest("form").submit();
            }
        });
    </script>
@endsection
