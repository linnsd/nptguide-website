<footer id="footer" class="footer">
    <div class="container">
        <div class="footer__top">
            <div class="row">
                <div class="col-lg-5">
                    <div class="footer__top__info">
                        <a title="Logo" href="{{ url('/')}}" class="footer__top__info__logo">
                            <img src="{{asset(setting('logo') ? 'uploads/' . setting('logo') : 'assets/images/assets/logo.png')}}" alt="logo">
                        </a>
                        <p class="footer__top__info__desc">{{__('Local Business Listings & Directory Citations Services in Naypyitaw.')}}</p>
                        <div class="footer__top__info__app">
                            <a title="App Store" href="https://apps.apple.com/us/app/npt-guide/id1288682874" class="banner-apps__download__iphone"><img src="{{asset('assets/images/assets/app-store.png')}}" alt="App Store"></a>
                            <a title="Google Play" href="https://play.google.com/store/apps/details?id=me.myatminsoe.nptguide&hl=en" class="banner-apps__download__android"><img src="{{asset('assets/images/assets/google-play.png')}}" alt="Google Play"></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <aside class="footer__top__nav">
                        <h3>{{__('About')}}</h3>
                        <ul>
                            <li><a href="{{url('about')}}">{{__('About Us')}}</a></li>
                            <li><a href="{{route('term-condition')}}">{{__('Terms & Conditions')}}</a></li>
                            <li><a href="{{route('privacy-policy')}}">{{__('Privacy Policy')}}</a></li>
                            <li><a href="{{url('faq')}}">{{__('Faqs')}}</a></li>
                            <li><a href="{{url('contact')}}">{{__('Contact')}}</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-4">
                    <aside class="footer__top__nav footer__top__nav--contact">
                        <h3>{{__('Contact Us')}}</h3>
                        <p>{{__('Email: support@npgtuide.com')}}</p>
                        <p>{{__('Phone: 09 1234567890')}}</p>
                        <ul>
                            <li class="facebook">
                                <a title="Facebook" target="_blank" href="https://www.facebook.com/NPTGuide" style="display: block;width: 36px;height: 36px;text-align: center;line-height: 36px;border-radius: 5px;color: #ffffff;background-color: #3b5998;font-size: 16px;">
                                    <i class="la la-facebook-f"></i>
                                </a>
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div><!-- .top-footer -->
        <div class="footer__bottom">
            <p class="footer__bottom__copyright">{{now()->year}} &copy; <a href="{{__('#')}}" >{{__('NPT Guide')}}</a>. {{__('All rights reserved.')}}</p>
        </div><!-- .top-footer -->
    </div><!-- .container -->
</footer><!-- site-footer -->