<header id="header" class="site-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-8">
               @include('frontend.layouts.left_menu')
            </div><!-- .col-md-6 -->
            <?php  
                if (Auth::user() != null) {

                    $images = end(Auth::user()['media']);
                    // dd($images);
                    if (count($images)>0) {
                        $img_url = end($images)['url'];
                    }
                    
                }
             ?>

            <div class="col-md-6 col-4">
                <div class="right-header align-right">

                    @guest
                        <div class="right-header__login">
                            <!-- class="open-login" -->
                            <!-- <a title="Login"  href="{{route('login')}}">{{__('Login')}}</a> -->
                            <a title="Login" class="open-login" href="#">{{__('Login')}}</a>
                        </div><!-- .right-header__login -->
                        <div class="popup popup-form">
                            <a title="Close" href="#" class="popup__close">
                                <i class="las la-times la-24-black"></i>
                            </a><!-- .popup__close -->
                            <ul class="choose-form">
                                <li class="nav-login"><a title="Log In" href="#login">{{__('Log In')}}</a></li>
                                <li class="nav-signup"><a title="Sign Up" href="#register">Sign Up</a></li>
                            </ul>
                            <div class="popup-content">

                                <form action="{{route('user_login')}}" class="form-log form-content" id="login" method="post">
                                    @csrf
                                    <div class="field-input">
                                        <input type="text" id="phone_number" name="phone_number" placeholder="Phone no" required>
                                    </div>
                                    <div class="field-input">
                                        <input type="password" id="password" name="password" placeholder="Password" required>
                                    </div>
                                    <a title="Forgot password" class="forgot_pass" href="#">{{__('Forgot password')}}</a>
                                    {{--                                    <input type="submit" name="submit" value="Login">--}}
                                    <button type="submit" class="gl-button btn button w-100" id="submit_login">{{__('Login')}}</button>
                                </form>

                                {{-- register --}}
                                <form class="form-sign form-content" id="register" action="{{route('user_register')}}" method="post">
                                    @csrf
                                    <small class="form-text text-danger golo-d-none" id="register_error">error!</small>
                                    <div class="field-input">
                                        <input type="text" id="register_name" name="name" placeholder="Name" required>
                                    </div>
                                    <div class="field-input">
                                        <input type="number" id="register_phone" name="phone_number" placeholder="Phone No" required>
                                    </div>
                                    <div class="field-input">
                                        <input type="password" id="register_password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="field-input">
                                        <input type="password" id="register_password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                                    </div>
                                    <div class="field-check">
                                        <label for="accept">
                                            <input type="checkbox" id="accept" checked required>
                                            Accept the <a title="Terms" href="{{ url('term-condition') }}" style="color: #dc512a;" >Terms</a> and <a title="Privacy Policy" href="{{ url('privacy-policy') }}"  style="color: #dc512a;">Privacy Policy</a>
                                            <span class="checkmark">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="6" viewBox="0 0 8 6">
                                                <path fill="#FFF" fill-rule="nonzero" d="M2.166 4.444L.768 3.047 0 3.815 1.844 5.66l.002-.002.337.337L7.389.788 6.605.005z"/>
                                            </svg>
                                        </span>
                                        </label>
                                    </div>

                                    <div class="field-input" id="otp_ele">
                                        <p class="mb-3">OTP will send to (<span id="phone-preview"></span>)</p>
                                        <input type="text" id="codeToVerify" value="" placeholder="Your OTP Code..." name="otp">
                                        <div class="text-danger my-3" id="error">* OTP Code invalid</div>
                                        <div class="form-group" style="margin: 1rem 0;">
                                            <div id="recaptcha-container"></div>
                                          </div>
                                        <div class="field-group">
                                            <button type="button" class="btn" id="verify-btn">Verify</button>
                                        </div>
                                    </div>

                                    <button type="button" class="gl-button btn button w-100" id="submit_register">{{__('Sign Up')}}</button>
                                </form>

                            </div>
                        </div><!-- .popup-form -->
                    @else


                        <div class="account">

                            <a href="#" title="{{Auth::user()->name}}">
                                <span>
                                    @if(Auth::user()->has_media)
                                        <img src="{{ asset($img_url) }}" alt="{{Auth::user()->name}}">
                                    @else
                                    <img src="/assets/images/default_avatar.svg" alt="avatar">
                                    @endif
                                    {{Auth::user()->name}}
                                    <i class="la la-angle-down la-12"></i>
                                </span>
                            </a>
                            <div class="account-sub">
                                <ul>

                                    @php
                                        $rolename = Auth::user()->getRoleNames()->toArray();
                                    @endphp

                                    
                                    <li class=""><a href="{{ url('/user_profile') }}">{{__('Profile')}}</a></li>
                                    <li class=""><a href="{{url('/user_my_place')}}">{{__('My Places')}}</a></li>
                                    {{-- <li class=""><a href="">{{__('Wishlist')}}</a></li> --}}
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('Logout')}}</a>
                                        <form class="d-none" id="logout-form" action="{{ route('logout') }}" method="POST">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .account -->
                    @endguest
                    <div class="right-header__search">
                        <a title="Search" href="#" class="search-open">
                            <i class="las la-search la-24-black"></i>
                        </a>
                    </div>
                    @if (!Auth::guest())
                    <div class="right-header__button btn">
                        <a title="Add place" href="{{route('place_addnew')}}">
                            <i class="las la-plus la-24-white"></i>
                            <span>{{__('Add place')}}</span>
                        </a>
                    </div>

                    @else
                    <div class="right-header__button btn">
                         <a title="Add place" href="#" onclick="alert('You need Login!');">
                            <i class="las la-plus la-24-white"></i>
                            <span>{{__('Add place')}}</span>
                        </a>
                    </div>
                    @endif
                    <!-- .right-header__button -->
                </div><!-- .right-header -->
            </div><!-- .col-md-6 -->
        </div><!-- .row -->


    </div><!-- .container-fluid -->
