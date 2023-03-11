@extends('layouts.admin', ['title' => '商品管理/カテゴリー'])
@section('main-content')
<div class="row">
    <div class="col-8">
        <form class="" id="survey" method="post" action="{{ route('admin.userProductCategory.save') }}" enctype="multipart/form-data">
            @csrf
            <div class="row" style="align-items: baseline;">
                <input id="saveSurvey" type="submit" class="btn btn-primary float-left" value="保存" style="margin-right:50px">
            </div>
            <div class="row mt-5" >
                <div style = "width : 100px"></div>
                <div class="d-flex col-2" >
                    <label class="col-form-label mr-1">カテゴリ名：</label>
                </div>
                <div class="d-flex col-6">
                    <input type = "text" class = "form-control" name = "name" value="{{$u_pCategory->name}}" required>
                </div>
            </div>
            <div class="row mt-3">
                <div style = "width : 100px"></div>
                <div class="d-flex col-2" >
                    <label class="col-form-label mr-1">メモ：</label>
                </div>
                <div class="d-flex col-6">
                    <textarea class = "form-control" name = "other" style="height : 160px">{{$u_pCategory->other}}</textarea>
                </div>
            </div>
            <input type = "hidden" name = "id" value = "{{$u_pCategory->id}}"/>
        </form>
    </div>
</div>

@endsection
