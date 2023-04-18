@extends('frontend.layouts.template')
@section('main')
    <main id="main" class="site-main">
        <div class="site-content">
            <div class="member-menu">
                <div class="container">
                    @include('frontend.user.user_menu')
                </div>
            </div>
            <div class="container">
                <div class="member-place-wrap">
                    <div class="member-place-top flex-inline">
                        <h1>{{__('My Place')}}</h1>
                    </div><!-- .member-place-top -->
                    @include('frontend.common.box-alert')
                    {{-- <div class="member-filter">
                        <div class="mf-left">
                            <form id="my_place_filter" action="" method="GET">
                                <div class="field-select">
                                    <select class="my_place_filter" name="township_id">
                                        <option value="">{{__('All Townships')}}</option>
                                        @foreach($townships as $township)
                                            <option value="{{$township->id}}" {{isSelected($township->id, $filter['township'])}}>{{$township->tsh_name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="la la-angle-down"></i>
                                </div>
                                <div class="field-select">
                                    <select class="my_place_filter" name="category_id">
                                        <option value="0">{{__('All categories')}}</option>
                                        @foreach($categories as $cat)
                                            <option value="{{$cat->id}}" {{isSelected($cat->id, $filter['category'])}}>{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="la la-angle-down"></i>
                                </div>
                            </form>
                        </div><!-- .mf-left -->
                        <div class="mf-right">
                            <form action="" class="site__search__form" method="GET">
                                <div class="site__search__field">
										<span class="site__search__icon">
											<i class="la la-search"></i>
										</span><!-- .site__search__icon -->
                                    <input class="site__search__input" type="text" name="keyword" value="{{$filter['keyword']}}" placeholder="{{__('Search')}}">
                                </div><!-- .search__input -->
                            </form><!-- .search__form -->
                        </div><!-- .mf-right -->
                    </div> --}}<!-- .member-filter -->
                    <table class="member-place-list table-responsive">
                        <thead>
                        <tr>
                            <th></th>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Thumb')}}</th>
                            <th>{{__('Place name')}}</th>
                            <th>{{__('Township')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Status')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($places)>0)
                            @foreach($places as $i=>$place)
                                <tr>
                                    <td data-title=""></td>
                                    <td data-title="ID">{{++$i}}</td>
                                    <td data-title="Thumb">

                                      <?php  
                                        if ($place['imgPath'] != null) {

                                            $images = end($place['imgPath']);
                                            // dd($images);
                                            // $img_url = end($images);
                                        }
                                       ?>
                                       <img src="{{asset('storage/app/public/'.$images->id.'/'.$images->file_name)}}" style="width:200px;height: :200px;">
                                </td>
                                    <td data-title="Place name"><b>{{$place['name']}}</b></td>
                                    <td data-title="City">{{$place['tsh_name']}}</td>
                                    <td data-title="Category">
                                        {{ $place['category_name']}}
                                    </td>
                                    <td data-title="Status" >
                                        @if($place['available'] ==1)
                                            Available
                                        @else
                                            Not Available
                                        @endif
                                    </td>
                                    <td data-title="" class="place-action">
                                        <a href="{{route('place_edit', $place['id'])}}" class="edit" title="{{__('Edit')}}"><i class="las la-edit"></i></a>
                                        <a href="{{route('place_detail', $place['id'])}}" class="view" title="{{__('View')}}"><i class="la la-eye"></i></a>
                                        {{-- @if($place['available']==1)
                                            <a href="{{route('user_my_place_delete')}}" class="delete" title="{{__('Delete')}}" onclick="event.preventDefault(); if (confirm('are you sure?')) {document.getElementById('delete_my_place_form_{{$place['id']}}').submit();}">
                                                <i class="la la-trash-alt"></i>
                                                <form class="d-none" id="delete_my_place_form_{{$place['id']}}" action="{{route('user_my_place_delete')}}" method="POST">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" name="place_id" value="{{$place['id']}}">
                                                </form>
                                            </a>
                                        @endif --}}
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            {{__('No item found')}}
                        @endif
                        </tbody>
                    </table>
                    <div class="pagination align-left">
                       {{--  {{$places->appends(["city_id" => $filter['city'], "category_id" => $filter['category'], "keyword" => $filter['keyword']])->render('frontend.common.pagination')}} --}}
                    </div><!-- .pagination -->
                </div><!-- .member-place-wrap -->
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
@stop

@push('scripts')
    <script>
        $('.my_place_filter').change(function () {
            $('#my_place_filter').submit();
        });
    </script>
@endpush