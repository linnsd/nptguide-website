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
          {{-- {{dd($promotion)}} --}}
            <div class="slick-sliders">
                <div class="slick-slider photoswipe" data-item="1" data-arrows="false" data-itemScroll="1" data-dots="false" data-infinite="false" data-centerMode="false" data-centerPadding="0">
                    @if(isset($promotion[0]['imgPath']))
                    <div class="place-slider__item photoswipe-item"><a href="{{$promotion[0]['imgPath']}}" data-height="900" data-width="1200" data-caption=""><img src="{{$promotion[0]['imgPath']}}"></a></div>
                    @else
                        <div class="place-slider__item"><a href="#"><img src="https://via.placeholder.com/1280x500?text=GOLO" alt="slider no image"></a></div>
                    @endif
                </div>
                <div class="place-share">
                   {{--  <a title="Save" href="#" class="add-wishlist  data-id="1">
                        <i class="la la-bookmark la-24"></i>
                    </a> --}}
                    {{-- <a title="Share" href="#" class="share">
                        <i class="la la-share-square la-24"></i>
                    </a>
                    <div class="social-share">
                        <div class="list-social-icon">
                            <a class="facebook" href="#" onclick="window.open('https://www.facebook.com/sharer.php?u=' + window.location.href,'popUpWindow','height=550,width=600,left=200,top=100,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes');">
                                <i class="la la-facebook"></i>
                            </a>
                        </div>
                    </div> --}}
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
                                    <li><a href="{{url('/place_detail/'.$promotion[0]['place_id'])}}" title="">{{ $promotion[0]['place_name'] }}</a></li>
                            </ul><!-- .place__breadcrumbs -->
                            <div class="place__box place__box--npd">
                                <h1>{{$promotion[0]['title']}}</h1>
                                <p></p>
                                <div class="place__meta">
                                </div><!-- .place__meta -->
                            </div><!-- .place__box -->

                            {{-- <div class="place__box place__box-overview">
                                <h3 class="py-3 d-flex justify-content-between">{{__('Overview')}}
                                    <div class="d-flex justify-content-center align-content-center">@if(isFav($arr['id'])) Remove @else Add @endif Fav <a href="{{route('add_to_fav',$arr['id'])}}"><i title="Add to Fav" class="ml-2 la la-heart fav-icon @if(isFav($arr['id'])) active-heart @endif"></i></a></div>
                                </h3>
                                <div class="place__desc" style="text-align: justify;">
                                    {!! strip_tags($arr['description'])!!}
                                </div><!-- .place__desc -->
                            </div> --}}
                            <!-- .place__box -->
                        </div><!-- .place__left -->
                    </div>             
                    </div>
                </div>
            </div>
            <div class="container p-3">
                <a href="{{url('/')}}" style="text-decoration: underline; color:#DC512A" class="my-5 back-btn">Back To Home</a>
              <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="header-text">{{$promotion[0]['title']}}</div>
                <div class="ml-2 duration-text">
                    <span class="fw-bold"> <i class="la la-calendar large"></i> Duration</span> 
                    <span style="color:#DC512A">{{date('d-M-Y',strtotime($promotion[0]['from_date']))}} to {{date('d-M-Y',strtotime($promotion[0]['to_date']))}} </span>
                </div>
              </div>
              <div class="py-2 promotion-text">
                {!! $promotion[0]['description'] !!}
              </div>

              {{-- contact --}}
              <div class="mt-3">
                @if($promotion[0]['facebookurl'])
                <div class="contact-item d-flex">
                    <div class="label">Facebook :</div>
                    <div><a href="{{$promotion[0]['facebookurl']}}">{{$promotion[0]['facebookurl']}}</a></div>
                </div>
                @endif
                @if($promotion[0]['phone'])
                <div class="contact-item d-flex">
                    <div class="label">Phone :</div>
                    <div>{{$promotion[0]['phone']}}</div>
                </div>
                @endif
                <div></div>
              </div>

            </div>
        </div><!-- .place -->
    </main><!-- .site-main -->
@stop

@push('scripts')

@endpush


