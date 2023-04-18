@extends('frontend.layouts.template_landing')
@section('main')
    <body class="template-coming-soon layout-2">
    <div id="wrapper">
        <header id="header" class="site-header">
            <div class="container">
                <div class="site__brand">
                    <a title="Logo" href="{{route('home')}}" class="site__brand__logo"><img src="{{asset('assets/images/assets/logo.png')}}" alt="Golo"></a>
                </div><!-- .site__brand -->
            </div><!-- .container-fluid -->
        </header><!-- .site-header -->

        <main id="main" class="site-main">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="cs-info">
                            <h1>Nice to <br> <span>meet</span> you!</h1>
                            <p>We are preparing something amazing and exciting for you.</p>
                        </div><!-- .cs-info -->
                    </div>
                    <div class="col-md-6">
                        <div class="cs-thumb">
                            <img src="{{asset('assets/images/cs-thumb.png')}}" alt="Coming Soon">
                            <div class="cs-day">
                                <span>12</span>
                                <p>Days to Launch</p>
                            </div>
                        </div><!-- .cs-thumb -->
                    </div>
                </div>
            </div>
        </main><!-- .site-main -->

        <footer id="footer" class="footer">
            <div class="container">
                <div class="footer-socials">
                    <ul>
                        <li>
                            <a title="Facebook" href="https://www.facebook.com/NPTGuide">
                                <i class="lab la-facebook-square"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div><!-- .container -->
        </footer><!-- site-footer -->
    </div><!-- #wrapper -->
@stop