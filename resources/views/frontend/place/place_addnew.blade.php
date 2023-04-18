@extends('frontend.layouts.template')
@section('main')
<link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
    <main id="main" class="site-main listing-main">
        <div class="listing-nav">
            <div class="listing-menu nav-scroll">
                <ul>
                    <li class="active">
                        <a href="#genaral" title="Genaral">
                            <span class="icon"><i class="la la-cog"></i></span>
                            <i class="la la-cog"></i>
                            <span>{{__('Genaral')}}</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#location" title="Location">
                            <span class="icon"><i class="la la-map-marker"></i></span>
                            <i class="la la-map-marker"></i>
                            <span>{{__('Location')}}</span>
                        </a>
                    </li>
                    <li>
                        <a href="#contact" title="Contact info">
                            <span class="icon"><i class="la la-phone"></i></span>
                            <i class="la la-phone"></i>
                            <span>{{__('Contact info')}}</span>
                        </a>
                    </li>
                   
                   <!--  <li>
                        <a href="#media" title="Media">
                            <span class="icon"><i class="la la-image"></i></span>
                            <span>{{__('Media')}}</span>
                        </a>
                    </li> -->
                </ul>
            </div>
        </div><!-- .listing-nav -->

        <div class="listing-content">
            <h2>
                @if(isRoute('place_edit'))
                    {{__('Edit my place')}}
                @else
                    {{__('Add new place')}}
                @endif
            </h2>
            <form class="upload-form" id="new_place" action="{{route('add_place')}}" method="POST" enctype="multipart/form-data">
                @if(isRoute('place_edit'))
                    @method('PUT')
                @endif
                @csrf
                <div class="listing-box" id="genaral">
                    <h3>{{__('Genaral')}}</h3>
                    
                    <div class="field-group">
                        <label for="description">{{__('Place Name')}}*</label>
                        <input type="text" id="place_name" name="name" value="" required placeholder="{{__('What the name of place')}}">
                    </div>
                    <div class="field-group field-select">
                        <label for="lis_category">{{__('Category')}} *</label>
                        
                        <select name="category" class="custom-select" id="select_city" required>
                            <option value="">{{__('Select Category')}}</option>
                            @foreach($categories as $cat)
                                <option value="{{$cat['id']}}">{{$cat['name']}}</option>
                            @endforeach
                        </select>
                        <i class="la la-angle-down"></i>
                    </div>
                    <div class="field-group">
                        <label for="description">{{__('Description')}}*</label>
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                    </div>
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                   
                    <div class="field-group field-file member-avatar">
                        <label for="thumb_image">{{__('Place Photo')}}</label>
                        <label for="thumb_image" class="preview">
                            
                        <input type="file" id="thumb_image" name="image[]" class="upload-file" required>
                            
                            <img id="member_avatar" src="/images/image_default.png" alt="avatar">
                            <i class="la la-cloud-upload-alt"></i> 
                        </label>
                    </div>
                    
                </div><!-- .listing-box -->
                <div class="listing-box" id="location">
                    <h3>{{__('Location')}}</h3>
                    <label for="place_address">{{__('Place Address')}} *</label>
                    <div class="field-group">
                        <div class="field-group field-select">
                                <select name="tsh_id" class="custom-select" id="select_city" required>
                                    <option value="">{{__('Select city')}}</option>
                                    @foreach($townships as $city)
                                        <option value="{{$city['id']}}">{{$city['tsh_name']}}</option>
                                    @endforeach
                                </select>
                                <i class="la la-angle-down"></i>
                            </div>
                    </div>
                    <div class="field-group">
                        <input type="text" id="pac-input" placeholder="{{__('Full Address')}}" value="" name="address" autocomplete="off" required/>
                    </div>
                    
                    <div class="field-group">
                        <input type="text" id="latitude" placeholder="{{__('Latitude')}}" value="" name="latitude" autocomplete="off" required/>
                    </div>
                    <div class="field-group">
                        <input type="text" id="longitude" placeholder="{{__('Longitude')}}" value="" name="longitude" autocomplete="off" required/>
                    </div>
                <div class="form-group mb-0 row">
                <div class="col-md-3"></div>
                <div class="col-lg-8">
                
                  @if($errors->first('longitude'))
                          <span class="error mt-2 text-danger">
                              <small>{{ trans('household.longitude') }}</small>
                          </span>
                  @endif 
                  <br>
                  <button type="button" style="border:none;"  class="btn btn-xs  btn-primary" data-toggle="modal" data-target=".bd-example-modal-xl"><i data-feather="map-pin"></i> Map</button>
                  <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                  
                        <div class="modal-header">
                          <h5 class="modal-title h4" id="myExtraLargeModalLabel">{{ trans('household.map') }}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div id="map" style="width:100%;height:600px;"></div>

                          <script>
                            let curentLat='';
                            let currentLng = '';
                            
                            let map;
                            let markers = [];

                            function initMap() {
                              const myLatLng = {  lat: 19.754438, lng: 96.202217 };
                              map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 15,
                                center: myLatLng,
                                // mapTypeId: "terrain",
                              });
                              // This event listener will call addMarker() when the map is clicked.
                              map.addListener("click", (event) => {
                                addMarker(event.latLng);
                              });
                              // Adds a marker at the center of the map.
                            //   addMarker(myLatLng);
                            }

                            // Adds a marker to the map and push to the array.
                            function addMarker(location) {
                                // console.log("here",location);
                              if(string !=null || string !='' ){   
                                  var string = JSON.stringify(location);
                                  var slice =  location.toString().slice(1,-1);
                                  var myArray = slice.split(",");
                                //   console.log(!isNaN(parseFloat(myArray[0])));
                                  document.getElementById("latitude").value = (!isNaN(parseFloat(myArray[0])))?parseFloat(myArray[0]):'19.754438';
                                  document.getElementById("longitude").value =(!isNaN(parseFloat(myArray[1])))?parseFloat(myArray[1]):'96.202217';
                              }
                              deleteMarkers();
                              const marker = new google.maps.Marker({
                                position: location,
                                map: map,
                              });
                              markers.push(marker);
                            }

                            // Sets the map on all markers in the array.
                            function setMapOnAll(map) {
                              for (let i = 0; i < markers.length; i++) {
                                markers[i].setMap(map);
                              }
                            }

                            // Removes the markers from the map, but keeps them in the array.
                            function clearMarkers() {
                              setMapOnAll(null);
                            }

                            // Shows any markers currently in the array.
                            function showMarkers() {
                              setMapOnAll(map);
                            }

                            // Deletes all markers in the array by removing references to them.
                            function deleteMarkers() {
                              clearMarkers();
                              markers = [];
                            }
                          </script>
                          <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd-rizPtjtbmnJbTozn8ip7lPWFuyWaG8&callback=initMap&libraries=&v=weekly" async ></script>
                        </div>
                        <div class="modal-footer">
                          <button type="button" style="border:none;" class="btn btn-xs btn-success" data-dismiss="modal"><i class="btn-icon-prepend" data-feather="save"></i>Save</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
                </div><!-- .listing-box -->
                <div class="listing-box" id="contact">
                    <h3>Contact Info</h3>
                    <div class="field-group">
                        <label for="place_email">{{__('Email')}}</label>
                        <input type="email" id="place_email" value="" placeholder="{{__('Your email address')}}" name="email">
                    </div>
                    <div class="field-group">
                        <label for="place_number">{{__('Phone number')}}</label>
                        <input type="tel" id="place_number" value="" placeholder="{{__('Your phone number')}}" name="phone_number">
                    </div>
                    <div class="field-group">
                        <label for="place_website">{{__('Website')}}</label>
                        <input type="text" id="place_website" value="" placeholder="{{__('Your website url')}}" name="fburl">
                    </div>
                </div><!-- .listing-box -->
               <!-- <div class="listing-box" id="media">
                    <h3>Media</h3>
                    <div class="field-group field-file">
                        <label for="thumb_image">{{__('Thumb image')}}</label>
                        <label for="thumb_image" class="preview w-100" style="height: 200px;">
                            <input type="file" id="thumb_image" name="thumb" class="upload-file" required>
                            <img id="thumb_preview" src="" alt=""/>
                            <i class="la la-cloud-upload-alt"></i>
                        </label>
                        <div class="field-note">{{__('Maximum file size: 1 MB')}}.</div>
                    </div>
                    <div class="field-group field-file">
                        <label for="gallery_img">{{__('Gallery Images')}}</label>
                        <div id="gallery_preview">
                            
                                    <div class="col-sm-2 media-thumb-wrap">
                                        <figure class="media-thumb">
                                            <img src="">
                                            <div class="media-item-actions">
                                                <a class="icon icon-delete" href="#">
                                                    <i class="la la-trash-alt"></i>
                                                </a>
                                                <input type="hidden" name="gallery[]" value="">
                                                <span class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>
                                            </div>
                                        </figure>
                                    </div>
                                
                        </div>
                        <label for="gallery" class="preview w-100">
                            <input type="file" id="gallery" class="upload-file">
                            <i class="la la-cloud-upload-alt"></i>
                        </label>
                        <div class="field-note">{{__('Maximum file size: 1 MB')}}.</div>
                    </div>
                    <div class="field-group">
                        <label for="place_video">{{__('Video')}}</label>
                        <input type="text" id="place_video" name="video" placeholder="{{__('Youtube, Vimeo video url')}}">
                    </div>
                </div> -->

                <div class="field-group field-submit">
                   
                    @guest
                        <a href="#" class="btn btn-login open-login">{{__('Login to submit')}}</a>
                    @else
                        @if(isRoute('place_edit'))
                            <input class="btn" type="submit" value="{{__('Update')}}">
                        @else
                            <input class="btn" type="submit" value="{{__('Submit')}}">
                        @endif
                    @endguest
                </div>

            </form>
        </div>
    </main>
