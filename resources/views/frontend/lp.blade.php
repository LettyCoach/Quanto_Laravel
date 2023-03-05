<!doctype html>
<html lang="jp">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" media="all" href="{{ asset('public/css/lp/ress.min.css') }}" />
    <link rel="stylesheet" media="all" href="{{ asset('public/css/lp/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/css/lib/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">

    <?php
      $adminHost = \Illuminate\Support\Facades\Config::get('constants.adminHost');
      $lpSettings = isset($lp->settings) ? json_decode($lp->settings) : [];
      $mainimgs = isset($lpSettings->mainimgs) ? json_decode($lpSettings->mainimgs) : ['public/img/lp-tmp/product.jpg'];
      $profile_url = isset($lpSettings->profile_url) ? $lpSettings->profile_url : '';
      $twitter_url = isset($lpSettings->twitter_url) ? $lpSettings->twitter_url : '';
      $facebook_url = isset($lpSettings->facebook_url) ? $lpSettings->facebook_url : '';
      $instagram_url = isset($lpSettings->instagram_url) ? $lpSettings->instagram_url : '';
      $shop_info = isset($lpSettings->shop_info) ? $lpSettings->shop_info : '';
      $shop_intro = isset($lpSettings->shop_intro) ? $lpSettings->shop_intro : '';
      $map_html = isset($lpSettings->map_html) ? $lpSettings->map_html : '';
    ?>
    @if ($isEdit)
      <title>{{ $lp->title }} - 編集ページ</title>
    @else
      <title>{{ $lp->title }}</title>
    @endif

    <script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
    <script>
    input_type_file = function(e) {
      let file_data = $(e.target).prop('files')[0];
      let formData = new FormData();
      formData.append("file", file_data);
      if (file_data) {
          $.ajax({
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              url: "{{url('admin/lp/upload')}}",
              data: formData,
              type: 'post',
              async: false,
              contentType: false,
              processData: false,
              dataType: 'json',
              success: (data) => {
                $(e.target).prev().val(data.url);
                $(e.target).next().attr('src', `{{ $adminHost }}${data.url}`);
              }
          });
      }
    }

    $(document).ready(function() {
      $('.btn-add-content').click(function (e) {
        let formData = new FormData();
        formData.append("lp_id", "{{ $lp['id'] }}");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('admin/lp/content')}}",
            data: formData,
            type: 'post',
            async: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
              $('.contents').append(`
              <div class="col span-4 position-relative" data-content-id="${data.id}">
              <button type="button" class="btn btn-danger position-absolute" onclick="deleteContent(event)" style="height: unset; right: 0;"><i class="fa fa-times"></i></button>
                <label for="content_file_${data.id}">
                  <input type="hidden" name="contents[${data.id}][url]" value="public/img/lp-tmp/product.jpg">
                  <input type="file" id="content_file_${data.id}" class="d-none" onchange="input_type_file(event)">
                  <img src="{{ asset('public/img/lp-tmp/product.jpg') }}" class="pointer m-2" alt="商品画像">
                </label>
                <textarea name="contents[${data.id}][description]" placeholder="ここに説明文が入ります"></textarea>
              </div>`);
            }
        });
      });

      $('.btn-add-mainimg').click(function (e) {
        $('.swiper-wrapper').append(`
          <div class="swiper-slide">
            <label for="mainimg_${mainimg_id}">
              <input type="hidden" name="mainimgs[${mainimg_id}]" value="public/img/lp-tmp/mainimg.jpg">
              <input type="file" id="mainimg_${mainimg_id}" class="d-none" onchange="input_type_file(event)">
              <img src="{{ asset('public/img/lp-tmp/mainimg.jpg') }}" class="pointer" alt="メイン画像" style="height: 500px; object-fit: cover; object-position: center;">
            </label>
          </div>`
        );
        mainimg_id++;
        swiper.update();
      });

      var topBtn = $('#pagetop');
      topBtn.hide();
      //スクロールが300に達したらボタン表示
      $(window).scroll(function () {
          if ($(this).scrollTop() > 300) {
              topBtn.fadeIn();
          } else {
              topBtn.fadeOut();
          }
      });
      //スクロールでトップへもどる
      topBtn.click(function () {
          $('body,html').animate({
              scrollTop: 0
          }, 500);
          return false;
      });

      deleteContent = function(e) {
        let formData = new FormData();
        formData.append("content_id", $(e.target).closest('div').data('content-id'));
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('admin/lp/contentd')}}",
            data: formData,
            type: 'post',
            async: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: () => $(e.target).closest('div').remove()
        });
      }
  });
  </script>

