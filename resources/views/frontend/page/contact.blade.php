@extends('frontend.layouts.template')
@php
    $contact_title_bg = "style='background-image:url(images/contact-01.png)'";
@endphp
@section('main')
    <main id="main" class="site-main contact-main">
        <div class="page-title page-title--small align-left" {!! $contact_title_bg !!}>
            <div class="container">
                <div class="page-title__content">
                    <h1 class="page-title__name">{{ __('Contact Us') }}</h1>
                    <p class="page-title__slogan">{{ __('We want to hear from you.') }}</p>
                </div>
            </div>
        </div><!-- .page-title -->
        <div class="site-content site-contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-text">
                            <h2>{{ __('Our Offices') }}</h2>
                            <div class="contact-box">
                                <h3>{{ __('NPT Guide Office') }}</h3>
                                <p>{{ __('Office Address will go here.') }}</p>
                                <p>{{ __('+95 (09)123456779') }}</p>
                            </div>
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7510.033309444866!2d96.20262200000002!3d19.754447000000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf04984d50590d3ff!2sLinn%20IT%20%26%20Mobile%20Mart!5e0!3m2!1sen!2smm!4v1634890754621!5m2!1sen!2smm"
                                width="550" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-form">
                            {{-- @include('frontend.common.box-alert') --}}

                            <h2>{{ __('Get in touch with us') }}</h2>
                            <form action="{{ route('send_message') }}" method="POST" class="form-underline">
                                @csrf
                                @method('POST')
                                <div class="field-input">
                                    <input type="text" name="name" placeholder="{{ __('Name') }} *" required>
                                </div>
                                <div class="field-input">
                                    <input type="tel" name="phone_number" placeholder="{{ __('Phone number') }}">
                                </div>
                                <div class="field-textarea">
                                    <textarea name="note" placeholder="{{ __('Message') }}"></textarea>
                                </div>
                                <div class="field-submit">
                                    <input type="submit" value="{{ __('Send Message') }}" class="btn">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
@stop

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 1500
            })
        @endif
    </script>
@endpush