</header><!-- .site-header -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://www.gstatic.com/firebasejs/7.21.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.21.1/firebase-auth.js"></script>
<script>
$(document).ready(function(){
     // init
     $("#otp_ele").hide();
    $("#error").hide();

    // Firebase configure
    const firebaseConfig = {
        apiKey: "AIzaSyBA1XdNYz0-Mp8PPLHhTaqbi05OM9hTNJ0",
        authDomain: "npt-guide.firebaseapp.com",
        projectId: "npt-guide",
        storageBucket: "npt-guide.appspot.com",
        messagingSenderId: "731489180619",
        appId: "1:731489180619:web:edd81be4fa815cf7d8b44e",
        measurementId: "G-XCBKHXKNBC"
    };


    // Initalize
    firebase.initializeApp(firebaseConfig);

    // Recaptcha
    var appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');


    // SEND OTP
    $("#submit_register").on("click",function(){

        if($('#register_name').val() == '' || $('#register_phone').val() == '' || $('#register_password').val() == '' || $('#register_password_confirmation').val() == '') {
            alert('please fill all required information!');
            return;
        }

        $("#otp_ele").show();
        $("#submit_register").hide();

        let phone = $("#register_phone").val();
        var phoneNumber = "";

        if(phone.startsWith('09')) {
            phoneNumber ='+959'+phone.slice(2);
        }else {
            phoneNumber = '+'+phone;
        }
        $("#phone-preview").text(phoneNumber);
        firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
        .then(function (confirmationResult) {
        // SMS sent. Prompt user to type the code from the message, then sign the
        // user in with confirmationResult.confirm(code).
        $("#recaptcha-container").hide();
        window.confirmationResult = confirmationResult;
        }).catch(function (error) {
        // Error; SMS not sent
        // ...
        console.log(error);
        });
    });

    // Verify OTP
    $("#verify-btn").on("click",function(){
        var code = $('#codeToVerify').val();
        $(this).attr('disabled', 'disabled');
        $(this).text('Processing..');

        confirmationResult.confirm(code).then(function (result) {
            // Success
            $("#register").submit();
            setTimeout(() => {
            $(this).text('Verify OTP');
            $("#error").hide();
            }, 2000);
            }.bind($(this))).catch(function (error) {
            
                // User couldn't sign in (bad verification code?)
                // ...
                $("#error").show();
                $(this).removeAttr('disabled');
                $(this).text('Invalid Code');
                setTimeout(() => {
                    $(this).text('Verify OTP');
                    $("#error").hide();
                }, 2000);
        }.bind($(this)));
                    });
    });
</script>

