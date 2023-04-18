@extends('frontend.layouts.template')
@php
$contact_title_bg = "style='background-image:url(images/contact-01.png)'";
@endphp
@section('main')
<main id="main" class="site-main contact-main">
   <div class="page-title page-title--small align-left" {!! $contact_title_bg !!}>
   <div class="container">
      <div class="page-title__content">
         <h1 class="page-title__name">{{__('About Us')}}</h1>
         <p class="page-title__slogan">{{__('Explore everything in Naypyitaw!')}}</p>
      </div>
   </div>
   </div><!-- .page-title -->
   <div class="site-content">
      <div class="container">
         <div class="company-info flex-inline">
            <img src="{{asset(setting('logo') ? 'uploads/' . setting('logo') : 'assets/images/assets/logo.png')}}" alt="logo" style="width: 50%;">
            <div class="ci-content">
               <br><br>
               <h2>NPT Guide</h2>
               <p style="text-align: justify;">NPT Guide လို့အမည်ပေးထားတဲ့အတိုင်း နေပြည်တော်ကိုလာရောက်လည်ပတ်ကြမည့်သူများနှင့် နေပြည်တော် အတွင်း နေထိုင်ကြတဲ့ သူတွေအတွက် လမ်းညွှန်ပေးမဲ့ ဆော့ဝဲလေးဖြစ်ပါတယ်။ </p>

               <p style="text-align: justify;">NPT Guide တွင် နေပြည်တော်မြို့နယ်အတွင်းရှိ ဆိုင်များ၊ ကားဂိတ်များ၊ ဟိုတယ်များ၊ လည်ပတ်စရာနေရာများ စသည်တို့ရဲ့ ဖုန်းနံပါတ် နှင့် မြေပုံတည်နေရာများကို စုစည်း ပြပေးထားတဲ့အတွက် နေပြည်တော်တို့ အလည်အပတ်လာရောက်ကြမယ့် သူတွေအတွက်ကော နေပြည်တော် အတွင်း နေထိုင်ကြတဲ့ သူတွေအတွက်လည်း လွယ်လွယ်ကူကူရှာဖွေကြည့်ရှုနိုင်ပါတယ်။</p>

               <p style="text-align: justify;">ဌာနဆိုင်ရာဖုန်းနံပါတ်များနှင့် အရေးပေါ်အခြေအနေ ဖြစ်ပေါ်လာပါကလည်း ဆက်သွယ်နိုင်ရန် အရေးပေါ်ဖုန်းနံပါတ်များကိုလည်း ထည့်သွင်းဖော်ပြပေးထားပါတယ်။</p>


               <p style="text-align: justify;">NPT Guide ဆော့ဝဲတွင် ကဏ္ဍအလိုက် မိမိတို့ လုပ်ငန်းများကို အခမဲ့ ထည့်သွင်းနိုင်ပါတယ်။ 
               မိမိတို့ လုပ်ငန်းများကိုထည့်သွင်းရာတွင် အခက်အခဲ့ရှိပါက ဆက်သွယ်ရန် ဖုန်းနံပါတ်၊ အီးမေးလ်လည်း ထည့်သွင်းပေးထားပါတယ်။
               </p>
            </div>
         </div>
         <br>

         <!-- .company-info -->
         {{-- <div class="our-team">
            <div class="container">
               <h2 class="title align-center">Meet Our Team</h2>
            </div>
            <div class="ot-content grid grid-4">
               <div class="grid-item ot-item hover__box">
                  <div class="hover__box__thumb"><img src="{{ asset('images/avatar_default.png')}}" alt=""></div>
                  <div class="ot-info">
                     <h3 style="color: #000;">Mg Mg</h3>
                     <span class="job" style="color: #000;">Co - founder</span>
                  </div>
               </div>
               <div class="grid-item ot-item hover__box">
                  <div class="hover__box__thumb"><img src="{{ asset('images/avatar_default.png')}}" alt=""></div>
                  <div class="ot-info">
                     <h3 style="color: #000;">Ma Ma</h3>
                     <span class="job" style="color: #000;">Marketing</span>
                  </div>
               </div>
               <div class="grid-item ot-item hover__box">
                  <div class="hover__box__thumb"><img src="{{ asset('images/avatar_default.png')}}" alt=""></div>
                  <div class="ot-info">
                     <h3 style="color: #000;">Kyaw Kyaw</h3>
                     <span class="job" style="color: #000;">Designer</span>
                  </div>
               </div>
               <div class="grid-item ot-item hover__box">
                  <div class="hover__box__thumb"><img src="{{ asset('images/avatar_default.png')}}" alt=""></div>
                  <div class="ot-info">
                     <h3 style="color: #000;">Aung Aung</h3>
                     <span class="job" style="color: #000;">developer</span>
                  </div>
               </div>
            </div>
            <!-- .ot-content -->
         </div>
         --}}
      </div>
   </div>
   <div class="img-box-inner">
                <div class="container">
                    <div class="title ld-title">
                        <h2>How It Works</h2>
                        {{-- <p>From its medieval origins to the digital era.</p> --}}
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('/assets/images/pelican.png')}}" alt="">
                                <h3>Discover</h3>
                                <p>Take a deep dive and explore in naypyitaw</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('assets/images/island.png')}}" alt="">
                                <h3>Advertise</h3>
                                <p>Find your service and product.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img-box-item">
                                <img src="{{asset('assets/images/surf.png')}}" alt="">
                                <h3>Plan your trip</h3>
                                <p>Find famous places in naypyitaw</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .img-box-inner -->
            <div class="features-inner">
                <div class="container">
                    <div class="title ld-title">
                        <h2>Best Features</h2>
                        <p>Lorem ipsum is placeholder text commonly used in the graphic.</p>
                    </div><!-- .title -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-01.png')}}" alt="Trending Ui/Ux Design">
                        </div>
                        <div class="features-info">
                            <h3>Trending <br> <span style="color: #dc512a;">Ui/Ux</span> Design</h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-02.png')}}" alt="Bringing it all together">
                        </div>
                        <div class="features-info">
                            <h3>Bringing it <br> all <span style="color: #dc512a;">together</span></h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                    <div class="features-item">
                        <div class="features-thumb">
                            <img src="{{asset('assets/images/features-03.png')}}" alt="Keep your audience update">
                        </div>
                        <div class="features-info">
                            <h3>Keep your <br> <span style="color: #dc512a;">audience</span> update</h3>
                            <p>Post directly to Instagram, email clients about what you’re up to, or send newsletters with announcements about new work or exhibitions. </p>
                            {{-- <a href="#" class="more" title="Read more">Read more</a> --}}
                        </div>
                    </div><!-- .features-item -->
                </div>
            </div><!-- .features -->
    <div class="landing-banner" style="background-image:url({{asset('/assets/images/ld-banner-02.png')}})">
                <div class="container">
                    <div class="lb-info">
                        <h2 style="color: #ffffff;">NPT Guide</h2>
                        <p style="color: #ffffff;">Download the app and explore in Naypyitaw.</p>
                        <div class="lb-button">
                            <a href="https://apps.apple.com/us/app/npt-guide/id1288682874" target="_blank" title="App store"><img src="{{asset('assets/images/app-store.png')}}" alt="App store"></a>
                            <a href="https://play.google.com/store/apps/details?id=me.myatminsoe.nptguide&hl=en"  target="_blank" title="Google play"><img src="{{asset('assets/images/google-play.png')}}" alt="Google play"></a>
                        </div>
                    </div><!-- .lb-info -->
                </div>
            </div><!-- .landing-banner -->
</main>
<!-- .site-main -->
@stop
