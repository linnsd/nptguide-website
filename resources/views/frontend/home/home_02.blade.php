@php
    $img_home_banner = (setting('home_banner'));
    if (setting('home_banner')) {
        $home_banner = "style=background-image:url({$img_home_banner})";
    } else {
        $home_banner = "style=background-image:url(/assets/images/home-bsn-banner.jpg)";
    }
@endphp
@extends('frontend.layouts.template_02')
@section('main')
    <main id="main" class="site-main home-main business-main">
        <div class="site-banner" {{$home_banner}}>
            <div class="container">
                <div class="site-banner__content">
                    <h1 class="site-banner__title">{{__('Business Listing')}}</h1>
                    <p><i>City Count</i> {{__('cities')}}, <i>Category Count</i> {{__('categories')}}, <i>place count</i> {{__('places')}}.</p>
                    <form action="{{route('page_search_listing')}}" class="site-banner__search layout-02">
                        <div class="field-input">
                            <label for="input_search">{{__('Find')}}</label>
                            <input class="site-banner__search__input open-suggestion" id="input_search" type="text" placeholder="{{__('Ex: fastfood, beer')}}" autocomplete="off">
                            <input type="hidden" name="category[]" id="category_id">
                            <div class="search-suggestions category-suggestion">
                                <ul>
                                    <li><a href="#"><span>{{__('Loading...')}}</span></a></li>
                                </ul>
                            </div>
                        </div><!-- .site-banner__search__input -->
                        <div class="field-input">
                            <label for="location_search">{{__('Where')}}</label>
                            <input class="site-banner__search__input open-suggestion" id="location_search" type="text" placeholder="{{__('Your city')}}" autocomplete="off">
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
                </div><!-- .site-banner__content -->
            </div>
        </div><!-- .site-banner -->

        <div class="business-category">
            <div class="container">
                <h2 class="title title-border-bottom align-center">{{__('Browse Businesses by Category')}}</h2>
                <div class="slick-sliders">
                    <div class="slick-slider business-cat-slider slider-pd30" data-item="6" data-arrows="true" data-itemScroll="6" data-dots="true" data-centerPadding="50" data-tabletitem="3" data-tabletscroll="3" data-smallpcitem="4" data-smallpcscroll="4" data-mobileitem="2" data-mobilescroll="2" data-mobilearrows="false">

                        @foreach($eProviders as $eProvider)
                            <div class="bsn-cat-item rosy-pink">
                                <a href="" style="background-color:#ED7777;">
                                    <img src="{{($eProvider['imgPath'])}}" alt="{{$eProvider['name']}}">
                                    <span class="title">{{$eProvider['name']}}</span>
                                    <span class="place" style="font-size: 12px;">{{$eProvider['tsh_name']}}</span>
                                </a>
                            </div>
                        @endforeach

                    </div>
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div>
            </div>
        </div><!-- .business-category -->

        <div class="trending trending-business">
            <div class="container">
                <h2 class="title title-border-bottom align-center">{{__('Trending Business Places')}}</h2>
                <div class="slick-sliders">
                    <div class="slick-slider trending-slider slider-pd30" data-item="4" data-arrows="true" data-itemScroll="4" data-dots="true" data-centerPadding="30" data-tabletitem="2" data-tabletscroll="2" data-smallpcscroll="3" data-smallpcitem="3" data-mobileitem="1" data-mobilescroll="1" data-mobilearrows="false">

                       

                    </div>
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div>
            </div>
        </div><!-- .trending -->

        <div class="featured-cities">
            <div class="container">
                <h2 class="title title-border-bottom align-center">{{__('Featured Cities')}}<span>{{__("Choose the city you'll be living in next")}}</span></h2>
                <div class="slick-sliders">
                    <div class="slick-slider featured-slider slider-pd30" data-item="4" data-arrows="true" data-itemScroll="4" data-dots="true" data-centerPadding="30" data-tabletitem="2" data-tabletscroll="2" data-mobileitem="1" data-mobilescroll="1" data-mobilearrows="false">

                        @foreach($popular_cities as $city)
                            <div class="slick-item">
                                <div class="cities__item hover__box">
                                    <div class="cities__thumb hover__box__thumb">
                                        <a title="London" href="{{route('page_search_listing', ['city[]' => $city->id])}}">
                                            <img src="{{($city->thumb)}}" alt="{{$city->name}}">
                                        </a>
                                    </div>
                                    <h4 class="cities__name">City Country name</h4>
                                    <div class="cities__info">
                                        <h3 class="cities__capital">City Name</h3>
                                        <p class="cities__number">place count {{__('places')}}</p>
                                    </div>
                                </div><!-- .cities__item -->
                            </div>
                        @endforeach

                    </div>
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div>
            </div>
        </div><!-- .featured-cities -->

        <div class="business-about" style="background-image: url({{asset('assets/images/img_about_1.jpg')}});">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="business-about-info">
                            <h2>{{__('Who we are')}}</h2>
                            <p>{{__("Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident.")}}</p>
                            <a href="#" class="btn">{{__('Read more')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .business-about -->

        <div class="testimonial">
            <div class="container">
                <h2 class="title title-border-bottom align-center">{{__('People Talking About Us')}}</h2>
                <div class="slick-sliders">
                    <div class="slick-slider testimonial-slider slider-pd30" data-item="2" data-arrows="true" data-itemScroll="2" data-dots="true" data-centerPadding="30" data-tabletitem="1" data-tabletscroll="1" data-mobileitem="1" data-mobilescroll="1" data-mobilearrows="false">
                        
                            <div class="testimonial-item layout-02">
                                <div class="avatar">
                                    <img class="ava" src="avatar" alt="Avatar">
                                    <img src="{{asset('assets/images/quote-active.png')}}" alt="Quote" class="quote">
                                </div>
                                <div class="testimonial-info">
                                    <p>content</p>
                                    <div class="testimonial-meta">
                                        <b>name</b>
                                        <span>job_title</span>
                                    </div>
                                </div>
                            </div>
                      
                    </div>
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div>
            </div>
        </div><!-- .testimonial -->

        <div class="blogs">
            <div class="container">
                <h2 class="title title-border-bottom align-center">{{__('From Our Blog')}}</h2>
                <div class="news__content">
                    <div class="row">

                        

                    </div>
                    <div class="align-center button-wrap"><a href="{{route('post_list_all')}}" class="btn btn-border">{{__('View more')}}</a></div>
                </div>
            </div>
        </div><!-- .blogs -->
    </main><!-- .site-main -->
@stop
