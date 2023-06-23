@extends('layouts.admin', ['title' => 'LP'])
@section('js')
    <script src="{{ asset('public/js/lib/clipboard.min.js') }}"></script>
    <script>
        var btns = document.querySelectorAll('.clipboard');
        var clipboard = new ClipboardJS(btns);

        clipboard.on('success', function (e) {
            $('.clip-toast').addClass('open');
            setTimeout(function() {
                $('.clip-toast').removeClass('open');
            }, 1000);
        });

        clipboard.on('error', function (e) {
            console.log(e);
        });
    </script>
@endsection
@section('main-content')
    <div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('admin.lp.add') }}">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <tbody>
            <?php $adminHost = \Illuminate\Support\Facades\Config::get('constants.adminHost'); ?>
            @foreach($lps as $lp)
                @if ($lp->status == 1)
                    <tr style="background-color: #ddd;">
                @else
                    <tr>
                @endif
                    <td><a class="btn btn-primary" href="{{ route('admin.lp.edit',['id'=>$lp->id]) }}">{{ $lp->title }}</a></td>
                    <td>
                        <a class="btn btn-info clipboard" data-clipboard-text="{{ $adminHost }}/lp/{{ $lp->id }}"
                        title="{{ $adminHost }}/lp/{{ $lp->id }}">共有</a>
                        <a class="btn btn-danger" href="{{ route('admin.lp.delete',['id'=>$lp->id]) }}">削除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $lps->links() }}
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
