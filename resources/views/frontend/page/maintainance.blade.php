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
                            <h1>Under Construction!</h1>
                            <p>To make somethings right we need some time to rebuild.  Get notified when we are done.</p>
                        </div><!-- .cs-info -->
                    </div>
                    <div class="col-md-6">
                        <div class="cs-thumb">
                            <img src="{{asset('assets/images/cs-maintain.jpg')}}" alt="Coming Soon">
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