</head>

<body>
@if ($isEdit)
<form method="post" action="{{ route('admin.lp.save') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{$lp['id']}}">
@endif
    <div class="mainimg">
      <?php $mainimg_id = 0; ?>
      <div class="swiper">
        <div class="swiper-wrapper">
          @foreach ($mainimgs as $url)
              <div class="swiper-slide">
                <label for="mainimg_{{ $mainimg_id }}">
                @if ($isEdit)
                  <input type="hidden" name="mainimgs[{{ $mainimg_id }}]" value="{{ $url }}">
                  <input type="file" id="mainimg_{{ $mainimg_id }}" class="d-none" onchange="input_type_file(event)">
                @endif
                  <img src="{{ asset($url) }}" class="pointer" alt="メイン画像" style="height: 500px; object-fit: cover; object-position: center;">
                </label>
              </div>
            <?php $mainimg_id++; ?>
          @endforeach
          <?php echo '<script>var mainimg_id = '.$mainimg_id.'</script>' ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
@if ($isEdit)
      <div class="d-flex justify-content-around align-items-center">
        <div class="d-flex">
          <a href="{{ url('admin/lps') }}">
            <button type="button">戻る</button>
          </a>
          <button type="submit" class="mx-3">保存</button>
          <!--button type="button" class="btn-add-mainimg">追加</button-->
        </div>
        <div>タイトル：<input type="text" name="title" value="{{ $lp->title }}"></input></div>
      </div>
@endif
      <div class="d-flex justify-content-around align-items-center">
        <div class="d-flex align-items-center">
          <label for="profile_url">
@if ($isEdit)
            <input type="hidden" name="profile_url" value="{{ $profile_url }}">
            <input type="file" id="profile_url" class="d-none" onchange="input_type_file(event)">
@endif
            <img src="{{ $profile_url != '' ? asset($profile_url) : asset('public/img/lp-tmp/default_profile.png') }}" class="pointer m-2" style="height: 50px; width: unset;" alt="プロフィール画像">
          </label>
@if ($isEdit)
          <textarea name="description" style="resize: auto;" placeholder="自己紹介">{{ $lp->description }}</textarea>
@else
          {!! nl2br(e($lp->description)) !!}
@endif
        </div>
        <div class="d-flex">
@if ($isEdit)
          <div class="contact-box p-0 mx-2">
@else
          <a href="tel:{{ $lp->tel }}" class="contact-box p-0 mx-2">
@endif
            <img src="{{ asset('public/img/lp-tmp/tel.png') }}" style="width: 30px;" alt="電話">
@if ($isEdit)
            <input type="tel" name="tel" value="{{ $lp->tel }}" class="m-0 p-0" placeholder="090-0000-0000"></input>
          </div>
          <div class="contact-box p-0">
@else
            {{ $lp->tel }}
          </a>
          <a href="mailto:{{ $lp->email }}" class="contact-box p-0">
@endif
            <img src="{{ asset('public/img/lp-tmp/mail.png') }}" style="width: 30px;" alt="Eメール">
@if ($isEdit)
            <input type="email" name="email" value="{{ $lp->email }}" class="m-0 p-0" placeholder="example@test.com"></input>
          </div>
@else
            {{ $lp->email }}
          </a>
@endif
        </div>
      </div>
    </div>
    <main>
<section>
  <div class="container center">
    <div class="row contents">
      @foreach ($contents as $content)
      <div class="col span-4 position-relative" data-content-id="{{ $content->id }}">
