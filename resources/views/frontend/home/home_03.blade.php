@php
    $home_banner = '/assets/images/bg-app.png';
    $img_home_banner_app = '/assets/images/ld-banner-02.png';
    
    if (setting('home_banner_app')) {
        $home_banner_app = "style=background-image:url({$img_home_banner_app})";
    } else {
        $home_banner_app = 'style=background-image:url(/assets/images/banner-apps.jpg)';
    }
    
    $townships = $townships;
    $categories = $myarr;
    $popular_cities = [];
    
    $testimonials = [];
    $blog_posts = [];
@endphp
@extends('frontend.layouts.template_03')
@push('style')
    <link rel="stylesheet" href="{{ asset('./css/owl.carousel.scss') }}">
    <link rel="stylesheet" href="{{ asset('./css/_theme.default.scss') }}">
    <style>
        .my-center {
            display: flex;
            justify-content: center;
        }

        * {
            padding: 0;
            margin: 0;
        }

        #slider {
            margin: 20px auto;
            width: 100%;
            height: 70vh;
            object-fit: cover;
            position: relative;
        }

        .slide {
            list-style: none;
        }

        #slider>li {
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @media only screen and (max-width: 512px) {
            #slider {
                height: 40vh;
            }
        }
    </style>
@endpush
@section('main')

    {{-- slider page start --}}
    <div class="wrapper">
        <ul id="slider">
            @foreach ($adsSliders as $slider)
                <li class="slide"> <img src="{{ $slider['imgPath'] }}" style="width:100%"></li>
            @endforeach
        </ul>
    </div>
    {{-- slider page end --}}


    {{-- search element start --}}
    {{-- <div class="container my-center" style="margin:10rem auto;">
        <div class="banner-content">
            <span class="site-banner__title">{{ __('NPT Guide') }}</span>
            <h4 class="site-banner__meta">
                {{ __('Local Business Listings & Directory Citations Services in Naypyitaw') }}</h4>
            <br>

            <form action="{{ route('place_search') }}" class="site-banner__search layout-02">
                <div class="field-input">
                    <label for="s">{{ __('Find') }}</label>
                    <input type="text" class="site-banner__search__input open-suggestion" id="input_search"
                        placeholder="{{ __('Category...') }}" autocomplete="off">
                    <input type="hidden" name="category[]" id="category_id">
                    <div class="search-suggestions name-suggestions category-suggestion">
                        <ul class="category_items">
                            @foreach ($categories as $category)
                                <li><a href="#"
                                        data-id={{ $category['id'] }}><span>{{ __($category['name']) }}</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- .site-banner__search__input -->
                <div class="field-input">
                    <label for="loca">{{ __('Where') }}</label>
                    <input type="text" class="site-banner__search__input open-suggestion" id="input_search"
                        placeholder="{{ __('Location...') }}" autocomplete="off">
                    <input type="hidden" name="township[]" id="township">
                    <div class="search-suggestions location-suggestions location-suggestion">
                        <ul class="">
                            @foreach ($townships as $township)
                                <li><a href="#"
                                        data-id="{{ $township->id }}"><span>{{ __($township->tsh_name) }}</span></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- .site-banner__search__input -->
                <div class="field-submit">
                    <button><i class="las la-search la-24-black"></i></button>
                </div>
            </form>

        </div>
    </div> --}}
    {{-- search element end --}}

    <main id="main" class="site-main">
        {{-- <section class="banner-wrap mt-5"> --}}
        {{-- <div class="flex">
                <div class="banner-left"></div>
                <div class="banner slick-sliders">
                    <div class="banner-sliders slick-slider" data-item="1" data-arrows="false" data-dots="true">
                        <div class="item"></div>
                        <div class="item"><img src="{{$home_banner}}" alt="Banner"></div>
                    </div>
                </div><!-- .banner -->
            </div> --}}

        {{-- </section><!-- .banner-wrap --> --}}
        <marquee direction="left" scrolldelay="100">
            @foreach ($text_slides as $text_slide)
                <span style="color:#DC512A;" id="text">{{ strip_tags($text_slide->text) }}</span>
            @endforeach
        </marquee>
        <input type="hidden" name="text_slide[]" value="{{ $text_slides }}" id="text_slide">

        {{-- Promotion List --}}
        @if (getPromotion())
            <section class="restaurant-wrap">
                <div class="container">
                    <div class="title_home d-flex justify-content-between">
                        <h2>{{ __('Current Promotion') }}</h2>
                        <a href="{{ route('promotions.list') }}" class="btn btn-primary btn-sm"
                            style="outline: none; border:none">See More</a>
                    </div>
                    <div class="restaurant-sliders slick-sliders">
                        <div class="restaurant-slider slick-slider" data-item="3" data-itemScroll="3" data-arrows="true"
                            data-dots="true" data-tabletItem="2" data-tabletScroll="2" data-mobileItem="1"
                            data-mobileScroll="1">
                            @foreach (getPromotion(10) as $provider)
                                <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false"
                                    style="width: 300px;" role="tabpanel" id="slick-slide10">
                                    <div>
                                        <div class="place-item layout-02 place-hover"
                                            style="width: 100%; display: inline-block;">
                                            <div class="place-inner" style="padding: 0;">
                                                <div class="place-thumb hover-img">
                                                    @if ($provider['imgPath'] != null)
                                                        <a class="entry-thumb"
                                                            href="{{ url('promotion_detail', $provider['id']) }}"
                                                            tabindex="0"><img src="{{ $provider['imgPath'] }}"
                                                                alt="photo"></a>
                                                    @else
                                                        <a class="entry-thumb"
                                                            href="{{ url('promotion_detail', $provider['id']) }}"
                                                            tabindex="0"><img
                                                                src="{{ asset('/assets/images/favicon.png') }}"
                                                                alt="photo"></a>
                                                    @endif
                                                    <!-- <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist   open-login  " data-id="19" tabindex="0">
                                                                    <span class="icon-heart">
                                                                        <i class="la la-bookmark large"></i>
                                                                    </span>
                                                                </a> -->
                                                    <!-- <a class="entry-category rosy-pink" href="https://lara-restaurant.getgolo.com/search-listing?category%5B%5D=11" style="background-color:#f0626c;" tabindex="0">
                                                                    <img src="{{ $provider['imgPath'] }}" alt="Pizza">
                                                                    <span>{{ $provider['title'] }}</span>
                                                                </a> -->
                                                </div>
                                                <div class="entry-detail" style="height:10rem;">
                                                    <h5 class="place-title" style="padding: 0;margin:1rem 0;"><a
                                                            href="{{ url('promotion_detail', $provider['id']) }}"
                                                            tabindex="0">{{ $provider['title'] }}</a></h5>
                                                    <div class="promo-duration">
                                                        <div style="font-size: 0.75rem; color:#DC512A;">
                                                            <span><i class="la la-calendar"></i></span>
                                                            {{ date('d-m-Y', strtotime($provider['from_date'])) }} to
                                                            {{ date('d-m-Y', strtotime($provider['to_date'])) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div><!-- .restaurant-slider -->
                        <div class="place-slider__nav slick-nav">
                            <div class="place-slider__prev slick-nav__prev">
                                <i class="las la-angle-left"></i>
                            </div><!-- .place-slider__prev -->
                            <div class="place-slider__next slick-nav__next">
                                <i class="las la-angle-right"></i>
                            </div><!-- .place-slider__next -->
                        </div><!-- .place-slider__nav -->
                    </div><!-- .restaurant-sliders -->
                </div>
            </section>
        @endif


        <section class="restaurant-wrap">
            <div class="container">
                <div class="title_home d-flex justify-content-between">
                    <h2>{{ __('Popular Shop') }}</h2>
                    <a href="{{ route('popular_shops') }}" class="btn btn-primary btn-sm"
                        style="outline: none; border:none">See More</a>
                </div>
                <div class="restaurant-sliders slick-sliders">
                    <div class="restaurant-slider slick-slider" data-item="4" data-itemScroll="4" data-arrows="true"
                        data-dots="true" data-tabletItem="2" data-tabletScroll="2" data-mobileItem="1"
                        data-mobileScroll="1">
                        @foreach ($providerArr as $provider)
                            <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false"
                                style="width: 300px;" role="tabpanel" id="slick-slide10">
                                <div>
                                    <div class="place-item layout-02 place-hover"
                                        style="width: 100%; display: inline-block;">
                                        <div class="place-inner">
                                            <div class="place-thumb hover-img">
                                                @if ($provider['imgPath'] != null)
                                                    <a class="entry-thumb"
                                                        href="{{ url('place_detail', $provider['id']) }}"
                                                        tabindex="0"><img src="{{ $provider['imgPath'] }}"
                                                            alt="photo"></a>
                                                @else
                                                    <a class="entry-thumb"
                                                        href="{{ url('place_detail', $provider['id']) }}"
                                                        tabindex="0"><img
                                                            src="{{ asset('/assets/images/favicon.png') }}"
                                                            alt="photo"></a>
                                                @endif
                                                <!-- <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist   open-login  " data-id="19" tabindex="0">
                                                                <span class="icon-heart">
                                                                    <i class="la la-bookmark large"></i>
                                                                </span>
                                                            </a> -->
                                                <!-- <a class="entry-category rosy-pink" href="https://lara-restaurant.getgolo.com/search-listing?category%5B%5D=11" style="background-color:#f0626c;" tabindex="0">
                                                                <img src="{{ $provider['imgPath'] }}" alt="Pizza">
                                                                <span>{{ $provider['name'] }}</span>
                                                            </a> -->
                                            </div>
                                            <div class="entry-detail" style="height:10rem;">
                                                <h4 class="place-title"><a
                                                        href="{{ url('place_detail', $provider['id']) }}"
                                                        tabindex="0">{{ $provider['name'] }}</a></h4>
                                                <p style="text-align: justify;">{{ $provider['address'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!-- .restaurant-slider -->
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div><!-- .restaurant-sliders -->
            </div>
        </section><!-- .restaurant-wrap -->

        <section class="cuisine-wrap section-bg">
            <div class="container">
                <div class="title_home">
                    <h2>{{ __('Search By Category') }}</h2>
                    <p>{{ __('Explore places by your favorite category') }}</p>
                </div>
                <div class="cuisine-sliders slick-sliders">
                    <div class="cuisine-slider slick-slider" data-item="6" data-itemScroll="6" data-arrows="true"
                        data-dots="true" data-smallpcItem="4" data-smallpcScroll="4" data-tabletItem="3"
                        data-tabletScroll="3" data-mobileItem="2" data-mobileScroll="2">

                        @foreach ($categories as $cat)
                            <div class="item">
                                @if ($cat['imgPath'] != null)
                                    <a href="{{ url('search-listing?category%5B%5D=' . $cat['id']) }}"
                                        title="{{ $cat['name'] }}">
                                        <span class="hover-img"><img src="{{ $cat['imgPath'] }}"
                                                alt="{{ $cat['name'] }}"></span>

                                        <span class="title">{{ $cat['name'] }}<span
                                                class="number">({{ $cat['shop_count'] }})</span></span>
                                    </a>
                                @else
                                    <a class="entry-thumb" href="{{ url('place_detail', $provider['id']) }}"
                                        tabindex="0"><img src="{{ asset('/assets/images/favicon.png') }}"
                                            alt="Boot Café"></a>
                                @endif
                            </div>
                        @endforeach

                    </div><!-- .cuisine-slider -->
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div><!-- .cuisine-sliders -->
            </div><!-- .container -->
        </section><!-- .cuisine-wrap -->

        <section class="restaurant-wrap">
            <div class="container">
                <div class="title_home d-flex justify-content-between">
                    <h2>{{ __('Visiting Places') }}</h2>
                    <a href="{{ route('visiting_places') }}" class="btn btn-primary btn-sm"
                        style="outline: none; border:none">See More</a>
                </div>
                <div class="restaurant-sliders slick-sliders">
                    <div class="restaurant-slider slick-slider" data-item="4" data-itemScroll="4" data-arrows="true"
                        data-dots="true" data-tabletItem="2" data-tabletScroll="2" data-mobileItem="1"
                        data-mobileScroll="1">
                        @foreach ($placesArr as $place)
                            <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false"
                                style="width: 300px;" role="tabpanel" id="slick-slide10">
                                <div>
                                    <div class="place-item layout-02 place-hover"
                                        style="width: 100%; display: inline-block;">
                                        <div class="place-inner">
                                            <div class="place-thumb hover-img">
                                                @if ($place['imgPath'] != null)
                                                    <a class="entry-thumb" href="{{ url('place_detail', $place['id']) }}"
                                                        tabindex="0"><img src="{{ $place['imgPath'] }}"
                                                            alt="Boot Café"></a>
                                                @else
                                                    <a class="entry-thumb" href="{{ url('place_detail', $place['id']) }}"
                                                        tabindex="0"><img
                                                            src="{{ asset('/assets/images/favicon.png') }}"
                                                            alt="Boot Café"></a>
                                                @endif
                                                <!-- <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist   open-login  " data-id="19" tabindex="0">
                                                                <span class="icon-heart">
                                                                    <i class="la la-bookmark large"></i>
                                                                </span>
                                                            </a> -->
                                                <!-- <a class="entry-category rosy-pink" href="https://lara-restaurant.getgolo.com/search-listing?category%5B%5D=11" style="background-color:#f0626c;" tabindex="0">
                                                                <img src="{{ $place['imgPath'] }}" alt="Pizza">
                                                                <span>{{ $place['name'] }}</span>
                                                            </a> -->
                                            </div>
                                            <div class="entry-detail">
                                                <div class="entry-head">
                                                    <!-- <div class="place-type list-item">
                                                                        <span>Township</span>
                                                                </div>
                                                                <div class="place-city">
                                                                    <a href="#" tabindex="0">{{ $place['tsh_name'] }}</a>
                                                                </div> -->
                                                </div>
                                                <h4 class="place-title"><a href="{{ url('place_detail', $place['id']) }}"
                                                        tabindex="0">{{ $place['name'] }}</a></h4>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div><!-- .restaurant-slider -->
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div><!-- .restaurant-sliders -->
            </div>
        </section><!-- .restaurant-wrap -->

        {{--  <section class="featured-home featured-wrap">
            <div class="container">
                <div class="title_home">
                    <h2>{{__('Featured Cities')}}</h2>
                    <p>{{__('Explore restaurants & cafes by locality')}}</p>
                </div>
                <div class="featured-inner">
                    <div class="item">
                        <div class="flex">
                            <div class="flex-col">
                                @foreach ($popular_cities as $index => $city)
                                    @if ($index === 0)
                                        <div class="cities">
                                            <div class="cities-inner">
                                                <a href="{{route('page_search_listing', ['city[]' => $city->id])}}" class="hover-img">
                                                    <span class="entry-thumb"><img src="{{getImageUrl($city->thumb)}}" alt="{{$city->name}}"></span>
                                                    <span class="entry-details">
                                                    <h3>{{$city->name}}</h3>
                                                    <span>{{$city->places_count}} {{__('places')}}</span>
                                                </span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex-col">
                                @foreach ($popular_cities as $index => $city)
                                    @if ($index >= 1 && $index <= 2)
                                        <div class="cities">
                                            <div class="cities-inner">
                                                <a href="{{route('page_search_listing', ['city[]' => $city->id])}}" class="hover-img">
                                                    <span class="entry-thumb"><img src="{{getImageUrl($city->thumb)}}" alt="{{$city->name}}"></span>
                                                    <span class="entry-details">
                                                    <h3>{{$city->name}}</h3>
                                                    <span>{{$city->places_count}} {{__('places')}}</span>
                                                </span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="flex-col">
                                @foreach ($popular_cities as $index => $city)
                                    @if ($index >= 3 && $index <= 4)
                                        <div class="cities">
                                            <div class="cities-inner">
                                                <a href="{{route('page_search_listing', ['city[]' => $city->id])}}" class="hover-img">
                                                    <span class="entry-thumb"><img src="{{getImageUrl($city->thumb)}}" alt="{{$city->name}}"></span>
                                                    <span class="entry-details">
                                                    <h3>{{$city->name}}</h3>
                                                    <span>{{$city->places_count}} {{__('places')}}</span>
                                                </span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div><!-- .featured-inner -->
            </div><!-- .container -->
        </section> --}}
        <!-- .featured-wrap -->

        {{--   <section class="join-wrap" {!! $home_banner_app !!}>
            <div class="container">
                <div class="join-inner">
                    <h2>{{__('Restaurateurs Join Us')}}</h2>
                    <p>{{__('Join the more than 10,000 restaurants which fill seats and manage reservations with Golo.')}}</p>
                    <a href="#" class="btn" title="Learn More">{{__('Learn More')}}</a>
                </div>
            </div>
        </section><!-- .join-wrap --> --}}

        {{-- <section class="home-testimonials testimonials">
            <div class="container">
                <div class="title_home">
                    <h2>{{__('People Talking About Us')}}</h2>
                </div>
                <div class="testimonial-sliders slick-sliders">
                    <div class="testimonial-slider slick-slider" data-item="2" data-itemScroll="2" data-arrows="true" data-dots="true"
                         data-tabletItem="1" data-tabletScroll="1" data-mobileItem="1" data-mobileScroll="1">
                        @foreach ($testimonials as $item)
                            <div class="item">
                                <div class="testimonial-item flex">
                                    <div class="testimonial-thumb">
                                        <img class="ava" src="{{getImageUrl($item->avatar)}}" alt="Avatar">
                                        <img src="{{asset('assets/images/quote-active.png')}}" alt="Quote" class="quote">
                                    </div>
                                    <div class="testimonial-info">
                                        <p>{{$item->content}}</p>
                                        <div class="cite">
                                            <h4>{{$item->name}}</h4>
                                            <span>{{$item->job_title}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!-- .testimonial-slider -->
                    <div class="place-slider__nav slick-nav">
                        <div class="place-slider__prev slick-nav__prev">
                            <i class="las la-angle-left"></i>
                        </div><!-- .place-slider__prev -->
                        <div class="place-slider__next slick-nav__next">
                            <i class="las la-angle-right"></i>
                        </div><!-- .place-slider__next -->
                    </div><!-- .place-slider__nav -->
                </div><!-- .testimonial-sliders -->
            </div>
        </section><!-- .testimonials -->
 --}}
        {{--  <section class="blogs-wrap section-bg">
            <div class="container">
                <div class="title_home">
                    <h2>From Our Blog</h2>
                </div>
                <div class="blog-wrap">
                    <div class="row">
                        @foreach ($blog_posts as $post)
                            <div class="col-md-4">
                                <article class="post hover__box">
                                    <div class="post__thumb hover__box__thumb">
                                        <a title="{{$post->title}}" href="{{route('post_detail', [$post->slug, $post->id])}}"><img
                                                src="{{getImageUrl($post->thumb)}}" alt="{{$post->title}}"></a>
                                    </div>
                                    <div class="post__info">
                                        <ul class="post__category">
                                            @foreach ($post['categories'] as $cat)
                                                <li><a title="{{$cat->name}}" href="{{route('post_list', $cat->slug)}}">{{$cat->name}}</a></li>
                                            @endforeach
                                        </ul>
                                        <h3 class="post__title"><a title="{{$post->title}}"
                                                                   href="{{route('post_detail', [$post->slug, $post->id])}}">{{$post->title}}</a></h3>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                    <div class="button-wrap">
                        <a href="{{route('post_list_all')}}" class="btn" title="View more">View more</a>
                    </div>
                </div>
            </div>
        </section> --}}
        <!-- .blogs-wrap -->
    </main><!-- .site-main -->
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('./js/owl.carousel.js') }}"></script>
    <script>
        $(document).ready(function() {
            @if (session('warning'))
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: '{{ session('warning') }}',
                    showConfirmButton: false,
                    timer: 1500
                })
            @endif

            $('#slider>li:gt(0)').hide();
            setInterval(function() {
                $('#slider > li:first')
                    .fadeOut(1000)
                    .next()
                    .fadeIn(1000)
                    .end()
                    .appendTo('#slider');
            }, 5000);
        });
    </script>
@endpush

<!-- document.getElementById("text").innerHTML = "This is my website. Happy reading!";
  document.getElementsByTagName("marquee")[0].start(); -->
