@extends('frontend.layouts.template_02')
@section('main')
    <style>
       .my-card {
        overflow: hidden;
        border:none;
        cursor: pointer;
        border-radius: 0.5rem;
        background-color: #FDFEFE;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; 
        margin: 0.5rem 0;
        height: 15rem;
       }
       .my-img img {
        width: 100%;
        height: 10rem;
        object-fit: cover;
       }
    </style>
    <div class="container mywrapper">
        <h1>Promotions List</h1>
        <div class="content">
            <div class="row">
              @forelse ($promotions as $eProvider)
              @php
                   $imgPath = ($eProvider->has_media)?$eProvider->media[0]->url:"";
               @endphp
                 <div class="w-100 col-md-4 p-2">
                   <div class="card my-card">
                       <div class="my-img">
                           <a class="" href="{{ route('promotion_detail',$eProvider['id'])}}"><img src="{{$imgPath}}" alt=""></a>
                       </div>
                       <div class="my-title p-2">
                           <div class="d-flex justify-content-between">
                            <h4 class="place-title"><a href="{{ route('promotion_detail',$eProvider['id'])}}">{{$eProvider['title']}}</a></h4>
                            <p>
                              <span class="text text-muted" style="font-size: 0.75rem;">{{date('d-M-Y',strtotime($eProvider['from_date']))}} to {{date('d-M-Y',strtotime($eProvider['to_date']))}} </span>
                            </p>
                           </div>
                       </div>
                   </div>
               </div> 
              @empty
               <p class="text text-muted">There is no current promotion yet.</p>
              @endforelse
            </div>
        </div>
    </div> 

    <div class="container">
        <div class="d-flex pagination mt-5">
            {{$promotions->render('frontend.common.pagination')}}
        </div>
      </div>
@stop
@push('scripts')
    <script src="{{asset('assets/js/page_business_category.js')}}"></script>
@endpush