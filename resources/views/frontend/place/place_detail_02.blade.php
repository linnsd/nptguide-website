@extends('frontend.layouts.template_02')
@section('main')
    <style>
        .fav-icon {
            cursor: pointer;
            /* color: #E5E8E8; */
        }

        .star-icon {
            color: gold;
        }

        .rating-star:hover {
            opacity: 0.7;
        }
        .active-heart {
            color: red;
        }
        .avg-header {
            font-size: 1rem;
        }
        .avg-rating {
            font-size: 2rem;
        }
    </style>
    <main id="main" class="site-main place-04">
        <div class="place">
            <div class="slick-sliders">
                <div class="slick-slider photoswipe" data-item="1" data-arrows="false" data-itemScroll="1" data-dots="false"
                    data-infinite="false" data-centerMode="false" data-centerPadding="0">
                    @if (isset($arr['media']))
                        @foreach ($arr['media'] as $gallery)
                            <div class="place-slider__item photoswipe-item"><a href="{{ $gallery['url'] }}"
                                    data-height="900" data-width="1200" data-caption=""><img src="{{ $gallery['url'] }}"
                                        alt="{{ $gallery['url'] }}"></a></div>
                        @endforeach
                    @else
                        <div class="place-slider__item"><a href="#"><img
                                    src="https://via.placeholder.com/1280x500?text=GOLO" alt="slider no image"></a></div>
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
                <div class="place-gallery">
                    <a class="show-gallery" title="Gallery" href="#">
                        <i class="la la-images la-24"></i>
                        {{ __('Gallery') }}
                    </a>
                </div><!-- .place-item__photo -->
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
                                {{-- {{dd($arr)}} --}}
                                <li><a href="{{url('search-listing?category%5B%5D='.$arr['cat_id'].'#')}}" title="{{ $arr['category'] }}">{{ $arr['category'] }}</a></li>
                            </ul><!-- .place__breadcrumbs -->
                            <div class="place__box place__box--npd">
                                <h1>{{ $arr['name'] }}</h1>
                                <p></p>
                                <div class="place__meta">
                                    {{-- <div class="place__reviews reviews">
											<span class="place__reviews__number reviews__number">
												
												<i class="la la-star"></i>
											</span>
                                        <span class="place__places-item__count reviews_count">(4 reviews)</span>
                                    </div> --}}
                                    {{-- <div class="place__currency">$$</div> --}}
                                    {{-- @if (isset($place_types))
                                        <div class="place__category">
                                            @foreach ($place_types as $type)
                                                <a title="{{$type->name}}" href="{{route('page_search_listing', ['amenities[]' => $type->id])}}">{{$type->name}}</a>
                                            @endforeach
                                        </div>
                                    @endif --}}
                                </div><!-- .place__meta -->
                            </div><!-- .place__box -->



                            <div class="place__box my-2">
                                <h3 class="py-3 d-flex justify-content-between">{{ __('Overview') }}
                                    <div class="d-flex justify-content-center align-content-center">
                                        @if (isFav($arr['id']))
                                            Remove
                                        @else
                                            Add
                                        @endif Favourite
                                        <a href="{{ route('add_to_fav', $arr['id']) }}"><i title="Add to Fav"
                                                class="ml-2 la la-heart fav-icon @if (isFav($arr['id'])) active-heart @endif"></i></a>
                                    </div>
                                </h3>
                                <div class="place__desc" style="text-align: justify;">
                                    {!! strip_tags($arr['description']) !!}
                                </div><!-- .place__desc -->
                            </div>
                            @if ($arr['services'])
                                <div class="place__box place__box-map">
                                    <h3 class="place__title--additional">
                                        Menu/Services
                                    </h3>
                                    <div class="menu-tab">
                                        <div class="menu-wrap active" id="diner">
                                            <div class="flex">

                                                @foreach ($arr['services'] as $menu)
                                                    <div class="menu-item">
                                                        <a href="{{ route('menu_service_detail', $menu['id']) }}"
                                                            class="d-flex">
                                                            <img src="{{ $menu['imgurl'] }}" alt="menu">
                                                            <div class="menu-info">
                                                                <h5>{{ $menu['name'] }}</h5>
                                                                {{-- <p>{!! $menu['description']!!}</p> --}}
                                                                @if ($menu['price'] != '')
                                                                    <span
                                                                        class="price">{{ number_format($menu['price']) }}
                                                                        Ks</span>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .place__box -->
                            @endif

                            {{-- tsn review section start --}}
                        <div class="container p-0 my-3">
                            <div class="d-flex justify-content-between p-0">
                                <h1 class="m-0 p-0">Reviews</h1>
                                @if(Auth()->user())
                                    @if(checkAlreadyReview(Auth()->user()->id,$arr['id']))
                                        <a href="" class="btn btn-primary open-rating" style="border:none;"
                                        data-toggle="modal" data-target="#exampleModalCenter">Add Review</a>
                                    @endif
                                @endif
                                
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('add_review') }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h3 class="modal-title" id="exampleModalLongTitle">Review</h3>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <div>
                                                            <label for="">Please Rate Your Experience</label>
                                                        </div>
                                                    <div class="d-flex">
                                                            <i style="font-size: 1.5rem; cursor:pointer;"
                                                            id="1star" class="la la-star rating-star"></i>
                                                            <i style="font-size: 1.5rem; cursor:pointer;"
                                                                id="2star" class="la la-star rating-star"></i>
                                                            <i style="font-size: 1.5rem; cursor:pointer;"
                                                                id="3star" class="la la-star rating-star"></i>
                                                            <i style="font-size: 1.5rem; cursor:pointer;"
                                                                id="4star" class="la la-star rating-star"></i>
                                                            <i style="font-size: 1.5rem; cursor:pointer;"
                                                                id="5star" class="la la-star rating-star"></i>
                                                    </div>
                                                    </div>
                                                    <input type="hidden" name="rating_grade" id="rating_grade">
                                                    <input type="hidden" name="eprovider_id" id="eprovider_id"
                                                        value="{{ $arr['id'] }}">
                                                    <div class="form-group">
                                                        <label for="remark" class="my-1">Additional Comment</label>
                                                        <textarea placeholder="Write your thoughts" name="remark" id="remark" class="form-control" rows="10" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    {{-- <button type="button" style="border:none;" class="btn btn-secondary" data-dismiss="modal">Cancel</button> --}}
                                                    <button type="submit" style="border:none;"
                                                        class="btn btn-primary">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-list my-2">
                                <div class="rating-label p-2" style="background: #eee;">
                                    <p>Rating Summary</p>
                                </div>

                                <div class="d-flex">
                                    <div class="leftStar col-md-6">
                                         {{-- 5 Stars --}}
                                        <div class="p-2">
                                            <div class="d-flex" style="align-items: center; justify-content:start;">
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <div class="ml-4 text-muted"> {{ getTotalRating(5, $arr['id']) }}</div>
                                            </div>
                                        </div>

                                        {{-- 4 stars --}}
                                        <div class="p-2">
                                            <div class="d-flex" style="align-items: center; justify-content:start;">
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star"></i>
                                                <div class="ml-4 text-muted"> {{ getTotalRating(4, $arr['id']) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 3 Stars --}}
                                        <div class="p-2">
                                            <div class="d-flex" style="align-items: center; justify-content:start;">
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <div class="ml-4 text-muted"> {{ getTotalRating(3, $arr['id']) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 2 Stars --}}
                                        <div class="p-2">
                                            <div class="d-flex" style="align-items: center; justify-content:start;">
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <div class="ml-4 text-muted"> {{ getTotalRating(2, $arr['id']) }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- 1 Star --}}
                                        <div class="p-2">
                                            <div class="d-flex" style="align-items: center; justify-content:start;">
                                                <i class="la la-star star-icon"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <i class="la la-star"></i>
                                                <div class="ml-4 text-muted"> {{ getTotalRating(1, $arr['id']) }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="rightStar col-md-6 d-flex flex-column justify-content-center align-items-center" style="user-select: none;">
                                        <div class="avg-header">Overall Rating</div>
                                        <div class="avg-rating"> {{ round(getAverageStars($arr['id']), 1) }}</div>
                                        <div class="avg-stars">
                                            @for ($i = 1; $i <= floor(getAverageStars($arr['id'])); $i++)
                                                <i class="la la-star star-icon"></i>
                                            @endfor

                                            @php
                                                $normalStar = (int) (5 - floor(getAverageStars($arr['id'])));
                                            @endphp

                                            @for ($i = 1; $i <= $normalStar; $i++)
                                                <i class="la la-star"></i>
                                            @endfor
                                        </div>
                                        <div class="p-2">
                                            <p class="text">All Total Review {{ getTotalRatingCount($arr['id']) }}</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- comment section --}}
                                <div class="comment-container p-3">
                                    @forelse(getReviews($arr['id']) as $review)
                                    <div class="card p-3 my-2">
                                        <div class="">
                                            <div class="d-flex justify-content-between">
                                                <h4>{{$review->username}}</h4>
                                                <div style="font-size: 0.7rem">{{date('d-m-Y',strtotime($review->created_at))}}</div>
                                            </div>
                                            <div>
                                               @for ($i = 1; $i <=  $review->rating_grade; $i++)
                                                    <i class="la la-star star-icon"></i>
                                               @endfor
                                            </div>
                                        </div>
                                        <p>{{$review->remark}}</p>
                                    </div>
                                    @empty
                                        <p class="text text-center">There is no review</p>
                                    @endforelse
                                    
                                </div>
                            </div>
                        </div>
                         {{-- tsn review section end --}}


                            <div class="place__box place__box--reviews">
                                {{-- <h3 class="place__title--reviews">
                                    {{__('Review')}} ({{count($reviews)}})
                                    @if (isset($reviews))
                                        <span class="place__reviews__number"> {{$review_score_avg}}
                                            <i class="la la-star"></i>
                                        </span>
                                    @endif
                                </h3> --}}

                                {{--  <ul class="place__comments">
                                    @foreach ($reviews as $review)
                                        <li>
                                            <div class="place__author">
                                                <div class="place__author__avatar">
                                                    <a title="Nitithorn" href="#"><img src="{{getUserAvatar($review['user']['avatar'])}}" alt=""></a>
                                                </div>
                                                <div class="place__author__info">
                                                    <h4>
                                                        <a title="Nitithorn" href="#">{{$review['user']['name']}}</a>
                                                        <div class="place__author__star">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                                <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                                <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                                <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                                <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                                <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                            </svg>
                                                            @php
                                                                $width = $review->score * 20;
                                                                $review_width = "style='width:{$width}%'";
                                                            @endphp
                                                            <span {!! $review_width !!}>
																<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
																    <path fill="#23D3D3" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
																</svg>
																<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
																    <path fill="#23D3D3" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
																</svg>
																<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
																    <path fill="#23D3D3" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
																</svg>
																<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
																    <path fill="#23D3D3" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
																</svg>
																<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
																    <path fill="#23D3D3" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
																</svg>
															</span>
                                                        </div>
                                                    </h4>
                                                    <time>{{formatDate($review->created_at, 'd/m/Y')}}</time>
                                                </div>
                                            </div>
                                            <div class="place__comments__content">
                                                <p>{{$review->comment}}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul> --}}

                                {{--  @guest
                                    <div class="login-for-review account logged-out">
                                        <a href="#" class="btn-login open-login">{{__('Login')}}</a>
                                        <span>{{__('to review')}}</span>
                                    </div>
                                @else
                                    <div class="review-form">
                                        <h3>{{__('Write a review')}}</h3>
                                        <form id="submit_review">
                                            @csrf
                                            <div class="rate">
                                                <span>{{__('Rate This Place')}}</span>
                                                <div class="stars">
                                                    <a href="#" class="star-item" data-value="1" title="star-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                            <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="#" class="star-item" data-value="2" title="star-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                            <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="#" class="star-item" data-value="3" title="star-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                            <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="#" class="star-item" data-value="4" title="star-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                            <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                        </svg>
                                                    </a>
                                                    <a href="#" class="star-item" data-value="5" title="star-5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12">
                                                            <path fill="#DDD" fill-rule="evenodd" d="M6.12.455l1.487 3.519 3.807.327a.3.3 0 0 1 .17.525L8.699 7.328l.865 3.721a.3.3 0 0 1-.447.325L5.845 9.4l-3.272 1.973a.3.3 0 0 1-.447-.325l.866-3.721L.104 4.826a.3.3 0 0 1 .17-.526l3.807-.327L5.568.455a.3.3 0 0 1 .553 0z"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="field-textarea">
                                                <img class="author-avatar" src="{{getUserAvatar(user()->avatar)}}" alt="">
                                                <textarea name="comment" placeholder="Write a review"></textarea>
                                            </div>
                                            <div class="field-submit">
                                                <small class="form-text text-danger" id="review_error">error!</small>
                                                <input type="hidden" name="score" value="">
                                                <input type="hidden" name="place_id" value="3">
                                                <button type="submit" class="btn" id="btn_submit_review">{{__('Submit')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                @endguest --}}

                            </div><!-- .place__box -->
                        </div><!-- .place__left -->
                    </div>
                    <div class="col-lg-4">
                        <div class="place__box place__box-map">
                            <h3 class="place__title--additional">
                                {{ __('Location & Maps') }}
                            </h3>
                            <div class="maps">
                                <div id="golo-place-map"></div>
                                <input type="hidden" id="place_lat" value="{{ $arr['latitude'] }}">
                                <input type="hidden" id="place_lng" value="{{ $arr['longitude'] }}">
                                <input type="hidden" id="place_icon_marker"
                                    value="{{ asset('assets/images/marker.png') }}">
                            </div>
                        </div><!-- .place__box -->
                        <div class="place__box">
                            <h3>{{ __('Contact Info') }}</h3>
                            <ul class="place__contact">
                                @if ($arr['phone_number'] != '')
                                    <li>

                                        <i class="la la-phone"></i>

                                        <a href="tel:'{{ $arr['phone_number'] }}'">{{ $arr['phone_number'] }}</a>
                                    </li>
                                @endif
                                @if ($arr['fb_page_url'] != '')
                                    <li>
                                        <i class="la la-facebook"></i>
                                        <a href="{{ $arr['fb_page_url'] }}">{{ $arr['fb_page_url'] }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- .place__box bf -->
                    </div>

                    {{--  <div class="col-lg-4">
                        <div class="sidebar sidebar--shop sidebar--border">
                            <div class="widget-reservation-mini">
                                @if ($place->booking_type === \App\Models\Booking::TYPE_AFFILIATE)
                                    <h3>{{__('Booking online')}}</h3>
                                    <a href="#" class="open-wg btn">{{__('Book now')}}</a>
                                @elseif($place->booking_type === \App\Models\Booking::TYPE_BOOKING_FORM)
                                    <h3>{{__('Make a reservation')}}</h3>
                                    <a href="#" class="open-wg btn">{{__('Book now')}}</a>
                                @elseif($place->booking_type === \App\Models\Booking::TYPE_CONTACT_FORM)
                                    <h3>{{__('Send me a message')}}</h3>
                                    <a href="#" class="open-wg btn">{{__('Send')}}</a>
                                @else
                                    <h3>{{__('Banner Ads')}}</h3>
                                    <a href="#" class="open-wg btn">{{__('View')}}</a>
                                @endif
                            </div>
                            @if ($place->booking_type === \App\Models\Booking::TYPE_AFFILIATE)
                                <aside class="widget widget-shadow widget-booking">
                                    <h3>{{__('Booking online')}}</h3>
                                    <a href="{{$place->link_bookingcom}}" class="btn" target="_blank" rel="nofollow">{{__('Book now')}}</a>
                                    <p class="note">{{__('By Booking.com')}}</p>
                                </aside><!-- .widget -->
                            @elseif($place->booking_type === \App\Models\Booking::TYPE_BOOKING_FORM)
                                <aside class="widget widget-shadow widget-reservation">
                                    <h3>{{__('Make a reservation')}}</h3>
                                    <form action="#" method="POST" class="form-underline" id="booking_form">
                                        @csrf
                                        <div class="field-select has-sub field-guest">
                                            <span class="sl-icon"><i class="la la-user-friends"></i></span>
                                            <input type="text" placeholder="Guest *" readonly>
                                            <i class="la la-angle-down"></i>
                                            <div class="field-sub">
                                                <ul>
                                                    <li>
                                                        <span>{{__('Adults')}}</span>
                                                        <div class="shop-details__quantity">
                                                        <span class="minus">
                                                            <i class="la la-minus"></i>
                                                        </span>
                                                            <input type="number" name="numbber_of_adult" value="0" class="qty number_adults">
                                                            <span class="plus">
                                                            <i class="la la-plus"></i>
                                                        </span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <span>{{__('Childrens')}}</span>
                                                        <div class="shop-details__quantity">
                                                        <span class="minus">
                                                            <i class="la la-minus"></i>
                                                        </span>
                                                            <input type="number" name="numbber_of_children" value="0" class="qty number_childrens">
                                                            <span class="plus">
                                                            <i class="la la-plus"></i>
                                                        </span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="field-select field-date">
                                            <span class="sl-icon"><i class="la la-calendar-alt"></i></span>
                                            <input type="text" name="date" placeholder="Date *" class="datepicker" autocomplete="off">
                                            <i class="la la-angle-down"></i>
                                        </div>
                                        <div class="field-select has-sub field-time">
                                            <span class="sl-icon"><i class="la la-clock"></i></span>
                                            <input type="text" name="time" placeholder="Time" readonly>
                                            <i class="la la-angle-down"></i>
                                            <div class="field-sub">
                                                <ul>
                                                    <li><a href="#">12:00 AM</a></li>
                                                    <li><a href="#">12:30 AM</a></li>
                                                    <li><a href="#">1:00 AM</a></li>
                                                    <li><a href="#">1:30 AM</a></li>
                                                    <li><a href="#">2:00 AM</a></li>
                                                    <li><a href="#">2:30 AM</a></li>
                                                    <li><a href="#">3:00 AM</a></li>
                                                    <li><a href="#">3:30 AM</a></li>
                                                    <li><a href="#">4:00 AM</a></li>
                                                    <li><a href="#">4:30 AM</a></li>
                                                    <li><a href="#">5:00 AM</a></li>
                                                    <li><a href="#">5:30 AM</a></li>
                                                    <li><a href="#">6:00 AM</a></li>
                                                    <li><a href="#">6:30 AM</a></li>
                                                    <li><a href="#">7:00 AM</a></li>
                                                    <li><a href="#">7:30 AM</a></li>
                                                    <li><a href="#">8:00 AM</a></li>
                                                    <li><a href="#">8:30 AM</a></li>
                                                    <li><a href="#">9:00 AM</a></li>
                                                    <li><a href="#">9:30 AM</a></li>
                                                    <li><a href="#">10:00 AM</a></li>
                                                    <li><a href="#">10:30 AM</a></li>
                                                    <li><a href="#">11:00 AM</a></li>
                                                    <li><a href="#">11:30 AM</a></li>
                                                    <li><a href="#">12:00 PM</a></li>
                                                    <li><a href="#">12:30 PM</a></li>
                                                    <li><a href="#">1:00 PM</a></li>
                                                    <li><a href="#">1:30 PM</a></li>
                                                    <li><a href="#">2:00 PM</a></li>
                                                    <li><a href="#">2:30 PM</a></li>
                                                    <li><a href="#">3:00 PM</a></li>
                                                    <li><a href="#">3:30 PM</a></li>
                                                    <li><a href="#">4:00 PM</a></li>
                                                    <li><a href="#">4:30 PM</a></li>
                                                    <li><a href="#">5:00 PM</a></li>
                                                    <li><a href="#">5:30 PM</a></li>
                                                    <li><a href="#">6:00 PM</a></li>
                                                    <li><a href="#">6:30 PM</a></li>
                                                    <li><a href="#">7:00 PM</a></li>
                                                    <li><a href="#">7:30 PM</a></li>
                                                    <li><a href="#">8:00 PM</a></li>
                                                    <li><a href="#">8:30 PM</a></li>
                                                    <li><a href="#">9:00 PM</a></li>
                                                    <li><a href="#">9:30 PM</a></li>
                                                    <li><a href="#">10:00 PM</a></li>
                                                    <li><a href="#">10:30 PM</a></li>
                                                    <li><a href="#">11:00 PM</a></li>
                                                    <li><a href="#">11:30 PM</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <input type="hidden" name="type" value="{{\App\Models\Booking::TYPE_BOOKING_FORM}}">
                                        <input type="hidden" name="place_id" value="{{$place->id}}">
                                        @guest()
                                            <button class="btn btn-login open-login">{{__('Send')}}</button>
                                        @else
                                            <button class="btn booking_submit_btn">{{__('Send')}}</button>
                                        @endguest
                                        <p class="note">{{__("You won't be charged yet")}}</p>

                                        <div class="alert alert-success alert_booking booking_success">
                                            <p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                    <path fill="#20D706" fill-rule="nonzero" d="M9.967 0C4.462 0 0 4.463 0 9.967c0 5.505 4.462 9.967 9.967 9.967 5.505 0 9.967-4.462 9.967-9.967C19.934 4.463 15.472 0 9.967 0zm0 18.065a8.098 8.098 0 1 1 0-16.196 8.098 8.098 0 0 1 8.098 8.098 8.098 8.098 0 0 1-8.098 8.098zm3.917-12.338a.868.868 0 0 0-1.208.337l-3.342 6.003-1.862-2.266c-.337-.388-.784-.589-1.207-.336-.424.253-.6.863-.325 1.255l2.59 3.152c.194.252.415.403.646.446l.002.003.024.002c.052.008.835.152 1.172-.45l3.836-6.891a.939.939 0 0 0-.326-1.255z"></path>
                                                </svg>
                                                {{__('You successfully created your booking.')}}
                                            </p>
                                        </div>
                                        <div class="alert alert-error alert_booking booking_error">
                                            <p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                    <path fill="#FF2D55" fill-rule="nonzero"
                                                          d="M11.732 9.96l1.762-1.762a.622.622 0 0 0 0-.88l-.881-.882a.623.623 0 0 0-.881 0L9.97 8.198l-1.761-1.76a.624.624 0 0 0-.883-.002l-.88.881a.622.622 0 0 0 0 .882l1.762 1.76-1.758 1.759a.622.622 0 0 0 0 .88l.88.882a.623.623 0 0 0 .882 0l1.757-1.758 1.77 1.771a.623.623 0 0 0 .883 0l.88-.88a.624.624 0 0 0 0-.882l-1.77-1.771zM9.967 0C4.462 0 0 4.462 0 9.967c0 5.505 4.462 9.967 9.967 9.967 5.505 0 9.967-4.462 9.967-9.967C19.934 4.463 15.472 0 9.967 0zm0 18.065a8.098 8.098 0 1 1 8.098-8.098 8.098 8.098 0 0 1-8.098 8.098z"></path>
                                                </svg>
                                                {{__('An error occurred. Please try again.')}}
                                            </p>
                                        </div>
                                    </form>
                                </aside><!-- .widget-reservation -->
                            @elseif($place->booking_type === \App\Models\Booking::TYPE_CONTACT_FORM)
                                <aside class="widget widget-shadow widget-booking-form">
                                    <h3>{{__('Send me a message')}}</h3>
                                    <form class="form-underline" id="booking_submit_form" action="" method="post">
                                        @csrf
                                        <div class="field-input">
                                            <input type="text" id="name" name="name" placeholder="Enter your name *" required>
                                        </div>
                                        <div class="field-input">
                                            <input type="text" id="email" name="email" placeholder="Enter your email *" required>
                                        </div>
                                        <div class="field-input">
                                            <input type="text" id="phone_number" name="phone_number" placeholder="Enter your phone">
                                        </div>
                                        <div class="field-input">
                                            <textarea type="text" id="message" name="message" placeholder="Enter your message"></textarea>
                                        </div>
                                        <input type="hidden" name="type" value="{{\App\Models\Booking::TYPE_CONTACT_FORM}}">
                                        <input type="hidden" name="place_id" value="{{$place->id}}">
                                        <button class="btn booking_submit_btn">{{__('Send')}}</button>

                                        <div class="alert alert-success alert_booking booking_success">
                                            <p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                    <path fill="#20D706" fill-rule="nonzero" d="M9.967 0C4.462 0 0 4.463 0 9.967c0 5.505 4.462 9.967 9.967 9.967 5.505 0 9.967-4.462 9.967-9.967C19.934 4.463 15.472 0 9.967 0zm0 18.065a8.098 8.098 0 1 1 0-16.196 8.098 8.098 0 0 1 8.098 8.098 8.098 8.098 0 0 1-8.098 8.098zm3.917-12.338a.868.868 0 0 0-1.208.337l-3.342 6.003-1.862-2.266c-.337-.388-.784-.589-1.207-.336-.424.253-.6.863-.325 1.255l2.59 3.152c.194.252.415.403.646.446l.002.003.024.002c.052.008.835.152 1.172-.45l3.836-6.891a.939.939 0 0 0-.326-1.255z"></path>
                                                </svg>
                                                {{__('You successfully created your booking.')}}
                                            </p>
                                        </div>
                                        <div class="alert alert-error alert_booking booking_error">
                                            <p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                    <path fill="#FF2D55" fill-rule="nonzero"
                                                          d="M11.732 9.96l1.762-1.762a.622.622 0 0 0 0-.88l-.881-.882a.623.623 0 0 0-.881 0L9.97 8.198l-1.761-1.76a.624.624 0 0 0-.883-.002l-.88.881a.622.622 0 0 0 0 .882l1.762 1.76-1.758 1.759a.622.622 0 0 0 0 .88l.88.882a.623.623 0 0 0 .882 0l1.757-1.758 1.77 1.771a.623.623 0 0 0 .883 0l.88-.88a.624.624 0 0 0 0-.882l-1.77-1.771zM9.967 0C4.462 0 0 4.462 0 9.967c0 5.505 4.462 9.967 9.967 9.967 5.505 0 9.967-4.462 9.967-9.967C19.934 4.463 15.472 0 9.967 0zm0 18.065a8.098 8.098 0 1 1 8.098-8.098 8.098 8.098 0 0 1-8.098 8.098z"></path>
                                                </svg>
                                                {{__('An error occurred. Please try again.')}}
                                            </p>
                                        </div>

                                    </form>
                                </aside><!-- .widget-reservation -->
                            @else
                                <aside class="sidebar--shop__item widget widget--ads">
                                    @if (setting('ads_sidebar_banner_image'))
                                        <a title="Ads" href="{{setting('ads_sidebar_banner_link')}}" target="_blank" rel="nofollow"><img src="{{asset('uploads/' . setting('ads_sidebar_banner_image'))}}" alt="banner ads golo"></a>
                                    @endif
                                </aside>
                            @endif
                        </div><!-- .sidebar -->

                    </div> --}}
                </div>
            </div>
        </div><!-- .place -->

        {{-- Promotion --}}
        @if (getPromotionByPlaceId($arr['id']))
            <div class="similar-places">
                <div class="container">
                    <h2 class="similar-places__title title">{{ __('Current Promotion') }}</h2>
                    <div class="similar-places__content">
                        <div class="row">
                            @foreach (getPromotionByPlaceId($arr['id']) as $promotion)
                                <div class="col-lg-3 col-md-6">
                                    <div class="places-item hover__box">
                                        <div class="places-item__thumb hover__box__thumb">
                                            <a title="{{ $promotion['title'] }}"
                                                href="{{ url('promotion_detail', $promotion['id']) }}"><img
                                                    src="{{ $promotion['imgPath'] }}"
                                                    alt="{{ $promotion['title'] }}"></a>
                                        </div>
                                        <div class="places-item__info">
                                            <div class="places-item__category">

                                            </div>
                                            <h3><a href="{{ url('promotion_detail', $promotion['id']) }}"
                                                    title="">{{ $promotion['title'] }}</a></h3>
                                            <div class="places-item__meta">
                                                <div class="places-item__reviews">
                                                    <span class="places-item__number">

                                                    </span>
                                                </div>
                                                <div class="places-item__currency">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif


        <div class="similar-places">
            <div class="container">
                <h2 class="similar-places__title title">{{ __('Similar places') }}</h2>
                <div class="similar-places__content">
                    <div class="row">
                        @foreach ($similar_places as $place)
                            <div class="col-lg-3 col-md-6">
                                <div class="places-item hover__box">
                                    <div class="places-item__thumb hover__box__thumb">
                                        <a title="{{ $place['name'] }}"
                                            href="{{ url('place_detail', $place['id']) }}"><img
                                                src="{{ $place['imgPath'] }}" alt="{{ $place['name'] }}"></a>
                                    </div>

                                    <div class="places-item__info">
                                        <div class="places-item__category">

                                            <a href="#" title="{{ $place['category_name'] }}"></a>

                                        </div>
                                        <h3><a href="{{ url('place_detail', $place['id']) }}" title="">{{ $place['name'] }}</a></h3>
                                        <div class="places-item__meta">
                                            <div class="places-item__reviews">
                                                <span class="places-item__number">

                                                </span>
                                            </div>
                                            <div class="places-item__currency">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main><!-- .site-main -->
@stop

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/page_place_detail.js') }}"></script>
    @if (setting('map_service', 'google_map') === 'google_map')
        <script src="{{ asset('assets/js/page_place_detail_googlemap.js') }}"></script>
    @else
        <script src="{{ asset('assets/js/page_place_detail_mapbox.js') }}"></script>
    @endif
    <script>
        $(document).ready(function() {

            @if(session('success'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session('success')}}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif

            $("#1star").click(function() {
                $("#1star").addClass("star-icon color-star");
                $("#2star").removeClass("star-icon");
                $("#3star").removeClass("star-icon");
                $("#4star").removeClass("star-icon");
                $("#5star").removeClass("star-icon");
                $("#rating_grade").val(1);
            });

            $("#2star").click(function() {
                $("#2star").addClass("star-icon color-star");
                $("#1star").addClass("star-icon color-star");
                $("#3star").removeClass("star-icon color-star");
                $("#4star").removeClass("star-icon color-star");
                $("#5star").removeClass("star-icon color-star");
                $("#rating_grade").val(2);
            });

            $("#3star").click(function() {
                $("#2star").addClass("star-icon color-star");
                $("#1star").addClass("star-icon color-star");
                $("#3star").addClass("star-icon color-star");
                $("#4star").removeClass("star-icon color-star");
                $("#5star").removeClass("star-icon color-star");
                $("#rating_grade").val(3);
            });

            $("#4star").click(function() {
                $("#2star").addClass("star-icon color-star");
                $("#1star").addClass("star-icon color-star");
                $("#3star").addClass("star-icon color-star");
                $("#4star").addClass("star-icon color-star");
                $("#5star").removeClass("star-icon color-star color-star");
                $("#rating_grade").val(4);
            });

            $("#5star").click(function() {
                $("#2star").addClass("star-icon color-star");
                $("#1star").addClass("star-icon color-star");
                $("#3star").addClass("star-icon color-star");
                $("#4star").addClass("star-icon color-star");
                $("#5star").addClass("star-icon color-star");
                $("#rating_grade").val(5);
            });
        });
    </script>
@endpush