@stop

@push('scripts')

   <script src="{{asset('vendor/dropzone/min/dropzone.min.js')}}"></script>
    <!-- <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script> -->
    <script type="text/javascript">
        Dropzone.autoDiscover = false;
        var dropzoneFields = [];

            var var16125363151854745906ble = [];
            @if(isset($eProvider) && $eProvider->hasMedia('image'))
            @forEach($eProvider->getMedia('image') as $media)
            var16125363151854745906ble.push({
                name: "{!! $media->name !!}",
                size: "{!! $media->size !!}",
                type: "{!! $media->mime_type !!}",
                uuid: "{!! $media->getCustomProperty('uuid'); !!}",
                thumb: "{!! $media->getUrl('thumb'); !!}",
                collection_name: "{!! $media->collection_name !!}"
            });
            @endforeach
            @endif
            var dz_var16125363151854745906ble = $(".dropzone").dropzone({
                url: "{!!url('/admin/uploads/store')!!}",
                addRemoveLinks: true,
                maxFiles: 5 - var16125363151854745906ble.length,
                init: function () {
                    @if(isset($eProvider) && $eProvider->hasMedia('image'))
                    var16125363151854745906ble.forEach(media => {
                        dzInit(this, media, media.thumb);
                    });
                    @endif
                },
                accept: function (file, done) {
                    dzAccept(file, done, this.element, "{!!config('medialibrary.icons_folder')!!}");
                },
                sending: function (file, xhr, formData) {
                    dzSendingMultiple(this, file, formData, '{!! csrf_token() !!}');
                },
                complete: function (file) {
                    dzCompleteMultiple(this, file);
                    dz_var16125363151854745906ble[0].mockFile = file;
                },
                removedfile: function (file) {
                    dzRemoveFileMultiple(
                        file, var16125363151854745906ble, '{!! url("/admin/eProviders/remove-media") !!}',
                        'image', '{!! isset($eProvider) ? $eProvider->id : 0 !!}', '{!! url("uploads/clear") !!}', '{!! csrf_token() !!}'
                    );
                }
            });
            dz_var16125363151854745906ble[0].mockFile = var16125363151854745906ble;
            dropzoneFields['image'] = dz_var16125363151854745906ble;
        </script>
@endpush