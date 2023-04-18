@extends('frontend.layouts.template_02')
@section('main')
<style>
    .fav-icon {
        cursor: pointer;
    }
    .active-heart {
        color: red;
    }
    .header-text {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .promotion-text p {
        margin-top: 1rem;
        font-size: 1rem;
        text-indent: 4rem;
    }
    .contact-item .label{
        width: 5rem;
    }
    .my-img {
      width: 20rem;
      border-radius: 0.5rem;
    }
    @media only screen and (max-width: 512px) {
        .back-btn {
            font-size: 0.75rem;
        }
        .header-text {
            font-size: 1rem;
        }
        .duration-text {
            font-size: 0.5rem;
        }
        .promotion-text p {
            font-size: 1rem;
            text-indent: 2rem;
        }
    }
</style>
    <main id="main" class="site-main place-04">
        <div class="place">
          {{-- {{dd($data[0])}} --}}
            <div class="slick-sliders">
                <div class="slick-slider photoswipe" data-item="1" data-arrows="false" data-itemScroll="1" data-dots="false" data-infinite="false" data-centerMode="false" data-centerPadding="0">
                    @if(isset($data[0]['imgPath']))
                    <div class="place-slider__item photoswipe-item"><a href="{{$data[0]['imgPath']}}" data-height="900" data-width="1200" data-caption=""><img src="{{$data[0]['imgPath']}}"></a></div>
                    @else
                        <div class="place-slider__item"><a href="#"><img src="https://via.placeholder.com/1280x500?text=GOLO" alt="slider no image"></a></div>
                    @endif
                </div>
                <div class="place-share">
                </div><!-- .place-share -->
                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <!-- Background of PhotoSwipe.
                           It's a separate element as animating opacity is faster than rgba(). -->
                    <div class="pswp__bg"></div>
                    <!-- Slides wrapper with overflow:hidden. -->
                    <div class="pswp__scroll-wrap">
                        <!-- Container that holds slides.
                              PhotoSwipe keeps only 3 of them in the DOM to save memory.
                              Don't modify these 3 pswp__item elements, data is added later on. -->
                        <div class="pswp__container">
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                        </div>
                        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                        <div class="pswp__ui pswp__ui--hidden">
                            <div class="pswp__top-bar">
                                <!--  Controls are self-explanatory. Order can be changed. -->
                                <div class="pswp__counter"></div>
                                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                <button class="pswp__button pswp__button--share" title="Share"></button>
                                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                                <!-- element will get class pswp__preloader--active when preloader is running -->
                                <div class="pswp__preloader">
                                    <div class="pswp__preloader__icn">
                                        <div class="pswp__preloader__cut">
                                            <div class="pswp__preloader__donut"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div class="pswp__share-tooltip"></div>
                            </div>
                            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                            </button>
                            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                            </button>
                            <div class="pswp__caption">
                                <div class="pswp__caption__center"></div>
                            </div>
                        </div>
                    </div>
                </div><!-- .pswp -->
            </div><!-- .place-slider -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="place__left">
                            <ul class="place__breadcrumbs breadcrumbs">
                                {{-- <li><a title="Pyinmana" href="#">{{$arr['township']}}</a></li> --}}
                                    <li><a href="{{url('/place_detail/'.$data[0]['place_id'])}}" title="">{{ $data[0]['place_name'] }}</a></li>
                            </ul><!-- .place__breadcrumbs -->
                            <div class="place__box place__box--npd">
                                <h1>{{$data[0]['name']}}</h1>
                                <p></p>
                                <div class="place__meta">
                                </div><!-- .place__meta -->
                            </div><!-- .place__box -->
                        </div><!-- .place__left -->
                    </div>             
                    </div>
                </div>
            </div>
            <div class="container p-3">
                <a href="{{url('/')}}" style="text-decoration: underline; color:#DC512A" class="my-5 back-btn">Back To Home</a>
                <div class="d-flex my-3">
                    <div class="info-container col-md-6">
                      @if(isset($data[0]['imgPath']))
                        <img src="{{$data[0]['imgPath']}}" class="my-img">
                      @else
                        <img src="https://via.placeholder.com/1280x500?text=GOLO" alt="slider no image" class="my-img">
                      @endif
                      <div class="py-3">
                        <h3 class="my-header text-muted">{{$data[0]['name']}}</h3>
                        <p class="text-muted">Price : {{$data[0]['price']}} Ks</p>
                      </div>
                    </div>
                    <div class="description-container">
                      <h3 class="mb-2" style="color:#DC512A">Description</h3>
                      <div>{!! $data[0]['description'] !!}</div>
                    </div>
                </div>
            </div>
        </div><!-- .place -->
    </main><!-- .site-main -->
@stop

@push('scripts')

@endpush


