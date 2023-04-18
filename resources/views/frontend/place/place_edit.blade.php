@extends('frontend.layouts.template')
@push('css_lib')
    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">
    <link rel="stylesheet" href="{{asset('vendor/dropzone/min/dropzone.min.css')}}">
@endpush
@section('main')
    <main id="main" class="site-main listing-main">
        <div class="listing-nav">
            <div class="listing-menu nav-scroll">
                <ul>
                    <li class="active">
                        <a href="#genaral" title="Genaral">
                            <!-- <span class="icon"><i class="la la-cog"></i></span>
                            <span>{{__('Genaral')}}</span> -->
                            <i class="la la-cog"></i> Ganeral
                        </a>
                    </li>
                    
                    <li>
                        <a href="#location" title="Location">
                            <!-- <span class="icon"><i class="la la-map-marker"></i></span>
                            <span>{{__('Location')}}</span> -->
                            <i class="la la-map-marker"></i> Location
                        </a>
                    </li>
                    <li>
                        <a href="#contact" title="Contact info">
                            <!-- <span class="icon"><i class="la la-phone"></i></span>
                            <span>{{__('Contact info')}}</span> -->
                            <i class="la la-phone"></i> Contact info
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
        </div>

        <div class="listing-content">
            <h2>
                @if(isRoute('place_edit'))
                    {{__('Edit my place')}}
                @else
                    {{__('Add new place')}}
                @endif
            </h2>
            <form class="upload-form" id="new_place" action="{{route('update_place')}}" method="POST" enctype="multipart/form-data">
                @if(isRoute('place_edit'))
                    @method('POST')
                @endif
                @csrf
                <div class="listing-box" id="genaral">
                    <h3>{{__('Genaral')}}</h3>
                    
                    <div class="field-group">
                        <label for="description">{{__('Place Name')}}*</label>
                        <input type="text" id="place_name" name="name" value="{{$place->name}}" required placeholder="{{__('What the name of place')}}">
                    </div>
                    <input type="hidden" name="id" value="{{$place->id}}">
                    <div class="field-group field-select">
                        <label for="lis_category">{{__('Category')}} *</label>
                        
                        <select name="category" class="custom-select" id="select_city" required>
                            <option value="">{{__('Select Category')}}</option>
                            @foreach($categories as $cat)
                                <option value="{{$cat['id']}}" {{$place->category_id == $cat->id ? "selected":""}}>{{$cat['name']}}</option>
                            @endforeach
                        </select>
                        <i class="la la-angle-down"></i>
                    </div>
                    <div class="field-group">
                        <label for="description">{{__('Description')}}*</label>
                        <textarea class="form-control" id="description" name="description" rows="5">{{$place->description}}</textarea>
                    </div>

                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                    <!-- <div class="field-group field-file member-avatar">
                        <label for="thumb_image">{{__('Place Photo')}}</label>
                        <label for="thumb_image" class="preview">
                       
                        <input type="file" id="thumb_image" name="image[]" class="upload-file" required>
                            @if(count($img)>0)
                            <img id="member_avatar" src="{{asset(end($img)['url'])}}" alt="avatar" name="image[]">
                            @else
                            <img id="member_avatar" src="/images/image_default.png" alt="avatar">
                            @endif
                            
                            <i class="la la-cloud-upload-alt"></i> 
                        </label>
                    </div> -->
                    <!-- Image Field -->
                   
                </div><!-- .listing-box -->
              
                <div class="listing-box" id="location">
                    <h3>{{__('Location')}}</h3>
                    <label for="place_address">{{__('Place Address')}} *</label>
                    <div class="field-group">
                        <div class="field-group field-select">
                                <select name="tsh_id" class="custom-select" id="select_city" required>
                                    <option value="">{{__('Select city')}}</option>
                                    @foreach($townships as $city)



                                        <option value="{{$city['id']}}" {{$place->tsh_id == $city['id'] ? "selected" : ""}}>{{$city['tsh_name']}}</option>
                                    @endforeach
                                </select>
                                <i class="la la-angle-down"></i>
                            </div>
                    </div>
                    <div class="field-group">
                        <input type="text" id="pac-input" placeholder="{{__('Full Address')}}" value="{{$place->address}}" name="address" autocomplete="off" required/>
                    </div>
                    <div class="field-group">
                        <input type="text" id="latitude" placeholder="{{__('Latitude')}}" value="{{$place->latitude}}" name="latitude" autocomplete="off" required/>
                    </div>
                     <div class="field-group">
                        <input type="text" id="longitude" placeholder="{{__('Longitude')}}" value="{{$place->longitude}}" name="longitude" autocomplete="off" required/>
                    </div>
                <div class="form-group mb-0 row">
                <div class="col-md-3"></div>
                <div class="col-lg-8">
                 
                  <button type="button" style="border:none"  class="btn btn-xs  btn-primary" data-toggle="modal" data-target=".bd-example-modal-xl"><i data-feather="map-pin"></i> Map</button>
                  <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                  
                        <div class="modal-header">
                          <h5 class="modal-title h4" id="myExtraLargeModalLabel">Map</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div id="map" style="width:100%;height:600px;"></div>

                          <script>
                            // In the following example, markers appear when the user clicks on the map.
                            // The markers are stored in an array.
                            // The user can then click an option to hide, show or delete the markers.
                            let curentLat= "<?php echo $place->latitude ?>";
                            let currentLng =  "<?php echo $place->longitude ?>";
                            
                            let map;
                            let markers = [];

                            function initMap() {
                              const myLatLng = {  lat: parseFloat(curentLat), lng:parseFloat(currentLng) };
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
                              addMarker(myLatLng);
                            }

                            // Adds a marker to the map and push to the array.
                            function addMarker(location) {
                              if(string !=null || string !='' ){   
                                  var string = JSON.stringify(location);
                                  var slice =  location.toString().slice(1,-1);
                                  var myArray = slice.split(",");
                                  document.getElementById("latitude").value = (!isNaN(parseFloat(myArray[0])))?parseFloat(myArray[0]):parseFloat(curentLat);
                                  document.getElementById("longitude").value =(!isNaN(parseFloat(myArray[1])))?parseFloat(myArray[1]):parseFloat(currentLng);
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
                          <button type="button" class="btn btn-xs btn-success" data-dismiss="modal"><i class="btn-icon-prepend" data-feather="save"></i>Save</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

                </div><!-- .listing-box -->
                <div class="listing-box" id="contact">
                    <h3>Contact Info</h3>
                    <!-- <div class="field-group">
                        <label for="place_email">{{__('Email')}}</label>
                        <input type="email" id="place_email" value="" placeholder="{{__('Your email address')}}" name="email">
                    </div> -->
                    <div class="field-group">
                        <label for="place_number">{{__('Phone number')}}</label>
                        <input type="tel" id="place_number" value="{{$place->phone_number}}" placeholder="{{__('Your phone number')}}" name="phone_number">
                    </div>
                    <div class="field-group">
                        <label for="place_website">{{__('Facebook Url')}}</label>
                        <input type="text" id="place_website" value="{{$place->fburl}}" placeholder="{{__('Your website url')}}" name="fburl">
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
    
@endpush