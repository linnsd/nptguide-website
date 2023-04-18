(function ($) {
    'use strict';

    $('input[type=text]').on('keydown', function (e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    });

    /**
     * Event click add more social
     */
    $('#social_addmore').click(function (event) {
        event.preventDefault();
        let social_list = $('.social_list');
        let social_item = $('.social_item').length;
        if (social_item < 6) {
            social_list.append(`
                    <div class="field-inline field-3col social_item">
                        <div class="field-group field-select">
                            <select name="social[${social_item}][name]" id="place_socials">
                                <option value="">Select network</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Youtube">Youtube</option>
                                <option value="Twitter">Twitter</option>
                                <option value="Pinterest">Pinterest</option>
                                <option value="Snapchat">Snapchat</option>
                            </select>
                            <i class="la la-angle-down"></i>
                        </div>
                        <div class="field-group field-input">
                            <input type="text" name="social[${social_item}][url]" placeholder="Enter URL include http or www">
                        </div>
                        <a href="#" class="social_item_remove pt-2">
                            <i class="la la-trash-alt"></i>
                        </a>
                    </div>
                `);
        }
    });
    $(document).on("click", ".social_item_remove", function (event) {
        event.preventDefault();
        $(this).parents('.field-3col').remove();
    });

    /**
     * Event select country => show list city
     */
    $('#select_country').change(function () {
        let country_id = $(this).val();
        let select_city = $('#select_city');
        let data_resp = callAPI({
            url: getUrlAPI(`${app_url}/cities/${country_id}`, 'full'),
            method: "GET"
        });
        data_resp.then(res => {
            let html = '';
            res.forEach(function (value, index) {
                html += `<option value="${value.id}">${value.name}</option>`;
            });
            select_city.find('option').remove();
            select_city.append(html);
        });
    });

    /**
     * Upload gallery
     */
    $('#gallery').change(function () {
        var form_data = new FormData();
        form_data.append('image', this.files[0]);
        form_data.append('_token', CSRF_TOKEN);
        $.ajax({
            url: getUrlAPI('/upload-image', 'api'),
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (res) {
                console.log("res");

                if (res.fail) {
                    alert(res.errors['image']);
                } else {
                    if (res.code === 200) {
                        let html = `
                                <div class="col-sm-2 media-thumb-wrap">
                                    <figure class="media-thumb">
                                        <img src="/uploads/${res.file_name}">
                                        <div class="media-item-actions">
                                            <a class="icon icon-delete" data-filename="${res.file_name}" href="#">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16">
                                                    <g fill="#5D5D5D" fill-rule="nonzero">
                                                        <path d="M14.964 2.32h-4.036V0H4.105v2.32H.07v1.387h1.37l.924 12.25H12.67l.925-12.25h1.369V2.319zm-9.471-.933H9.54v.932H5.493v-.932zm5.89 13.183H3.65L2.83 3.707h9.374l-.82 10.863z"></path>
                                                        <path d="M6.961 6.076h1.11v6.126h-1.11zM4.834 6.076h1.11v6.126h-1.11zM9.089 6.076h1.11v6.126h-1.11z"></path>
                                                    </g>
                                                </svg>
                                            </a>
                                            <input type="hidden" name="gallery[]" value="${res.file_name}">
                                            <span class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>
                                        </div>
                                    </figure>
                                </div>
                            `;
                        $('#gallery_preview').append(html);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseJSON);
                alert(xhr.responseJSON.message);
            }
        });
    });

    // Delete preview gallery
    $(document).on("click", ".icon-delete", function (event) {
        event.preventDefault();
        let thumbnail = $(this).closest('.media-thumb-wrap');
        thumbnail.remove();
    });

    // Show thumbnail preview
    $('#thumb_image').change(function () {
        previewUploadImage(this, 'thumb_preview')
    });

    // Add more opening hour
    $('#openinghour_addmore').click(function () {
        event.preventDefault();
        let openinghour_list = $('#time-opening');
        let openinghour_item = $('.openinghour_item').length;
        openinghour_list.append(`
                <div class="field-inline field-3col social_item">
                    <div class="field-group field-input">
                        <input type="text" class="form-control valid" name="opening_hour[${openinghour_item}][title]" placeholder="Enter day open">
                    </div>
                    <div class="field-group field-input">
                        <input type="text" class="form-control" name="opening_hour[${openinghour_item}][value]" placeholder="Enter time open">
                    </div>
                    <a href="#" class="openinghour_item_remove pt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16">
                            <g fill="#5D5D5D" fill-rule="nonzero">
                                <path d="M14.964 2.32h-4.036V0H4.105v2.32H.07v1.387h1.37l.924 12.25H12.67l.925-12.25h1.369V2.319zm-9.471-.933H9.54v.932H5.493v-.932zm5.89 13.183H3.65L2.83 3.707h9.374l-.82 10.863z"/>
                                <path d="M6.961 6.076h1.11v6.126h-1.11zM4.834 6.076h1.11v6.126h-1.11zM9.089 6.076h1.11v6.126h-1.11z"/>
                            </g>
                        </svg>
                    </a>
                </div>
            `);
    });
    $(document).on("click", ".openinghour_item_remove", function (event) {
        event.preventDefault();
        $(this).parents('.field-3col').remove();
    });

})(jQuery);


// function placeMap() {
let place_lat = parseFloat($('#place_lat').val()) || -33.8688;
let place_lng = parseFloat($('#place_lng').val()) || 151.2195;
let place_icon_marker = $('#place_icon_marker').val();

var map_location = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
    center: [place_lng, place_lat], // starting position [lng, lat]
    zoom: 12 // starting zoom
});

var marker_location = new mapboxgl.Marker()
    .setLngLat([place_lng, place_lat])
    .addTo(map_location);

var geocoder = new MapboxGeocoder({
    accessToken: mapboxgl.accessToken,
    mapboxgl: mapboxgl
});

geocoder.addTo(map_location);


controlMarker();

function controlMarker() {
    // This event listener will call addMarker() when the map is clicked.
    map_location.on('click', function(event) {
        if( jQuery('body .lock-marker').length == 0 ) {
            var lngLat = marker_location.getLngLat();
            marker_location.setLngLat(event.lngLat).addTo(map_location);
            $( '#place_lat' ).val(lngLat.lat);
            $( '#place_lng' ).val(lngLat.lng);
        }
    });
    
    function onDragEnd() {
        var lngLat = marker_location.getLngLat();
        marker_location.setLngLat([lngLat.lng, lngLat.lat]);
        $( '#place_lat' ).val(lngLat.lat);
        $( '#place_lng' ).val(lngLat.lng);
    }
     
    marker_location.on('dragend', onDragEnd);
}

geocoder.on('result', function(ev) {
    $( '#place_lat' ).val(ev.result.geometry['coordinates'][1]);
    $( '#place_lng' ).val(ev.result.geometry['coordinates'][0]);
    jQuery( '.mapboxgl-marker:last-child' ).remove();
});