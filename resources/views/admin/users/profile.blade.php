@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('プロフィール') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Modal -->
    <a href="#" id="exampleModalBtn" class="btn" data-toggle="modal" data-target="#exampleModal"
        style="display: none;">モーダル</a>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: fit-content; min-width: unset; margin: 25% auto;">
            <div class="modal-content"
                style="height: 300px; min-height: unset; width: 400px; border-radius: 30px; box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 0.3);">
                <div class="modal-body d-flex fd-c align-items-center">
                    <img>
                    <p class="modal-title"></p>
                    <input class="form-control">
                </div>
                <div class="modal-footer" style="justify-content: center; flex-wrap: unset;">
                    <button type="button" class="btn" data-dismiss="modal" style="width: 50%">キャンセル</button>
                    <button id="modal-add" type="button" class="btn" style="width: 50%">追加</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset(Auth::user()->settings)) {
        $userSettings = json_decode(Auth::user()->settings);
        $invoice = isset($userSettings->invoice) ? $userSettings->invoice : '';
        $member = isset($userSettings->member) ? $userSettings->member : '';
        $purpose = isset($userSettings->purpose) ? $userSettings->purpose : '';
        $payment_method = isset($userSettings->payment_method) ? $userSettings->payment_method : '';
        $stamp_url = isset($userSettings->stamp_url) ? $userSettings->stamp_url : '';
    } else {
        $invoice = '';
        $member = '';
        $purpose = '';
        $payment_method = '';
        $stamp_url = '';
    }
    $profile_url = Auth::user()->profile_url != null ? url(Auth::user()->profile_url) : '';
    ?>

    <div class="row">

        <div class="col-lg-4 order-lg-2">

            <div class="card shadow mb-4">
                <div class="card-profile-image mt-4">
                    <form id="file-upload" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="profile_path">
                            <figure class="avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;"
                                data-initial="Photo" style="border: 1px solid gray">

                                @if ($profile_url != '')
                                    <img id="uploaded_url" src="{{ $profile_url }}">
                                @else
                                    <img id="uploaded_url" src="" style="display: none;">
                                @endif
                            </figure>
                        </label>
                        <input type="file" id="profile_path" style="display: none">
                    </form>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h5 class="font-weight-bold">{{ Auth::user()->full_name }}</h5>
                                @if (Auth::user()->isAdmin())
                                    <p>管理者</p>
                                @else
                                    <p>ユーザ</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-profile-image mt-4">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="stamp_path">
                            <figure class="font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;"
                                data-initial="Stamp">

                                @if ($stamp_url != '')
                                    <img id="uploaded_stamp_url" src="{{ url($stamp_url) }}"
                                        style="border-radius: 5px; height: 100%; width: 100%; object-fit: cover; object-position: center;">
                                @else
                                    <img id="uploaded_stamp_url" src="" style="display: none;">
                                @endif
                            </figure>
                        </label>
                        <input type="file" id="stamp_path" style="display: none">
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">アカウント</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="hidden" name="profile_url" id="profile_url" value="{{ $profile_url }}">
                        <input type="hidden" name="stamp_url" id="stamp_url" value="{{ $stamp_url }}">
                        <input type="hidden" name="_method" value="PUT">

                        <h6 class="heading-small text-muted mb-4">ユーザ情報</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label">クライアントID</label>
                                        <input type="text" id="id" class="form-control" name="id"
                                            value="{{ Auth::user()->getQID() }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="full_name">会社名</label>
                                        <input type="text" id="full_name" class="form-control" name="full_name"
                                            placeholder="会社名" value="{{ old('full_name', Auth::user()->full_name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="shop_name">ショップ名</label>
                                        <input type="text" id="shop_name" class="form-control" name="shop_name"
                                            placeholder="ショップ名" value="{{ old('shop_name', Auth::user()->shop_name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="zip_code">郵便番号</label>
                                        <input type="text" id="zip_code" class="form-control" name="zip_code"
                                            placeholder="000-0000" value="{{ old('zip_code', Auth::user()->zip_code) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="form-control-label" for="address">住所</label>
                                        <input type="text" id="address" class="form-control" name="address"
                                            placeholder="東京都千代田区..." value="{{ old('address', Auth::user()->address) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="phone_number">連絡先</label>
                                        <input type="tel" id="phone_number" class="form-control" name="phone_number"
                                            placeholder="000-000-0000"
                                            value="{{ old('phone_number', Auth::user()->phone_number) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="member">担当者一覧</label>
                                        <select class="form-control" name="member">
                                            <option value="">選択なし</option>
                                            <option value="新規追加">新規追加</option>
                                            @foreach ($members as $key => $item)
                                                <option value="{{ $item->name }}"
                                                    {{ $member == $item->name ? 'selected' : '' }}>{{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="invoice">登録番号</label>
                                        <input type="text" id="invoice" class="form-control" name="invoice"
                                            placeholder="T0000000000000" value="{{ old('invoice', $invoice) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="purpose">用途一覧</label>
                                        <select class="form-control" name="purpose">
                                            <option value="">選択なし</option>
                                            <option value="新規追加">新規追加</option>
                                            @foreach ($purposes as $key => $item)
                                                <option value="{{ $item->name }}"
                                                    {{ $purpose == $item->name ? 'selected' : '' }}>{{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="payment_method">お支払方法一覧</label>
                                        <select class="form-control" name="payment_method">
                                            <option value="">選択なし</option>
                                            <option value="新規追加">新規追加</option>
                                            @foreach ($payment_methods as $key => $item)
                                                <option value="{{ $item->name }}"
                                                    {{ $payment_method == $item->name ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">ユーザ名<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="ユーザ名" value="{{ old('name', Auth::user()->name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">メールアドレス<span
                                                class="small text-danger">*</span></label>
                                        <input type="email" id="email" class="form-control" name="email"
                                            placeholder="example@example.com"
                                            value="{{ old('email', Auth::user()->email) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="current_password">現在のパスワード</label>
                                        <input type="password" id="current_password" class="form-control"
                                            name="current_password" placeholder="現在のパスワード">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="new_password">新しいパスワード</label>
                                        <input type="password" id="new_password" class="form-control"
                                            name="new_password" placeholder="新しいパスワード">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="confirm_password">パスワードの確認</label>
                                        <input type="password" id="confirm_password" class="form-control"
                                            name="password_confirmation" placeholder="パスワードの確認">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">保存</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#profile_path, #stamp_path').change(function(e) {
                let file_data = $(this).prop('files')[0];
                let id = $(this).prop('id');
                let formData = new FormData();
                formData.append(id, file_data);
                if (file_data) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('admin/user/upload') }}",
                        data: formData,
                        type: 'post',
                        async: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: (data) => {
                            if (id == 'profile_path') {
                                $('#profile_url').val(data.profile_url);
                                $('#uploaded_url').attr('src', `.${data.profile_url}`);
                                $('#uploaded_url').css('display', 'block');
                            } else {
                                $('#stamp_url').val(data.profile_url);
                                $('#uploaded_stamp_url').attr('src', `.${data.profile_url}`);
                                $('#uploaded_stamp_url').css('display', 'block');
                            }

                            $('figure').css('background-color', 'white');
                            $('figure').css('border', '1px solid grey');
                        }
                    });
                }
            });

            $.fn.exChange = function(callback, fn) {

                // 値取得用関数定義（なければvalueをとる）
                var func = fn || function(r) {
                    return $(r).val();
                };

                $(this).each(function() {

                    // イベントバインド時に入力内容を保持
                    var val = func.apply(this);

                    // changeイベントにバインド
                    $(this).change(function() {

                        // 発火前にargumentsの末尾に変更前の値を入れる
                        Array.prototype.push.call(arguments, val);

                        // イベントを発火
                        callback.apply(this, arguments);

                        // 終わったら更新しておく
                        val = func($(this));
                    });
                });
            }

            $('[name=payment_method],[name=member],[name=purpose]').exChange((e, prev) => {
                if ($(e.target).val() == "新規追加") {
                    var __name = $(e.target).prop('name');
                    if (__name == "payment_method") {
                        $('#exampleModal .modal-title').text("支払方法を入力してください。");
                        prev = prev === undefined ? "{{ $payment_method }}" : prev;
                    } else if (__name == "member") {
                        $('#exampleModal .modal-title').text("担当者名を入力してください。");
                        prev = prev === undefined ? "{{ $member }}" : prev;
                    } else if (__name == "purpose") {
                        $('#exampleModal .modal-title').text("用途名を入力してください。");
                        prev = prev === undefined ? "{{ $purpose }}" : prev;
                    }
                    $(e.target).val(prev);
                    $('#exampleModal input').val("");
                    $('#modal-add').data('name', __name);
                    $('#exampleModalBtn').click();
                }
            });

            $('#modal-add').on('click', (e) => {
                if ($('#exampleModal input').val() == '') return;
                var __name = $(e.target).data('name');
                var url = "{{ url('profile') }}" + "/" + __name;
                var formData = new FormData();
                formData.append('data', $('#exampleModal input').val());
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    data: formData,
                    type: 'post',
                    async: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
@endsection
