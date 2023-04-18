@extends('frontend.layouts.template')
@php
$contact_title_bg = "style='background-image:url(images/contact-01.png)'";
@endphp
@section('main')
<main id="main" class="site-main contact-main">
   <div class="page-title page-title--small align-left" {!! $contact_title_bg !!}>
   <div class="container">
      <div class="page-title__content">
         <h1 class="page-title__name">{{__('Privacy  Policy')}}</h1>
      </div>
   </div>
   </div><!-- .page-title -->
   <div class="site-content">
      <div class="container">
         <br>
         <!-- <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">Introduction</h2>
                      <p>We are committed to safeguarding the privacy of our website visitors; this policy sets out how we will treat your personal information.By using our website and agreeing to this policy, you consent to our use of cookies in accordance with the terms of this policy.</p>
                      
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">Collecting personal information</h2>
                     <p>We may collect, store and use the following kinds of personal information:</p>

                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
               <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">Using your personal information</h2>

                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
               <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">Security of your personal information</h2>

                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
               <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  </div>
               </div>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">User IDs and passwords</h2>

                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
               <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  </div>
               </div>
            </div>
         </div>
          <br>
         <div class="row">
            <div class="col-md-12">
                <div class=" flex-inline">
                  <div class="ci-content">
                     <h2 style="color: #dc512a;">Cancellation and suspension of account</h2>
                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
               <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                  </div>
               </div>
            </div>
         </div> -->
         {!! $privacyPolicy->content !!}
      </div>
   </div>
</main>
<!-- .site-main -->
@stop
