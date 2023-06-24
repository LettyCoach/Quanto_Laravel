@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800" style="font-family: yu gothic">{{ __('クライアントの変更') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.user.update') }}" autocomplete="off">

        @csrf
        <div class="row">
            <div class="col-lg-9 order-lg-1">

                <div class="card shadow mb-4">

                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-lg" style="color: #6423FF">アカウント</h6>
                    </div>

                    <div class="card-body">


                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label">クライアントID</label>
                                        <input type="text" id="id" class="form-control"
                                            value="{{ $model->getQID() }}" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="shop_name">業種</label>
                                        <input type="text" id="shop_name" class="form-control" name="shop_name"
                                            placeholder="業種" value="{{ old('shop_name', $model->shop_name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="full_name">会社名</label>
                                        <input type="text" id="full_name" class="form-control" name="full_name"
                                            placeholder="会社名" value="{{ old('full_name', $model->full_name) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="zip_code">郵便番号</label>
                                        <input type="text" id="zip_code" class="form-control" name="zip_code"
                                            placeholder="000-0000" value="{{ old('zip_code', $model->zip_code) }}">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-control-label" for="prefecture">都道府県</label>
                                        <select class="form-control" name="prefecture">
                                            <option value="">選択なし</option>
                                            @foreach (Config::get('app.prefectures') as $k => $v)
                                                <option value="{{ $v }}"
                                                    {{ old('prefecture', $model->prefecture) == $v ? 'selected' : '' }}>
                                                    {{ $v }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="address">住所</label>
                                        <input type="text" id="address" class="form-control" name="address"
                                            placeholder="東京都千代田区..." value="{{ old('address', $model->address) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="phone_number">連絡先</label>
                                        <input type="tel" id="phone_number" class="form-control" name="phone_number"
                                            placeholder="000-000-0000"
                                            value="{{ old('phone_number', $model->getSettingInfo('phone_number')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="department">担当部署</label>
                                        <input type="tel" id="department" class="form-control" name="department"
                                            placeholder=""
                                            value="{{ old('department', $model->getSettingInfo('department')) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="job_position">役職</label>
                                        <input type="tel" id="job_position" class="form-control" name="job_position"
                                            placeholder=""
                                            value="{{ old('job_position', $model->getSettingInfo('job_position')) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="member">担当者名</label>
                                        <input type="tel" id="member" class="form-control" name="member"
                                            placeholder="" value="{{ old('member', $model->getSettingInfo('member')) }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-control-label" for="invoice">登録番号</label>
                                        <input type="text" id="invoice" class="form-control" name="invoice"
                                            placeholder="T0000000000000"
                                            value="{{ old('invoice', $model->getSettingInfo('invoice')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="purpose">用途</label>
                                        <input type="text" id="purpose" class="form-control" name="purpose"
                                            placeholder=""
                                            value="{{ old('purpose', $model->getSettingInfo('purpose')) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="payment_method">お支払方法</label>
                                        <input type="text" id="payment_method" class="form-control"
                                            name="payment_method" placeholder=""
                                            value="{{ old('payment_method', $model->getSettingInfo('payment_method')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">ユーザ名<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="ユーザ名" value="{{ old('name', $model->name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="form-control-label" for="email">メールアドレス<span
                                                class="small text-danger">*</span></label>
                                        <input type="email" id="email" class="form-control" name="email"
                                            placeholder="example@example.com" value="{{ old('email', $model->email) }}">
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
                                    <button type="submit" class="btn btn-primary px-4 font-weight-bold"
                                        style="background-color: #6423FF">保 存</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 order-lg-2">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-4 font-weight-bold text-lg" style="color: #6423FF"></h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="charge_member_name">貴社担当者名</label>
                                    <input type="tel" id="charge_member_name" class="form-control"
                                        name="charge_member_name" placeholder=""
                                        value="{{ old('charge_member_name', $model->charge_member_name) }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <h1 class="h4 mt-5 text-gray-800" style="font-family: yu gothic">{{ __('権限設定') }}</h1>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group mt-4">
                                    <input type="radio" value="1" id="ec_role1" name="ec_role"
                                        {{ $model->ec_role == 1 ? 'checked' : '' }} />
                                    <label for="ec_role1">Supplier/サプライヤー</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" value="2" id="ec_role2" name="ec_role"
                                        {{ $model->ec_role == 2 ? 'checked' : '' }} />
                                    <label for="ec_role2">Buyer/バイヤー</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h1 class="h4 mt-5 text-gray-800" style="font-family: yu gothic">{{ __('メモ') }}</h1>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row p-0 m-0">
                            <div class="col-lg-12 p-0 m-0">
                                <textarea name="memo" class="form-control rounded " style="width:100%; min-height: 96px">{{ old('memo', $model->memo) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="{{ $model->id }}">
            <input type="hidden" name="profile_url" value="{{ $model->profile_url }}">
            <input type="hidden" name="stamp_url" value="{{ $model->stamp_url }}">
        </div>

    </form>
@endsection

@section('js')
    <script></script>
@endsection
