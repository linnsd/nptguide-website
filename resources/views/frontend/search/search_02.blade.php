@extends('frontend.layouts.template_02')
@section('main')
    <main id="main" class="site-main">
        <div class="archive-city">
            <div class="col-left">
                <div class="archive-filter">
                    <form action="#" class="filterForm" id="filterForm">
                        <div class="filter-head">
                            <h2>{{__('Filter')}}</h2>
{{--                            <a href="#" class="clear-filter"><i class="fal fa-sync"></i>Clear all</a>--}}
                            <a href="#" class="close-filter"><i class="las la-times"></i></a>
                        </div>
                        <div class="filter-box">
                            <h3>Townships</h3>
                            <div class="filter-list">
                                <div class="filter-group">
                                    @foreach($townships as $township)
                                        <div class="field-check">
                                            <label class="bc_filter" for="tsh_{{$township->id}}">
                                                <input type="checkbox" id="tsh_{{$township->id}}" name="township[]" value="{{$township->id}}" {{isChecked($township->id, $filter_township)}}>
                                                {{$township->tsh_name}}
                                                <span class="checkmark"><i class="la la-check"></i></span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="#" class="more open-more" data-close="Close" data-more="More">More</a>
                            </div>
                        </div>
                        <div class="filter-box">
                            <h3>Categories</h3>
                            <div class="filter-list">
                                <div class="filter-group">
                                    @foreach($categories as $cat)
                                        <div class="field-check">
                                            <label class="bc_filter" for="cat_{{$cat->id}}">
                                                <input type="checkbox" id="cat_{{$cat->id}}" name="category[]" value="{{$cat->id}}" {{isChecked($cat->id, $filter_category)}}>
                                                {{$cat->name}}
                                                <span class="checkmark"><i class="la la-check"></i></span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="#" class="more open-more" data-close="Close" data-more="More">{{__('More')}}</a>
                            </div>
                        </div>
                        <div class="form-button align-center">
                            {{-- <input type="hidden" name="keyword" value="{{$keyword}}"> --}}
                            <a href="#" class="btn">{{__('Apply')}}</a>
                        </div>
                    </form>
                </div><!-- .archive-fillter -->

                <div class="main-primary">
                    <div class="filter-mobile">
                        <ul>
                            <li><a class="mb-filter mb-open" href="#filterForm">{{__('Filter')}}</a></li>
                        </ul>
                        <div class="mb-maps"><a class="mb-maps" href="#"><i class="las la-map-marked-alt"></i></a></div>
                    </div>
                    <div class="top-area top-area-filter">
                        <span class="result-count"><span class="count">{{$count}}</span> {{__('results')}}</span>
{{--                        <a href="#" class="clear">Clear filter</a>--}}
                        <div class="select-box">
                        </div><!-- .select-box -->
                        {{-- <div class="show-map">
                            <span>{{__('Maps')}}</span>
                            <a href="#" class="icon-toggle"></a>
                        </div> --}}<!-- .show-map -->
                    </div>

                    <div class="area-places">
                        @if(count($eProviders)>0)
                        @foreach($eProviders as $eProvider)
                            @php
                                $imgPath = ($eProvider->has_media)?$eProvider->media[0]->url:"";
                            @endphp
                                <div class="place-item place-hover layout-02" data-maps="">
                                    <div class="place-inner">
                                        <div class="place-thumb">
                                            <a class="entry-thumb" href="{{ route('place_detail',$eProvider['id'])}}"><img src="{{$imgPath}}" alt=""></a>
                                            <!-- <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist   open-login  " data-id="19">
                                            <span class="icon-heart">
                                                <i class="la la-bookmark large"></i>
                                            </span>
                                            </a> -->
                                            <!-- <a class="entry-category rosy-pink" href="https://lara-restaurant.getgolo.com/search-listing?category%5B%5D=11" style="background-color:#f0626c;">
                                                <img src="{{$imgPath}}" alt="{{$eProvider['name']}}">
                                                <span>{{$eProvider['name']}}</span>
                                            </a> -->
                                        </div>
                                        <div class="entry-detail">
                                            <div class="entry-head">
                                                {{-- <div class="place-type list-item">
                                                    <span>Township</span>
                                                </div>
                                                <div class="place-city">
                                                    <a href="https://lara-restaurant.getgolo.com/search-listing?city%5B%5D=26">{{$eProvider['tsh_name']}}</a>
                                                </div> --}}
                                            </div>
                                            <h3 class="place-title"><a href="{{ route('place_detail',$eProvider['id'])}}">{{$eProvider['name']}}</a></h3>
                                            <!-- <div class="entry-bottom">
                                                <div class="place-preview">
                                                    <div class="place-rating">
                                                                                                                    <span>5.0</span>
                                                            <i class="la la-star"></i>
                                                                                                            </div>
                                                    <span class="count-reviews">(1 reviews)</span>
                                                </div>
                                                <div class="place-price">
                                                    <span>$$</span>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="p-3">
                                <p>{{__('Nothing found!')}}</p>
                                <p>{{__("We're sorry but we do not have any listings matching your search, try to change you search settings")}}</p>
                            </div>
                            @endif
                    </div>
                    
                    <div class="pagination">
                        {{$eProviders->render('frontend.common.pagination')}}
                    </div>
                </div><!-- .main-primary -->
            </div><!-- .col-left -->

        </div><!-- .archive-city -->
    </main><!-- .site-main -->
@stop

@push('scripts')
    <script src="{{asset('assets/js/page_business_category.js')}}"></script>
@endpush
