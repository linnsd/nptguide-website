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
                <div class="member-wrap">
                    <h1>{{__('Profile Setting')}}</h1>

                    {{-- @include('frontend.common.box-alert') --}}

                    <div class="row">
                        <div class="col-md-6">
                             <form class="member-profile form-underline" action="{{route('user_profile_update')}}" method="post" enctype="multipart/form-data">
                                @method('post')
                                @csrf
                                <h3>{{__('Avatar')}}</h3>
                                <div class="member-avatar">
                                    @if(Auth::user()->has_media)
                                        <img id="member_avatar" src="{{$img ?? ''}}" alt="{{Auth::user()->name}}">
                                    @else
                                        <img id="member_avatar" src="/assets/images/default_avatar.svg" alt="avatar">
                                    @endif

                                  
                                    <label for="upload_new" style="cursor: pointer;">
                                        <input id="upload_new" type="file" name="avatar" value="{{__('Upload new')}}" accept="image/*" >
                                        {{__('Upload new')}}
                                    </label>
                                </div>
                                <input type="hidden" name="id" value="{{Auth()->user()->id}}">
                                <h3>{{__('Basic Info')}}</h3>
                                <div class="field-input">
                                    <label for="name">{{__('Full name')}}</label>
                                    <input type="text" id="name" name="name" value="{{user()->name}}" placeholder="{{__('Enter your name')}}">
                                </div>
                                <!-- <div class="field-input">
                                    <label for="email">{{__('Email')}}</label>
                                    <input type="email" id="email" name="email" value="{{user()->email}}" disabled>
                                </div> -->
                                <div class="field-input">
                                    <label for="phone">{{__('Phone')}}</label>
                                    <input type="tel" id="phone" name="phone_number" value="{{user()->phone_number}}" placeholder="{{__('Enter phone number')}}">
                                </div>
                                <div class="field-submit">
                                    <input type="submit" value="{{__('Update')}}">
                                </div>
                            </form><!-- .member-profile -->
                        </div>
                        <div class="col-md-6">
                            <form class="member-password form-underline" action="{{route('user_password_update')}}" method="post">
                                @method('post')
                                @csrf
                                <h3>{{__('Change Password')}}</h3>
                                <div class="field-input">
                                    <label for="old_password">{{__('Old password')}}</label>
                                    <input type="password" name="old_password" placeholder="{{__('Enter old password')}}" id="old_password" required>
                                </div>
                                <input type="hidden" name="id" value="{{Auth()->user()->id}}">
                                <div class="field-input">
                                    <label for="new_password">{{__('New password')}}</label>
                                    <input type="password" name="password" placeholder="{{__('Enter new password')}}" id="new_password" required>
                                </div>
                                <div class="field-input">
                                    <label for="re_new">{{__('Re-new password')}}</label>
                                    <input type="password" name="password_confirmation" placeholder="{{__('Enter new password')}}" id="re_new" required>
                                </div>
                                <div class="field-submit">
                                    <input type="submit" value="{{__('Save')}}">
                                </div>
                            </form><!-- .member-password -->
                        </div>
                    </div>

                   

                    

                </div><!-- .member-wrap -->
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
@stop
@prepend('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

            @if(session('success'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session('success')}}',
                    showConfirmButton: false,
                    timer: 1500
                })
            @endif

            @if(session('error'))
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: '{{session('error')}}',
                    showConfirmButton: false,
                    timer: 1500
                })
            @endif

        var user_avatar = '';
        @if(isset($user) && $user->hasMedia('avatar'))
            user_avatar = {
            name: "{!! $user->getFirstMedia('avatar')->name !!}",
            size: "{!! $user->getFirstMedia('avatar')->size !!}",
            type: "{!! $user->getFirstMedia('avatar')->mime_type !!}",
            collection_name: "{!! $user->getFirstMedia('avatar')->collection_name !!}"
        };
                @endif
        var dz_user_avatar = $(".dropzone.avatar").dropzone({
                url: "{!!url('uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 1,
                init: function () {
                    @if(isset($user) && $user->hasMedia('avatar'))
                    dzInit(this, user_avatar, '{!! url($user->getFirstMediaUrl('avatar','thumb')) !!}')
                    @endif
                },
                accept: function (file, done) {
                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                },
                sending: function (file, xhr, formData) {
                    dzSending(this, file, formData, '{!! csrf_token() !!}');
                },
                maxfilesexceeded: function (file) {
                    dz_user_avatar[0].mockFile = '';
                    dzMaxfile(this, file);
                },
                complete: function (file) {
                    dzComplete(this, file, user_avatar, dz_user_avatar[0].mockFile);
                    dz_user_avatar[0].mockFile = file;
                },
                removedfile: function (file) {
                    dzRemoveFile(
                        file, user_avatar, '{!! url("/adminusers/remove-media") !!}',
                        'avatar', '{!! isset($user) ? $user->id : 0 !!}', '{!! url("/admin/uplaods/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
        });
        dz_user_avatar[0].mockFile = user_avatar;
        dropzoneFields['avatar'] = dz_user_avatar;
    </script>
    @endprepend