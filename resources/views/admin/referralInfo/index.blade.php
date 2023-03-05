@extends('layouts.admin', ['title' => '関連情報質問'])
@section('main-content')
    <div>
        <div class="action-container">
            <span class="btn btn-primary" id="btnAddReferralInfo">新規追加</span>
        </div>
    </div>
    <div>
        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Info</th>
                    <th></th>

                </tr>
                </thead>
                <tbody>
                @foreach($infos as $info)
                    <tr>
                        <td>{{ $info->id }}</td>
                        <td>{{ $info->name }}</td>
                        <td>{{ htmlspecialchars_decode($info->info) }}</td>
                        <td>
                            <a href="#" onclick="editReferralInfo({{$info}})"><i class="fa fa-edit"></i></a>
                            <a class="text-danger" href="{{ route('admin.referralInfo.delete',['id'=>$info->id]) }}"><i
                                    class="fa fa-times"></i></a>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
            {{ $infos->links() }}
        </div>
    </div>
    <div class="modal fade" id="modalReferralInfo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">関連情報</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.referralInfo.save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="col-form-label">名前 :</label>
                            <input type="text" class="form-control" name="name" id="txtReferralName" />
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">情報 :</label>
                            <textarea type="text" class="form-control" name="info" id="txtReferralInfo"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary" id="btnReferralInfo">追加</button>
                    </div>
                    <input type="hidden" id="container-id">
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditReferralInfo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">関連情報</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="{{ route('admin.referralInfo.save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body" id="referralInfoEdit">
                        <input type="hidden" class="form-control" name="id" id="txtReferralEditID" />
                        <div class="form-group">
                            <label class="col-form-label">名前 :</label>
                            <input type="text" class="form-control" name="name" id="txtReferralEditName" />
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">情報 :</label>
                            <textarea type="text" class="form-control" name="info" id="txtReferralEditInfo"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary" id="btnReferralEditInfo">変更</button>
                    </div>
                    <input type="hidden" id="container-id">
                </form>
            </div>
        </div>
    </div>

@endsection
