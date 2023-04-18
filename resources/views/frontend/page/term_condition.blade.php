@extends('frontend.layouts.template')
@php
$contact_title_bg = "style='background-image:url(images/contact-01.png)'";
@endphp
@section('main')
<main id="main" class="site-main contact-main">
   <div class="page-title page-title--small align-left" {!! $contact_title_bg !!}>
   <div class="container">
      <div class="page-title__content">
         <h1 class="page-title__name">{{__('Terms and Conditions')}}</h1>
      </div>
   </div>
   </div><!-- .page-title -->
   <div class="site-content">
      <div class="container">
         <br>
         {!! $termsAndCondtion->content !!}
      </div>
   </div>
</main>
<!-- .site-main -->
@stop
