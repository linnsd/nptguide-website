@extends('frontend.layouts.template')
@section('main')
    <main id="main" class="site-main">
        <div class="site-content">
            <div class="landing-banner" style="background-image:url({{ asset('/assets/images/bg-app.png') }})">
                <div class="container">
                    <div class="lb-info">
                        <h2 style="color: #ffffff;">NPT Guide</h2>
                        <p style="color: #ffffff;">Local Business Listings & Directory Citations Services in Naypyitaw</p>
                        <div class="lb-button">
                            <a href="https://apps.apple.com/us/app/npt-guide/id1288682874" target="_blank" title="App store"><img src="{{asset('/assets/images/app-store.png')}}" alt="App store"></a>
                            <a href="https://play.google.com/store/apps/details?id=me.myatminsoe.nptguide&hl=en" target="_blank" title="Google play"><img src="{{asset('/assets/images/google-play.png')}}" alt="Google play"></a>
                        </div>
                    </div><!-- .lb-info -->
                </div>
            </div><!-- .landing-banner -->
            <div class="img-box-inner">
                <div class="container">
                    <div class="title ld-title">
                        <h2>How It Works</h2>
                        {{-- <p>From its medieval origins to the digital era.</p> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('/assets/images/pelican.png')}}" alt="">
                                <h3>Discover</h3>
                                <p>Take a deep dive and explore in naypyitaw</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('assets/images/island.png')}}" alt="">
                                <h3>Advertise</h3>
                                <p>Find your service and product.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('assets/images/surf.png')}}" alt="">
                                <h3>Plan your trip</h3>
                                <p>Find famous places in naypyitaw</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .img-box-inner -->
            <div class="features-inner">
                <div class="container">
                    <div class="title ld-title">
                        <h2>Best Features</h2>
                        <p>Lorem ipsum is placeholder text commonly used in the graphic.</p>
                    </div><!-- .title -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-01.png')}}" alt="Trending Ui/Ux Design">
                        </div>
                        <div class="features-info">
                            <h3>Trending <br> <span style="color: #dc512a;">Ui/Ux</span> Design</h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-02.png')}}" alt="Bringing it all together">
                        </div>
                        <div class="features-info">
                            <h3>Bringing it <br> all <span style="color: #dc512a;">together</span></h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-03.png')}}" alt="Keep your audience update">
                        </div>
                        <div class="features-info">
                            <h3>Keep your <br> <span style="color: #dc512a;">audience</span> update</h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                </div>
            </div><!-- .features -->
            <div class="landing-banner" style="background-image:url({{asset('/assets/images/ld-banner-02.png')}})">
                <div class="container">
                    <div class="lb-info">
                        <h2 style="color: #ffffff;">NPT Guide</h2>
                        <p style="color: #ffffff;">Download the app and explore in Naypyitaw.</p>
                        <div class="lb-button">
                            <a href="https://apps.apple.com/us/app/npt-guide/id1288682874" target="_blank" title="App store"><img src="{{asset('assets/images/app-store.png')}}" alt="App store"></a>
                            <a href="https://play.google.com/store/apps/details?id=me.myatminsoe.nptguide&hl=en"  target="_blank" title="Google play"><img src="{{asset('assets/images/google-play.png')}}" alt="Google play"></a>
                        </div>
                    </div><!-- .lb-info -->
                </div>
            </div><!-- .landing-banner -->
        </div><!-- .site-content -->
    </main><!-- .site-main -->
@stop