@if ($isEdit)
        <button type="button" class="btn btn-danger position-absolute" onclick="deleteContent(event)" style="height: unset; right: 0;"><i class="fa fa-times"></i></button>
@endif
        <label for="content_file_{{ $content->id }}">
          <?php $file_url = isset($content->file_url) ? $content->file_url : 'public/img/lp-tmp/product.jpg';
                $description = isset($content->description) ? $content->description : ''; ?>
@if ($isEdit)
          <input type="hidden" name="contents[{{ $content->id }}][url]" value="{{ $file_url }}">
          <input type="file" id="content_file_{{ $content->id }}" class="d-none" onchange="input_type_file(event)">
@endif
          <img src="{{ asset($file_url) }}" class="pointer m-2" alt="商品画像">
        </label>
@if ($isEdit)
        <textarea name="contents[{{ $content->id }}][description]" placeholder="ここに説明文が入ります">{{ $description }}</textarea>
@else
        <p>{!! nl2br(e($description)) !!}</p>
@endif
      </div>
      @endforeach
    </div>
@if ($isEdit)
    <div class="row justify-content-center">
      <button type="button" class="btn-add-content col span-2">追加</button>
    </div>
@endif
	</div>
</section>

<section>
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

<section>
  <div class="d-flex justify-content-around align-items-center">
    <div class="d-flex align-items-center">
@if ($isEdit)
      <div class="row">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <a href="http://twitter.com/{{ $twitter_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Twitter_logo.png') }}" class="sns-icons" alt="twitter"></a>
          <input type="text" name="twitter_url" value="{{ $twitter_url }}" placeholder="twitterID" class="m-0 p-0"></input>
        </div>
        <div class="d-flex justify-content-center align-items-center mb-3">
          <a href="http://facebook.com/{{ $facebook_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Facebook_logo.png') }}" class="sns-icons" alt="facebook"></a>
          <input type="text" name="facebook_url" value="{{ $facebook_url }}" placeholder="facebookID" class="m-0 p-0"></input>
        </div>
        <div class="d-flex justify-content-center align-items-center mb-3">
          <a href="http://instagram.com/{{ $instagram_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Instagram_logo.png') }}" class="sns-icons" alt="instagram"></a>
          <input type="text" name="instagram_url" value="{{ $instagram_url }}" placeholder="instagramID" class="m-0 p-0"></input>
        </div>
      </div>
@else
  @if ($twitter_url != '')
          <a href="http://twitter.com/{{ $twitter_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Twitter_logo.png') }}" class="sns-icons" alt="twitter"></a>
  @endif
  @if ($facebook_url != '')
          <a href="http://facebook.com/{{ $facebook_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Facebook_logo.png') }}" class="sns-icons" alt="facebook"></a>
  @endif
  @if ($instagram_url != '')
          <a href="http://instagram.com/{{ $instagram_url }}" target="_blank"><img src="{{ asset('public/img/lp-tmp/Instagram_logo.png') }}" class="sns-icons" alt="instagram"></a>
  @endif
  @if ($map_html != '')
          <a href="#map_section"><img src="{{ asset('public/img/lp-tmp/map.png') }}" class="sns-icons" alt="Googleマップ"></a>
  @endif
@endif
    </div>
  </div>
</section>

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

<section id="map_section">
  <div class="container">
    <div class="row">
      <div class="d-flex justify-content-center col span-12">
        {!! htmlspecialchars_decode($map_html) !!}
      </div>
    </div>
@if ($isEdit)
    <div class="row">
      <input type="text" name="map_html" value="{{ $map_html }}" placeholder="Googleマップの埋め込みURLを入力" onfocus="this.select();"
      pattern='^$|^<iframe src="https://www.google.com/maps/embed[^<>]*></iframe>$'></input>
    </div>
@endif
  </div>
</section>
</main>
    <div class="copyright">
      <a href="#">Powered by Quanto</a> 
    </div>
    <p id="pagetop"><a href="#">TOP</a></p>
@if ($isEdit)
  </form>
@endif

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper(".swiper", {
      pagination: {
        el: ".swiper-pagination"
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
      }
    });
  </script>
</body>

</html>