<!doctype html>
<html lang="jp">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" media="all" href="{{ asset('public/css/lp/ress.min.css') }}" />
    <link rel="stylesheet" media="all" href="{{ asset('public/css/lp/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/lib/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">

    <!-- Slick CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/slick/css/slick-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/lib/slick/css/slick.css') }}">

    <style>
        /*cyberbrain追加*/
        h1 {
            margin-bottom: 0;
            padding-left: 7px;
            font-size: 2.5rem;
        }

        h2,
        h3,
        h4,
        h5 {
            margin-bottom: 0;
            font-size: 2.5rem;
        }

        section {
            margin: 0;
        }

        /* トップ */

        .save-buttons {
            z-index: 2;
        }

        .save-buttons button {
            background-color: #FFF;
        }

        .save-buttons button {
            font-size: 1.5rem;
            padding: 0 10px;
        }

        .prof-label.posi1 {
            display: flex;
            justify-content: center;
        }

        .prof-img {
            padding: 10px;
            height: 150px;
            width: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        @media (max-width: 900px) and (min-width: 769px) {
            .prof-label {
                padding: 3rem;
            }

            .desc-wrapper.posi2 {
                padding-left: 3rem !important;
            }
        }

        /*アイコンサイズ調整*/
        .prof-img.size2 {
            height: 200px !important;
            width: 200px !important;
        }

        @media (max-width: 420px) {
            .prof-img.size1 {
                width: 100px !important;
                height: 100px !important;
            }

            .prof-img.size2 {
                width: 150px !important;
                height: 150px !important;
            }
        }

        /*アイコン位置調整*/

        #title-id-wrapper {
            position: relative;
        }

        #prof-label.posi2 {
            position: absolute !important;
            top: 0;
            z-index: 1;
            transform: translateY(-50%);
        }

        #desctxtarea.size1.posi2 {
            margin-top: 30px;
        }

        textarea#desctxtarea {
            min-height: 60px;
            margin-bottom: 0;
        }

        #desctxtarea.size2.posi2 {
            margin-top: 60px;
        }

        .first-sec {
            margin-top: 0;
            padding-top: 0;
            padding-bottom: 0;
        }

        .first-sec.posi2 {
            padding-top: 1rem;
        }

        .title-input {
            padding-left: 10px;
        }

        .tel-mail-wrapper a img {
            margin-right: 5px;
        }

        @if (!$isEdit)
            .desc-wrapper.size2.posi2 {
                padding-top: 100px;
            }

            .desc-wrapper.size1.posi2 {
                padding-top: 70px;
            }
        @endif

        @media (max-width: 900px) {
            .prof-area {
                display: block !important;
            }

            .prof-label {
                width: 100%;
            }

            .desc-wrapper {
                width: 100%;
            }

            .desc-wrapper.posi2 {
                padding-left: 5px;
            }

            #desctxtarea {
                width: 99%;
            }

            .size-posi-select {
                text-align: center;
            }

            .tel-mail-wrapper {
                margin-top: 1rem;
                justify-content: center;
            }
        }

        @media(max-width: 768px) {

            .container.center>.row.contents {
                margin: 0;
            }

            .container {
                padding: 0;
            }

            .title-input {
                padding-left: 10px;
            }

            h1 {
                padding-left: 0;
            }

            .image-slider-wrapper {
                padding: 0;
            }

            .tel-mail-wrapper a {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .tel-mail-wrapper a img {
                display: block;
            }

        }

        @media (min-width: 576px) {

            .modal-dialog {
                max-width: 90%;
                width: fit-content;
            }

            img#modal-image {
                height: fit-content;
                max-height: 90vh;
            }

        }

        .swiper {
            height: 500px;
        }

        .swiper--wrapper {
            width: 100%;
            height: 500px;
            object-fit: cover;
            object-position: center;
        }

        .swiper-slide {
            color: #ffffff;
            width: 100%;
            height: 100%;
            text-align: center;
            line-height: 500px;
            text-align: center;
        }

        .swiper-slide:nth-child(3n + 1) {
            background-color: #de4439;
        }

        .swiper-slide:nth-child(3n + 2) {
            background-color: #fcd500;
        }

        .swiper-slide:nth-child(3n + 3) {
            background-color: #53c638;
        }

        .swiper-horizontal>.swiper-pagination-bullets,
        .swiper-pagination-bullets.swiper-pagination-horizontal,
        .swiper-pagination-custom,
        .swiper-pagination-fraction {
            top: 220px;
        }

        .swiper-pagination-bullets {
            width: 200px;
            top: unset !important;
            bottom: 0px !important;
        }


        /* 3カラム */
        .slick-slider {
            position: relative;

            display: block;
            box-sizing: border-box;

            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;

            -webkit-touch-callout: none;
            -khtml-user-select: none;
            -ms-touch-action: pan-y;
            touch-action: pan-y;
            -webkit-tap-highlight-color: transparent;
        }

        .slick-list {
            position: relative;

            display: block;
            overflow: hidden;

            margin: 0;
            padding: 0;
        }

        .slick-list:focus {
            outline: none;
        }

        .slick-list.dragging {
            cursor: pointer;
            cursor: hand;
        }

        .slick-slider .slick-track,
        .slick-slider .slick-list {
            -webkit-transform: translate3d(0, 0, 0);
            -moz-transform: translate3d(0, 0, 0);
            -ms-transform: translate3d(0, 0, 0);
            -o-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
        }

        .slider-area {
            padding: 0 !important;
            padding-bottom: 3rem !important;
        }

        .slider {
            width: 100%;
            margin: 0 auto 0;
        }

        @if ($isEdit)
            .slider {
                margin: 30px auto 0;
            }
        @endif

        .slider img {
            width: 100%;
            height: auto;
        }

        .slick-slide input[type=file] {
            margin-left: 2rem;
            font-size: 9px;
        }

        .slider .slick-slide {
            margin: 0 10px;
            position: relative;
        }

        .slick-slide>p {
            margin-bottom: 0;
            margin-top: 0.8rem;
            display: inline-flex;
            justify-content: center;
            text-align: left;
        }

        .slick-arrow {
            z-index: 2;
        }

        .slick-prev,
        .slick-next {
            position: absolute;
            top: 50%;
            cursor: pointer;
            outline: none;
            width: 30px;
            height: 30px;
        }

        .slick-prev {
            left: 0px !important;
            background-image: url('/public/img/lp-tmp/left.png') !important;
            background-repeat: no-repeat !important;
            background-size: contain !important;
            opacity: 0.8;
        }

        .slick-next {
            right: 0px !important;
            background-image: url('/public/img/lp-tmp/light.png') !important;
            background-repeat: no-repeat !important;
            background-size: contain !important;
            opacity: 0.5;
        }

        .slick-prev:hover,
        .slick-next:hover {
            opacity: 1;
        }

        .slick-prev:before {
            display: none;
        }

        .slick-next:before {
            display: none;
        }

        .slick-dots {
            text-align: center;
            margin: 20px 0 0 0;
        }

        .slick-dots li {
            display: inline-block;
            margin: 0 5px;
        }

        .slick-dots button {
            color: transparent;
            outline: none;
            width: 8px;
            height: 8px;
            display: block;
            border-radius: 50%;
            background: #ccc;
        }

        .slick-dots .slick-active button {
            background: #333;
        }

        .slick-slide img {
            width: 100%;
            border: none;
            padding: 0;
            box-shadow: 0px 0px 10px -5px #1f1f1f;
        }

        .slider-txt {
            padding: 10px;
        }

        .slider-txt h4 {
            font-weight: bold;
            padding-bottom: 10px;
            line-height: normal;
        }

        .slider-txt .btn-wrap {
            text-align: center;
            padding: 15px 0px;
        }

        .image-slider-wrapper img {
            object-fit: cover;
            border-radius: 10px;
            margin: auto;
        }

        /*メイン画像削除ボタン*/
        .btn-del-mainimg {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 2rem;
            border: none;
            overflow: hidden;
            margin: 1rem;
        }

        /*地図*/
        #dispmap iframe {
            width: 100%;
            height: 400px;
        }

        /*モーダル*/
        #modal-pager {
            display: flex;
            position: fixed;
            top: 50%;
            left: 0;
            width: 100%;
            justify-content: space-between;
        }

        #myModal {
            padding-right: 0 !important;
        }

        #modal-pager button {
            border: none;
            color: #FFFFFF;
            font-size: 3rem;
            animation: fadein 1s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadein {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <?php
    $adminHost = \Illuminate\Support\Facades\Config::get('constants.adminHost');
    $lpSettings = isset($lp->settings) ? json_decode($lp->settings) : [];
    $mainimgs = isset($lpSettings->mainimgs) && $lpSettings->mainimgs != 'null' ? json_decode($lpSettings->mainimgs) : ['public/img/lp-tmp/product.jpg'];
    $profile_url = isset($lpSettings->profile_url) ? $lpSettings->profile_url : '';
    $twitter_url = isset($lpSettings->twitter_url) ? $lpSettings->twitter_url : '';
    $facebook_url = isset($lpSettings->facebook_url) ? $lpSettings->facebook_url : '';
    $instagram_url = isset($lpSettings->instagram_url) ? $lpSettings->instagram_url : '';
    $shop_info = isset($lpSettings->shop_info) ? $lpSettings->shop_info : '';
    $shop_intro = isset($lpSettings->shop_intro) ? $lpSettings->shop_intro : '';
    $map_html = isset($lpSettings->map_html) ? $lpSettings->map_html : '';
    
    $icon_position = isset($lpSettings->icon_position) ? $lpSettings->icon_position : '1';
    $icon_size = isset($lpSettings->icon_size) ? $lpSettings->icon_size : '1';
    ?>
    @if ($isEdit)
        <title>{{ $lp->title }} - 編集ページ</title>
    @else
        <title>{{ $lp->title }}</title>
    @endif

    <script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>

    <!--bootstrapのjs-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>

    <!-- slickのJavaScript -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('public/lib/slick/js/slick.min.js') }}"></script>

    <script>
        function setThumbnailHeight() {
            var thumbnails = document.querySelectorAll('.img-thumbnail');
            var width = thumbnails[0].offsetWidth;
            for (var i = 0; i < thumbnails.length; i++) {

                if (thumbnails[i].classList.contains('layout-2-item')) { // layout-2クラスがある場合
                    thumbnails[i].style.height = (width * 16 / 9) + 'px'; // 横幅の9:16の比率で高さを設定
                } else { // その他の場合
                    thumbnails[i].style.height = width + 'px'; // 横幅と同じ高さに設定
                }
            }
        }

        // ページ読み込み時にサムネイルの高さを設定する
        window.addEventListener('load', function() {
            setThumbnailHeight();
        });

        // 画面サイズ変更時にサムネイルの高さを再設定する
        window.addEventListener('resize', function() {
            setThumbnailHeight();
        });
    </script>
    <script>
        input_type_file = function(e) {
            let file_data = $(e.target).prop('files')[0];
            let formData = new FormData();
            formData.append("file", file_data);
            if (file_data) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('admin/lp/upload') }}",
                    data: formData,
                    type: 'post',
                    async: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {
                        $(e.target).prev().val(data.url);
                        console.log($(e.target).next());
                        $(e.target).next().attr('src', `{{ $adminHost }}${data.url}`);
                    }
                });
            }
        }

        $(document).ready(function() {
            $('.btn-add-content').click(function(e) {
                let formData = new FormData();
                let type = $(this).data('type');
                let trgt_slide = $(this).parent().prev('.slider');
                formData.append("lp_id", "{{ $lp['id'] }}");
                formData.append("type", type);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('admin/lp/content') }}",
                    data: formData,
                    type: 'post',
                    async: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {

                        var newSlide = $(`<li class="content-wrapper" data-content-id="${data.id}">
                        <input type="hidden" name="contents[${data.id}][type]" value="${type}">
              <button type="button" class="btn btn-danger position-absolute" onclick="deleteContent(event)" style="height: unset; right: 0;"><i class="fa fa-times"></i></button>
                        <input type="hidden" name="contents[${data.id}][url]" value="public/img/lp-tmp/product.jpg">
                        <input type="file" id="content_file_${data.id}" class="" onchange="input_type_file(event)">
                        <img src="{{ asset('public/img/lp-tmp/product.jpg') }}" alt="" class="img-thumbnail" data-bs-toggle="modal"
                             data-bs-target="#myModal">
                        <textarea name="contents[${data.id}][description]" placeholder="ここに説明文が入ります"></textarea>
                    </li>`);

                        // スライド要素をslick-sliderに追加
                        trgt_slide.slick('slickAdd', newSlide);

                        // 追加したスライドの通番を取得
                        let addSlidelideNum = trgt_slide.slick('getSlick').slideCount - 3;
                        if (addSlidelideNum < 0) addSlidelideNum = 0;
                        //console.log(addSlidelideNum);

                        trgt_slide.slick('slickGoTo', addSlidelideNum, false);
                        //console.log($(this).closest('.slider-area').find('.slider'));

                        //console.log($(this).parent().prev('.slider')[0]);

                    }
                });
            });

            $('.btn-add-mainimg').click(function(e) {

                $('.swiper-wrapper').append(`
          <div class="swiper-slide" id="swiper-slide-${mainimg_id}">
            <label for="mainimg_${mainimg_id}">
              <input type="hidden" name="mainimgs[${mainimg_id}]" value="public/img/lp-tmp/mainimg.jpg">
              <input type="file" id="mainimg_${mainimg_id}" class="d-none" onchange="input_type_file(event)">
              <img src="{{ asset('public/img/lp-tmp/mainimg.jpg') }}" class="pointer" alt="メイン画像" style="height: 500px; object-fit: cover; object-position: center;">
            </label>
            <button type="button" class="btn-del-mainimg" data-trgt="${mainimg_id}"><i class="fas fa-times-circle"></i></button>
          </div>`);
                mainimg_id++;
                swiper.update();
            });

            $(document).on('click', '.btn-del-mainimg', function(e) {

                var target_id = $(this).data('trgt');
                var target_elem = $('#swiper-slide-' + target_id);
                target_elem.remove();
                mainimg_id--;
                swiper.update();

            });

            var topBtn = $('#pagetop');
            topBtn.hide();
            //スクロールが300に達したらボタン表示
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    topBtn.fadeIn();
                } else {
                    topBtn.fadeOut();
                }
            });
            //スクロールでトップへもどる
            topBtn.click(function() {
                $('body,html').animate({
                    scrollTop: 0
                }, 500);
                return false;
            });

            deleteContent = function(e) {
                let li = $(e.target).closest('li');
                let targetIndex = li.attr('data-slick-index');

                let wrapper = $(e.target).closest('.content-wrapper');
                //let trgt_slide = $(e.target).closest('.slick');
                let trgt_slide = $(e.target).closest('.slider');

                let formData = new FormData();
                formData.append("content_id", wrapper.data('content-id'));

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('admin/lp/contentd') }}",
                    data: formData,
                    type: 'post',
                    async: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: () => {

                        //htmlタグ削除処理
                        let dell_html_result = trgt_slide.slick('slickRemove', targetIndex, false);

                        if (dell_html_result === false) {
                            alert('削除結果を反映する為、一度ページを保存します。');
                            $('#saveButton').click();
                        }


                    },
                    error: function(xhr, status, error) {
                        // error時の処理

                        // console.log(xhr.responseText);
                        alert('削除結果を反映する為、一度ページを保存します。');
                        $('#saveButton').click();


                    }
                });
            }
        });
    </script>

    <!-- cyberbrain追加JS -->
    <script>
        $(function() {

            $('.slider').on('init', function() {
                $('.slick-cloned input, .slick-cloned textarea').remove();
            });


            $('.slider').slick({
                autoplay: false,
                infinite: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                dots: false,
                responsive: [{
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                }],
                beforeChange: function(event, slick, currentSlide, nextSlide) {
                    slick.$slides.find('input, textarea').each(function() {
                        if ($(this).closest('.slick-cloned').length) {
                            // クローンされたスライドの場合、値を取り除く
                            $(this).remove();
                        }
                    });
                }
            });
        });
    </script>

    <!-- アイコンサイズ調整 -->
    <script>
        $(document).ready(function() {
            $('#icon-size-select').on('change', function() {
                var selectedValue = $(this).val();
                $('.desc-wrapper,.prof-img,#desctxtarea,#prof-label').removeClass('size1 size2').addClass(
                    'size' + selectedValue);
            });
        });
    </script>
    <!-- //アイコンサイズ調整 -->

    <!-- アイコン位置調整 -->
    <script>
        $(document).ready(function() {
            $('#icon-position-select').on('change', function() {
                var selectedValue = $(this).val();
                $('.desc-wrapper,.prof-img,#desctxtarea,#prof-label').removeClass('posi1 posi2').addClass(
                    'posi' + selectedValue);
            });
        });
    </script>
    <!-- //アイコン位置調整 -->

    <!-- モーダル内のprev,next -->
    <script>
        $(document).ready(function() {

            // img要素をクリックしたら、そのsrcをモーダルの画像srcに設定する
            $('.img-thumbnail').on('click', function() {
                var $this = $(this);
                console.log($this);
                var $parent = $this.closest('ul');
                console.log($parent);
                var imageList = $parent.find('img');
                console.log(imageList);
                var currentImage = imageList.index(this);
                console.log(currentImage);
                var modalImage = document.getElementById('modal-image');
                console.log(modalImage);
                modalImage.src = this.src;

                // prev,nextボタンのクリックイベントを設定する
                $('.modal-prev').on('click', function() {
                    currentImage = (currentImage > 0) ? currentImage - 1 : imageList.length - 1;
                    modalImage.src = imageList.eq(currentImage).attr('src');
                });

                $('.modal-next').on('click', function() {
                    currentImage = (currentImage < imageList.length - 1) ? currentImage + 1 : 0;
                    modalImage.src = imageList.eq(currentImage).attr('src');
                });
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $("#saveButton").on('click', function() {

                $('.slick-cloned input, .slick-cloned textarea').remove();
                $('#mainform').submit();
            });


        });
    </script>

</head>

<body>
    @if ($isEdit)
        <form id="mainform" method="post" action="{{ route('admin.lp.save') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $lp['id'] }}">
    @endif
    <div class="mainimg">
        <?php $mainimg_id = 0; ?>
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach ($mainimgs as $url)
                    <div class="swiper-slide" id="swiper-slide-{{ $mainimg_id }}">
                        @if ($isEdit)
                            <label for="mainimg_{{ $mainimg_id }}">

                                <input type="hidden" name="mainimgs[{{ $mainimg_id }}]"
                                    value="{{ $url }}">
                                <input type="file" id="mainimg_{{ $mainimg_id }}" class="d-none"
                                    onchange="input_type_file(event)">

                                <img src="{{ asset($url) }}" class="pointer" alt="メイン画像"
                                    style="height: 500px; object-fit: cover; object-position: center;">

                            </label>
                            <button type="button" class="btn-del-mainimg" data-trgt="{{ $mainimg_id }}"><i
                                    class="fas fa-times-circle"></i></button>
                        @else
                            <img src="{{ asset($url) }}" class="pointer" alt="メイン画像"
                                style="height: 500px; object-fit: cover; object-position: center;">
                        @endif
                    </div>
                    <?php $mainimg_id++; ?>
                @endforeach
                <?php echo '<script>var mainimg_id = ' . $mainimg_id . '</script>'; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <div id="title-id-wrapper">
        @if ($isEdit)
            <div class="d-flex justify-content-around align-items-center">
                <div class="d-flex pt-3 save-buttons">
                    <a href="{{ url('admin/lps') }}">
                        <button type="button">戻る</button>
                    </a>
                    <button id="saveButton" type="button" class="mx-3">保存</button>
                    <button type="button" class="btn-add-mainimg slick-change">追加</button>
                </div>

            </div>
        @endif
        <div class="d-flex justify-content-around align-items-center prof-area">
            <div class="d-flex align-items-center prof-area-inner">
                @if ($isEdit)
                    <label for="profile_url" id="prof-label"
                        class="prof-label size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}">
                        <input type="hidden" name="profile_url" value="{{ $profile_url }}">
                        <input type="file" id="profile_url" class="d-none" onchange="input_type_file(event)">

                        <img src="{{ $profile_url != '' ? asset($profile_url) : asset('public/img/lp-tmp/default_profile.png') }}"
                            class="pointer prof-img  size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}"
                            alt="プロフィール画像">
                    </label>
                @else
                    <span id="prof-label"
                        class="prof-label size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}">
                        <img src="{{ $profile_url != '' ? asset($profile_url) : asset('public/img/lp-tmp/default_profile.png') }}"
                            class="pointer prof-img  size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}"
                            alt="プロフィール画像">
                    </span>
                @endif
                <div
                    class="desc-wrapper size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}">
                    <div class="desc-wrapper-inner">
                        @if ($isEdit)
                            <textarea id="desctxtarea"
                                class=" size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}"
                                name="description" style="resize: auto;" placeholder="自己紹介">{{ $lp->description }}</textarea>
                        @else
                            {!! nl2br(e($lp->description)) !!}
                        @endif
                    </div>
                    @if ($isEdit)
                        <div class="size-posi-select">
                            <select id="icon-size-select" name="icon_size">
                                <option value="1"{{ str_replace('"', '', $icon_size) == 1 ? ' selected' : '' }}>
                                    小サイズ</option>
                                <option value="2"{{ str_replace('"', '', $icon_size) == 2 ? ' selected' : '' }}>
                                    大サイズ</option>
                            </select>
                            <select id="icon-position-select" name="icon_position">
                                <option
                                    value="1"{{ str_replace('"', '', $icon_position) == 1 ? ' selected' : '' }}>
                                    パターンA</option>
                                <option
                                    value="2"{{ str_replace('"', '', $icon_position) == 2 ? ' selected' : '' }}>
                                    パターンB</option>
                            </select>
                        </div>
                    @endif

                </div>
            </div>
            <div class="d-flex tel-mail-wrapper">
                @if ($isEdit)
                    <div class="contact-box p-0 mx-4">
                        <img src="{{ asset('public/img/lp-tmp/tel.png') }}" style="width: 30px;" alt="電話">
                        <input type="tel" name="tel" value="{{ $lp->tel }}" class="m-0 p-0"
                            placeholder="090-0000-0000"></input>
                    </div>
                    <div class="contact-box p-0">
                        <img src="{{ asset('public/img/lp-tmp/mail.png') }}" style="width: 30px;" alt="Eメール">
                        <input type="email" name="email" value="{{ $lp->email }}" class="m-0 p-0"
                            placeholder="example@test.com"></input>
                    </div>
                @else
                    @if (!empty($lp->tel))
                        <a href="tel:{{ $lp->tel }}" class="contact-box p-0 mx-4" style="border:none;">
                            <img src="{{ asset('public/img/lp-tmp/tel.png') }}" style="width: 30px;" alt="電話">
                            {{ $lp->tel }}
                        </a>
                    @endif
                    @if (!empty($lp->email))
                        <a href="mailto:{{ $lp->email }}" class="contact-box p-0" style="border:none;">
                            <img src="{{ asset('public/img/lp-tmp/mail.png') }}" style="width: 30px;"
                                alt="Eメール">
                            {{ $lp->email }}
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <main style="margin-top: 0">
        <section
            class="first-sec size{{ str_replace('"', '', $icon_size) }} posi{{ str_replace('"', '', $icon_position) }}">
            <div class="container center">
                <div class="row contents">
                    <div class="d-flex justify-content-left align-items-center title-input">
                        @if ($isEdit)
                            タイトル：<input type="text" name="title" value="{{ $lp->title }}"
                                class="w-50 mb-0">
                        @else
                            @if (!empty($lp->title))
                                <h1>{{ $lp->title }}</h1>
                            @endif
                        @endif
                    </div>

                    <div class="image-slider-wrapper">

                        @if (!$isEdit && $lp->checkContentExist(1) == false)
                            {{-- showモードで、コンテンツが１つもない場合は何も出力しない。 --}}
                        @else
                            <div class="slider-area">
                                <ul class="slider" id="slider-1">
                                    @foreach ($contents as $content)
                                        @if ($content->type == 1)
                                            <li class="content-wrapper" data-content-id="{{ $content->id }}">
                                                <?php $file_url = isset($content->file_url) ? $content->file_url : 'public/img/lp-tmp/product.jpg';
                                                $description = isset($content->description) ? $content->description : ''; ?>
                                                @if ($isEdit)
                                                    <input type="hidden" name="contents[{{ $content->id }}][type]"
                                                        value="1">
                                                    <button type="button" class="btn btn-danger position-absolute"
                                                        onclick="deleteContent(event)"
                                                        style="height: unset; right: 0;"><i
                                                            class="fa fa-times"></i></button>
                                                    <input type="hidden" name="contents[{{ $content->id }}][url]"
                                                        value="{{ $file_url }}">
                                                    <input type="file" id="content_file_{{ $content->id }}"
                                                        class="" onchange="input_type_file(event)">
                                                @endif
                                                <img src="{{ asset($file_url) }}" alt="商品画像"
                                                    class="img-thumbnail" data-bs-toggle="modal"
                                                    data-bs-target="#myModal">
                                                @if ($isEdit)
                                                    <textarea name="contents[{{ $content->id }}][description]" placeholder="ここに説明文が入ります">{{ $description }}</textarea>
                                                @elseif(!empty($description))
                                                    <p>{!! nl2br(e($description)) !!}</p>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if ($isEdit)
                                    <div class="row justify-content-center">
                                        <button type="button" class="btn-add-content col span-2"
                                            data-type="1">追加</button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex justify-content-left align-items-center title-input">
                            @if ($isEdit)
                                タイトル：<input type="text" name="title2" value="{{ $lp->title2 }}"
                                    class="w-50 mb-0">
                            @else
                                @if (!empty($lp->title2))
                                    <h2>{{ $lp->title2 }}</h2>
                                @else
                                    <br style="display: block;height: 2.5rem">
                                @endif
                            @endif
                        </div>

                        @if (!$isEdit && $lp->checkContentExist(2) == false)
                            {{-- showモードで、コンテンツが１つもない場合は何も出力しない。 --}}
                        @else
                            <div class="slider-area">
                                <ul class="slider" id="slider-2">

                                    @foreach ($contents as $content)
                                        @if ($content->type == 2)
                                            <li class="content-wrapper" data-content-id="{{ $content->id }}">
                                                <?php $file_url = isset($content->file_url) ? $content->file_url : 'public/img/lp-tmp/product.jpg';
                                                $description = isset($content->description) ? $content->description : ''; ?>
                                                @if ($isEdit)
                                                    <input type="hidden" name="contents[{{ $content->id }}][type]"
                                                        value="2">
                                                    <button type="button" class="btn btn-danger position-absolute"
                                                        onclick="deleteContent(event)"
                                                        style="height: unset; right: 0;"><i
                                                            class="fa fa-times"></i></button>
                                                    <input type="hidden" name="contents[{{ $content->id }}][url]"
                                                        value="{{ $file_url }}">
                                                    <input type="file" id="content_file_{{ $content->id }}"
                                                        class="" onchange="input_type_file(event)">
                                                @endif
                                                <img src="{{ asset($file_url) }}" alt="商品画像"
                                                    class="img-thumbnail" data-bs-toggle="modal"
                                                    data-bs-target="#myModal">
                                                @if ($isEdit)
                                                    <textarea name="contents[{{ $content->id }}][description]" placeholder="ここに説明文が入ります">{{ $description }}</textarea>
                                                @elseif(!empty($description))
                                                    <p>{!! nl2br(e($description)) !!}</p>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                                @if ($isEdit)
                                    <div class="row justify-content-center">
                                        <button type="button" class="btn-add-content col span-2"
                                            data-type="2">追加</button>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="d-flex justify-content-left align-items-center title-input">
                            @if ($isEdit)
                                タイトル：<input type="text" name="title3" value="{{ $lp->title3 }}"
                                    class="w-50 mb-0">
                            @else
                                @if (!empty($lp->title3))
                                    <h2>{{ $lp->title3 }}</h2>
                                @else
                                    <br style="display: block;height: 2.5rem">
                                @endif
                            @endif
                        </div>

                        @if (!$isEdit && $lp->checkContentExist(3) == false)
                            {{-- showモードで、コンテンツが１つもない場合は何も出力しない。 --}}
                        @else
                            <div class="slider-area layout-2">
                                <ul class="slider" id="slider-3">
                                    @foreach ($contents as $content)
                                        @if ($content->type == 3)
                                            <li class="content-wrapper" data-content-id="{{ $content->id }}">
                                                <?php $file_url = isset($content->file_url) ? $content->file_url : 'public/img/lp-tmp/product.jpg';
                                                $description = isset($content->description) ? $content->description : ''; ?>
                                                @if ($isEdit)
                                                    <input type="hidden" name="contents[{{ $content->id }}][type]"
                                                        value="3">
                                                    <button type="button" class="btn btn-danger position-absolute"
                                                        onclick="deleteContent(event)"
                                                        style="height: unset; right: 0;"><i
                                                            class="fa fa-times"></i></button>
                                                    <input type="hidden" name="contents[{{ $content->id }}][url]"
                                                        value="{{ $file_url }}">
                                                    <input type="file" id="content_file_{{ $content->id }}"
                                                        class="" onchange="input_type_file(event)">
                                                @endif
                                                <img src="{{ asset($file_url) }}" alt="商品画像"
                                                    class="img-thumbnail layout-2-item" data-bs-toggle="modal"
                                                    data-bs-target="#myModal">
                                                @if ($isEdit)
                                                    <textarea name="contents[{{ $content->id }}][description]" placeholder="ここに説明文が入ります">{{ $description }}</textarea>
                                                @elseif(!empty($description))
                                                    <p>{!! nl2br(e($description)) !!}</p>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                                @if ($isEdit)
                                    <div class="row justify-content-center">
                                        <button type="button" class="btn-add-content col span-2"
                                            data-type="3">追加</button>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                </div>

            </div>
        </section>






        @if (!$isEdit && empty($shop_info))
            {{-- showモードで、入力がない場合は何も出力しない。 --}}
        @else
            <section style="padding-top: 0;">
                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center">
                        @if ($isEdit)
                            <textarea name="shop_info" style="resize: auto;" placeholder="ショップ等の情報">{{ $shop_info }}</textarea>
                        @else
                            {{ $shop_info }}
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if (!$isEdit && empty($twitter_url) && empty($facebook_url) && empty($instagram_url) && empty($map_html))
            {{-- showモードで、どの入力もない場合はセクションごと出力しない。 --}}
        @else
            <section>
                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center">
                        @if ($isEdit)
                            <div class="d-flex">
                                <div class="d-flex justify-content-center align-items-center flex-wrap mb-3">
                                    <a href="http://twitter.com/{{ $twitter_url }}" target="_blank"><img
                                            src="{{ asset('public/img/lp-tmp/Twitter_logo.png') }}" class="sns-icons"
                                            alt="twitter"></a>
                                    <input type="text" name="twitter_url" value="{{ $twitter_url }}"
                                        placeholder="twitterID" class="m-0 p-0"></input>
                                </div>
                                <div class="d-flex justify-content-center align-items-center flex-wrap mb-3">
                                    <a href="http://facebook.com/{{ $facebook_url }}" target="_blank"><img
                                            src="{{ asset('public/img/lp-tmp/Facebook_logo.png') }}"
                                            class="sns-icons" alt="facebook"></a>
                                    <input type="text" name="facebook_url" value="{{ $facebook_url }}"
                                        placeholder="facebookID" class="m-0 p-0"></input>
                                </div>
                                <div class="d-flex justify-content-center align-items-center flex-wrap mb-3">
                                    <a href="http://instagram.com/{{ $instagram_url }}" target="_blank"><img
                                            src="{{ asset('public/img/lp-tmp/Instagram_logo.png') }}"
                                            class="sns-icons" alt="instagram"></a>
                                    <input type="text" name="instagram_url" value="{{ $instagram_url }}"
                                        placeholder="instagramID" class="m-0 p-0"></input>
                                </div>
                            </div>
                        @else
                            @if ($twitter_url != '')
                                <a href="http://twitter.com/{{ $twitter_url }}" target="_blank"><img
                                        src="{{ asset('public/img/lp-tmp/Twitter_logo.png') }}" class="sns-icons"
                                        alt="twitter"></a>
                            @endif
                            @if ($facebook_url != '')
                                <a href="http://facebook.com/{{ $facebook_url }}" target="_blank"><img
                                        src="{{ asset('public/img/lp-tmp/Facebook_logo.png') }}" class="sns-icons"
                                        alt="facebook"></a>
                            @endif
                            @if ($instagram_url != '')
                                <a href="http://instagram.com/{{ $instagram_url }}" target="_blank"><img
                                        src="{{ asset('public/img/lp-tmp/Instagram_logo.png') }}" class="sns-icons"
                                        alt="instagram"></a>
                            @endif
                            @if ($map_html != '')
                                <a href="#map_section"><img src="{{ asset('public/img/lp-tmp/map.png') }}"
                                        class="sns-icons" alt="Googleマップ"></a>
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if (!$isEdit && empty($shop_intro))
            {{-- showモードで、入力がない場合はセクションごと出力しない。 --}}
        @else
            <section>
                <div class="d-flex justify-content-around align-items-center">
                    <div class="d-flex align-items-center">
                        @if ($isEdit)
                            <textarea name="shop_intro" style="resize: auto;" placeholder="ショップ等の紹介">{{ $shop_intro }}</textarea>
                        @else
                            {{ $shop_intro }}
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if (!$isEdit && empty($map_html))
            {{-- showモードで、入力がない場合はセクションごと出力しない。 --}}
        @else
            <section id="map_section">
                <div class="container">
                    @if ($isEdit)
                        <div class="row">
                            <input type="text" name="map_html" value="{{ $map_html }}"
                                placeholder="Googleマップの埋め込みURLを入力" onfocus="this.select();"
                                pattern='^$|^<iframe src="https://www.google.com/maps/embed[^<>]*></iframe>$'></input>
                        </div>
                    @endif
                    <div id="dispmap">
                        @if (!empty($map_html))
                            {!! htmlspecialchars_decode($map_html) !!}
                        @else
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3240.5000881778465!2d139.6898348270166!3d35.68930947258462!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188cd4b71a37a1%3A0xf1665c37f38661e8!2z5p2x5Lqs6YO95bqB!5e0!3m2!1sja!2sjp!4v1681100126754!5m2!1sja!2sjp"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        @endif
                    </div>
                </div>
            </section>
        @endif

    </main>
    <div class="copyright">
        <a href="#">Powered by Quanto</a>
    </div>
    <p id="pagetop"><a href="#">TOP</a></p>
    @if ($isEdit)
        </form>
    @endif
    <!-- モーダル -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" id="modal-image" alt="">
                    <div id="modal-pager">
                        <button class="modal-prev"><i class="fa fa-chevron-circle-left "></i></button>
                        <button class="modal-next"><i class="fa fa-chevron-circle-right "></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".swiper", {
            pagination: {
                el: ".swiper-pagination"
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            @if (!$isEdit)
                autoplay: {
                    delay: 5000, // スライドが切り替わるまでの時間（ミリ秒）
                    disableOnInteraction: true, // ユーザーがスライダーを操作した後も自動再生を継続するかどうか
                },
            @endif
        });
    </script>
</body>

</html>
