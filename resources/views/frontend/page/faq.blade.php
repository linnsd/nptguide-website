@extends('frontend.layouts.template')
@php
$contact_title_bg = "style='background-image:url(images/contact-01.png)'";
@endphp
@section('main')
<main id="main" class="site-main">
   <div class="page-title page-title--small align-left" style="background-image:url(/assets/images/about-01.png)">
      <div class="container">
         <div class="page-title__content">
            <h1 class="page-title__name">Faqs</h1>
         </div>
      </div>
   </div>
   <!-- .page-title -->
   <div class="site-content">
      <div class="container">
         <h2 class="title align-center">How may we be of help?</h2>
         <ul class="accordion first-open">
            <li class="">
               <h3 class="accordion-title"><a href="#">What is NPT Guide?</a></h3>
               <div class="accordion-content" style="display: none;">
                  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
               </div>
            </li>
            <li class="">
               <h3 class="accordion-title"><a href="#">Why should I use NPT Guide?</a></h3>
               <div class="accordion-content" style="display: none;">
                  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
               </div>
            </li>
            <li class="">
               <h3 class="accordion-title"><a href="#">How to add place in NPT Guide?</a></h3>
               <div class="accordion-content" style="display: none;">
                  <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
               </div>
            </li>
         </ul>
      </div>
   </div>
   <!-- .site-content -->
</main>

<!-- .site-main -->
@stop
