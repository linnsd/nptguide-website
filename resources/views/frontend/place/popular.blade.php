@extends('frontend.layouts.template_02')
@section('main')
    <style>
       .my-card {
        border:none;
        cursor: pointer;
        border-radius: 0.5rem;
        background-color: #FDFEFE;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px; 
        margin: 0.5rem 0;
       }
       .my-img img {
        width: 100%;
        height: 10rem;
        object-fit: cover;
       }
       .mywrapper {
        /* height: 60vh; */
       }
    </style>
    <div class="container mywrapper">
      <h3>Popular Places</h3>
      <div class="row">
        
          @foreach($places as $eProvider)
           <div class="w-100 col-md-4 p-2">
              <div class="card my-card">
                  <div class="my-img">
                      <a class="" href="{{ route('place_detail',$eProvider['id'])}}"><img src="{{$eProvider['imgPath']}}" alt=""></a>
                  </div>
                  <div class="my-title p-2">
                      <h3 class="place-title"><a href="{{ route('place_detail',$eProvider['id'])}}">{{$eProvider['name']}}</a></h3>
                  </div>
              </div>
          </div>   
      @endforeach 
      </div>
  </div> 
  <div class="container">
    <div class="d-flex pagination mt-5">
        {{$data->render('frontend.common.pagination')}}
    </div>
  </div>
@stop
@push('scripts')
    <script src="{{asset('assets/js/page_business_category.js')}}"></script>
@endpush