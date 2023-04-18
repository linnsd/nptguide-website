<div class="site">
                        <div class="site__menu">
                            <a title="Menu Icon" href="#" class="site__menu__icon">
                                <i class="la la-bars la-24"></i>
                            </a>
                            <div class="popup-background"></div>
                            <div class="popup popup--left">
                                <a title="Close" href="#" class="popup__close">
                                    <i class="la la-times la-24"></i>
                                </a><!-- .popup__close -->
                                <div class="popup__content">
                                    @guest
                                        <div class="popup__user popup__box open-form">
                                            <a title="Login" href="#" class="open-login">{{__('Login')}}</a>
                                            <a title="Sign Up" href="#" class="open-signup">{{__('Sign Up')}}</a>
                                        </div>
                                    @else
                                        <div class="account">
                                            <a href="#" title="{{Auth::user()->name}}">
                                                <span>
                                                    {{Auth::user()->name}}
                                                    <i class="la la-angle-down la-12"></i>
                                                </span>
                                            </a>
                                            <div class="account-sub">
                                                <ul>
                                                    {{-- <li class="{{isActiveMenu('user_profile')}}"><a href="{{route('user_profile')}}">{{__('Profile')}}</a></li>
                                                    <li class="{{isActiveMenu('user_my_place')}}"><a href="{{route('user_my_place')}}">{{__('My Places')}}</a></li>
                                                    <li class="{{isActiveMenu('user_wishlist')}}"><a href="{{route('user_wishlist')}}">{{__('Wishlist')}}</a></li> --}}
                                                    <li>
                                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                                                        <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">
                                                            @csrf
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div><!-- .account -->
                                    @endguest
                                    <div class="popup__menu popup__box">
                                        <ul class="menu-arrow">
                                             <li>
                                                <a title="Home" href="{{url('/')}}">Home</a>
                                            </li>

                                            <li>
                                                <a title="Places" href="{{url('/search-listing')}}">Places</a>
                                            </li>
                                            <li>
                                                <a title="Places" href="{{url('/promotions_list')}}">Promotions</a>
                                            </li>
                                            <li>
                                                <a title="Fav" href="{{url('/fav_place')}}">Favourite List</a>
                                            </li>
                                            <li>
                                                <a title="Places" href="{{url('/popular_shops')}}">Popular Places</a>
                                            </li>
                                            <li>
                                                <a title="Places" href="{{url('/visiting_places')}}">Visiting Places</a>
                                            </li>

                                            <li><a title="About" href="{{url('about')}}">About Us</a></li>
                                            <li><a title="Contacts" href="{{url('contact')}}">Contact</a></li>
                                            <li><a href="{{url('term-condition')}}">Terms & Conditions</a></li>
                                            <li><a href="{{url('privacy-policy')}}">Privacy Policy</a></li>
                                            
                                        </ul>
                                    </div><!-- .popup__menu -->
                                </div><!-- .popup__content -->
                                <div class="popup__button popup__box">
                                    <a class="btn" href="{{route('place_addnew')}}">
                                        <i class="la la-plus la-24"></i>
                                        <span>{{__('Add place')}}</span>
                                    </a>
                                </div><!-- .popup__button -->
                            </div><!-- .popup -->
                        </div><!-- .site__menu -->
                        <div class="site__brand">
                            <a title="Logo" href="{{route('home')}}" class="site__brand__logo">   
                                <h1 style="color: #dc512a;">NPT Guide</h1>
                            </a>
                        </div><!-- .site__brand -->

                        @php
                            $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
                        @endphp
                        @unless(\Request::route()->getName()=='home3')
                            @if(setting('template', '01') == '01')
                                <div class="site__search golo-ajax-search">
                                    <a title="Close" href="#" class="search__close">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <path fill="#5D5D5D" fill-rule="nonzero" d="M9.3 8.302l6.157-6.156a.706.706 0 1 0-.999-.999L8.302 7.304 2.146 1.148a.706.706 0 1 0-.999.999l6.157 6.156-6.156 6.155a.706.706 0 0 0 .998.999L8.302 9.3l6.156 6.156a.706.706 0 1 0 .998-.999L9.301 8.302z"/>
                                        </svg>
                                    </a><!-- .search__close -->
                                    <form action="{{route('place_search')}}" class="site__search__form" method="GET">
                                        <div class="site__search__field">
                                    <span class="site__search__icon">
                                        <i class="la la-search la-24"></i>
                                    </span><!-- .site__search__icon -->
                                            <input class="site__search__input" type="text" name="keyword" placeholder="{{__('Search places ...')}}" autocomplete="off" value="{{ $keyword }}">
                                            <div class="search-result"></div>
                                            <div class="golo-loading-effect"><span class="golo-loading"></span></div>
                                        </div><!-- .search__input -->
                                    </form><!-- .search__form -->
                                </div><!-- .site__search -->
                            @else
                                <div class="site__search layout-02">
                                    <a title="Close" href="#" class="search__close">
                                        <i class="la la-times"></i>
                                    </a><!-- .search__close -->
                                    <form action="{{route('page_search_listing')}}" class="site-banner__search layout-02">
                                        <div class="field-input">
                                            <label for="input_search">{{__('Find')}}</label>
                                            <input class="site-banner__search__input open-suggestion" id="input_search" type="text" name="keyword" placeholder="Ex: fastfood, beer" autocomplete="off">
                                            <input type="hidden" name="category[]" id="category_id">
                                            <div class="search-suggestions category-suggestion">
                                                <ul>
                                                    <li><a href="#"><span>{{__('Loading...')}}</span></a></li>
                                                </ul>
                                            </div>
                                        </div><!-- .site-banner__search__input -->
                                        <div class="field-input">
                                            <label for="location_search">{{__('Where')}}</label>
                                            <input class="site-banner__search__input open-suggestion" id="location_search" type="text" name="city_name" placeholder="Your city" autocomplete="off">
                                            <input type="hidden" id="city_id">
                                            <div class="search-suggestions location-suggestion">
                                                <ul>
                                                    <li><a href="#"><span>{{__('Loading...')}}</span></a></li>
                                                </ul>
                                            </div>
                                        </div><!-- .site-banner__search__input -->
                                        <div class="field-submit">
                                            <button><i class="las la-search la-24-black"></i></button>
                                        </div>
                                    </form><!-- .site-banner__search -->
                                </div>
                            @endif
                        @endunless

                    </div><!-- .site -->