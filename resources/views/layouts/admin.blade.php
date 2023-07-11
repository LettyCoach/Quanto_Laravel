<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . '管理' : '管理者' }} | {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('public/css/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('public/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('public/js/easy-number-separator.js') }}"></script>


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar"
            style="background: #3A00FF;">

            <!-- Sidebar - Brand -->
            <a class="align-items-center justify-content-center text-decoration-none" href="{{ asset('/') }}">
                <img src="{{ asset('public/img/quanto_logo.png') }}" class="m-2"
                    style="width: 100%; margin: 0!important; padding: .5rem;">
                <p class="text-white text-center mr-4">クライアント質問管理</p>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <!-- ******************** -->
            <!-- 注文管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.orders') }}">
                <a class="nav-link" href="{{ route('admin.orders') }}">
                    <i class="fas fa-box"></i>
                    <span>{{ __('注文管理') }}</span></a>
            </li>
            <!-- 設問管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.surveys') }}">
                <a class="nav-link" href="{{ route('admin.surveys') }}">
                    <i class="fas fa-comment"></i>
                    <span>{{ __('設問管理') }}</span></a>
            </li>
            <!-- 回答管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.clients') }}">
                <a class="nav-link" href="{{ route('admin.clients') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ __('回答管理') }}</span></a>
            </li>
            <!-- LPページ管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.lps') }}">
                <a class="nav-link" href="{{ route('admin.lps') }}">
                    <i class="fas fa-desktop"></i>
                    <span>{{ __('LPページ管理') }}</span></a>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item {{ Nav::isRoute('admin.userProducts') }} {{ Nav::isRoute('admin.userProductCategories') }}">
                <a class="nav-link" href="{{ route('admin.userProducts') }}">
                    <i class="fas fa-book"></i>
                    <span>{{ __('商品管理') }}</span>
                </a>
                <ul class="" id="">
                    <li>
                        <a class="nav-link" href="{{ route('admin.userProductCategories') }}">
                            <i class="fas fa-book"></i>
                            <span>{{ __('カテゴリー') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- 関連データ管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.referralInfo') }}">
                <a class="nav-link" href="{{ route('admin.referralInfo') }}">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ __('関連データ管理') }}</span></a>
            </li>
            <!-- フォーム作成 -->
            <li class="nav-item {{ Nav::isRoute('paper.invoice') }}">
                <a class="nav-link" href="{{ route('paper.invoice') }}">
                    <i class="fas fa-book"></i>
                    <span>{{ __('フォーム作成') }}</span>
                </a>
                <ul class="" id="">
                    <li>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-book"></i>
                            <span>{{ __('見積書') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('paper.invoice') }}">
                            <i class="fas fa-book"></i>
                            <span>{{ __('請求書') }}<br>(インボイス)</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-book"></i>
                            <span>{{ __('納品書') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-book"></i>
                            <span>{{ __('領収書') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- クライアント管理 -->
            <li class="nav-item {{ Nav::isRoute('admin.users') }}  {{ Nav::isRoute('admin.user.create') }}">
                <a class="nav-link" href="{{ route('admin.users') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>{{ __('クライアント管理') }}</span>
                </a>
                <ul class="" id="">
                    <li>
                        <a class="nav-link" href="{{ route('admin.user.create') }}">
                            <i class="fas fa-user-friends"></i>
                            <span>{{ __('新規登録') }}</span></a>
                    </li>
                </ul>
            </li>

            @if (Auth::check())
                @if (Auth::user()->isAdmin())
                @endif
            @endif




            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('Settings') }}
            </div>

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ Nav::isRoute('profile') }}">
                <a class="nav-link" href="{{ route('profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('Profile') }}</span>
                </a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <figure class="img-profile rounded-circle avatar font-weight-bold"
                                    data-initial="{{ Auth::user()->name[0] }}">
                                    @if (Auth::user()->profile_url != null)
                                        <img src="{{ url(Auth::user()->profile_url) }}">
                                    @endif
                                </figure>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Settings') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('ログアウト') }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('main-content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('ログアウトしてよろしいですか?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">現在のセッションを終了する場合は、以下のログアウトボタンを押してください。</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('キャンセル') }}</button>
                    <a class="btn btn-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('ログアウト') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('public/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/lib/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
    <script src="{{ asset('public/js/lib/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('public/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('public/js/admin.js') }}"></script>
    <script src="{{ asset('public/js/drag.js') }}"></script>
    @yield('js')
</body>

</html>
