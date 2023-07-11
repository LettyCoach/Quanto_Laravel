@extends('layouts.admin', ['title' => 'フォーム作成'])

@section('main-content')
    <script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
    <div style="display: flex; justify-content: space-between; padding-right: 100px;">
        <div class="img-modal-search-bar">
            <input type="text" class="pl-5" id="search_invoice" placeholder="取引先名.."
                style="width: 400px; padding-left: 20px; background: url({{ asset('public/img/img_03/search_one.png') }}) left center no-repeat; ">
        </div>
        <div class="action-container">
            <a class="btn btn-primary" href="{{ route('paper.invoice.new') }}" style="background:#6423fe;">新規追加</a>
        </div>
    </div>
    <div class="mt-2">
        <table class="table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="th_check"></th>
                    <th>お取引先名</th>
                    <th>発行日</th>
                    <th>請求金額</th>
                    <th>有効期限</th>
                    <th>ステイタス</th>
                    <th>取引状況</th>
                    <th style="width:300px;">メモ</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($papers as $paper)
                    <?php
                    $clientHost = \Illuminate\Support\Facades\Config::get('constants.clientHost');
                    ?>
                    <?php $i++; ?>
                    <tr class="show_tr">
                        <td class="text-center">
                            <input type="hidden" id="user_id" value="{{ $paper->id }}">
                            <input type="checkbox" id="td_check_{{ $i }}">
                        </td>
                        <td class="text-center send_name">{{ $paper->send_name }}</td>
                        <td class="text-center">{{ $paper->cDate }}</td>
                        <td class="text-center">{{ $paper->total_price }}円</td>
                        <td class="text-center">{{ $paper->eDate }}</td>
                        <td class="text-center">{{ 'ステイタス' }}</td>
                        <td class="text-center">{{ '取引状況' }}</td>

                        <td class="memotd" style="font-size: 16px;">
                            <form action="{{ route('paper.invoice.memo_edit') }}" method="post">
                                @csrf
                                <div class="w-100 d-flex justify-content-between">
                                    <textarea name="memoText"
                                        style="width: calc(100% - 32px); height: 50px;border:0; background: #e7ebfd; border-radius: 10px; overflow: hidden; line-height: 20px;">{{ $paper->memo_text }}</textarea>
                                    <div name="memoEdit" class="memoEdit"><img
                                            src="{{ asset('public/img/img_03/image_on (1).png') }}"
                                            style="height: 30px; visibility: visible;"></div>
                                    <input type="hidden" name="paperid" value="{{ $paper->id }}">
                                </div>
                            </form>
                        </td>

                        <td>
                            <a class="" href="{{ route('paper.invoice.edit', ['id' => $paper->id]) }}"><img
                                    src="{{ asset('public/img/img_03/pen.png') }}"
                                    style="height: 30px; visibility: visible;"></a>
                            <a class="" href="{{ route('paper.invoice.duplicate', ['id' => $paper->id]) }}"><img
                                    src="{{ asset('public/img/img_03/copy.png') }}"
                                    style="height: 30px; visibility: visible;"></a>
                            <a class=""
                                href="javascript:deleteData('{{ route('paper.invoice.delete', ['id' => $paper->id]) }}')"><img
                                    src="{{ asset('public/img/img_03/delete.png') }}"
                                    style="height: 30px; visibility: visible;"></a>
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
    <style>
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        thead {
            background: rgb(241, 242, 255);
            box-shadow: 5px 5px 10px grey;
            color: #6423fe;
            border-radius: 15px;
        }

        .table td {
            border: 0;
        }

        tr {
            line-height: 50px;
        }

        th {
            text-align: center;
        }

        tr:first-child th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        tr:first-child th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        tbody {
            z-index: -10;
        }
    </style>
    <script>
        $(document).on('change', '#th_check', function() {
            if ($(this).prop('checked') == true) {
                $('[id^="td_check"]').each(function() {
                    $(this).prop('checked', 'true');
                });
            } else {
                $('[id^="td_check"]').each(function() {
                    $(this).prop('checked', '');
                });
            }
        })
        $(document).on('keyup', '#search_invoice', function() {
            var filter = $(this).val();
            $(".show_tr").each(function() {
                var title = $(this).find("td.send_name").text();
                if (title.indexOf(filter) > -1) {
                    $(this).css('display', 'table-row');
                } else {
                    $(this).css('display', 'none');
                }
            });
        });
        $(document).on('click', '.memoEdit', function(e) {
            e.preventDefault();
            $(this).parent().submit();
        });
    </script>
@endsection
