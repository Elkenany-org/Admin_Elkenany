@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100px;
	}
	#avatar:hover{
		width: 100px;
		cursor: pointer;
	}
  .img img{
		width:150px;
		height:150px;
		margin-right:20px;
		margin-top:20px;
	}

	#gallery-photo-add {
    display: inline-block;
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 50px;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
	}
</style>
@endsection

@section('content')
<div class="container-fluid">
<div class="card card-primary card-outline">

  <div class="card-body">
  <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

    {{-- edit --}}
    <li class="nav-item">
      <a class="nav-link active" id="custom-content-below-farm-tab" data-toggle="pill" href="#custom-content-below-farm" role="tab" aria-controls="custom-content-below-farm" aria-selected="true">بيانات المُزارع</a>
    </li>

    {{-- images --}}
    <li class="nav-item">
      <a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور المزرعة</a>
    </li>


  </ul>
  </div>
<div class="tab-content" id="custom-content-below-tabContent">
  {{-- edit --}}
  <div class="tab-pane fade show active" id="custom-content-below-farm" role="tabpanel" aria-labelledby="custom-content-below-farm-tab">
    <div class="row">
      <div class="col-sm-12">
      <div class="card-header">
        <h6 class="m-0" style="display: inline;">تعديل مُزارع <span class="text-primary"> {{$farmer->name}} </span></h6>
      </div>
      <div class="row">
        <div class="col-sm-12">
            <div class="card-body">
              <form action="{{route('Updatefarmer')}}" method="post" enctype="multipart/form-data">
                <div class="row">
                  {{csrf_field()}}
                  <input type="hidden" name="id" value="{{$farmer->id}}">
                  <input type="hidden"  value="{{$farmer->latitude}}" id="latitude" name="latitude">
                  <input type="hidden" value="{{$farmer->longitude}}" id="longitude"  name="longitude">
                  {{-- avatar --}}
                  <div class="col-sm-2" style="margin-bottom: 20px">
                    <div class="from-group ">
                      <label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
                      <input type="file" name="avatar" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                      <img src="{{asset('uploads/farmers/avatar/'.$farmer->avatar)}}" onclick="ChooseAvatar()" id="avatar">
                    </div>
                  </div>

                  {{-- details --}}
                  <div class="col-sm-10">
                    <div class="row">
                      {{-- name --}}
                      <div class="col-sm-4" style="margin-top: 10px">
                        <div class="from-group">
                          <label class="text-primary">إسم المُزارع : <span class="text-danger">*</span></label>
                          <input type="text" name="name" class="form-control" value="{{$farmer->name}}" placeholder="إسم المُزارع" required="">
                        </div>
                      </div>

                      {{-- email --}}
                      <div class="col-sm-4" style="margin-top: 10px">
                        <div class="from-group">
                          <label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
                          <input type="email" name="email" class="form-control" value="{{$farmer->email}}" placeholder="البريد الإلكتروني" required="">
                        </div>
                      </div>
                      {{-- phone --}}
                      <div class="col-sm-4">
                        <div class="from-group" style="margin-top: 10px">
                          <label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
                          <input type="text" name="phone" class="form-control" value="{{$farmer->phone}}" placeholder="رقم الهاتف " required="">
                        </div>
                      </div>
                      <div class="col-sm-6" style="margin-top: 10px">
                        <div class="row">
                          {{-- sections --}}
                          <div class="col-sm-12" style="margin-top: 10px">
                            <label class="text-primary">  الاقسام <span class="text-danger">*</span></label>
                            <select name="section_id" class="form-control section_id" required>
                              <option value="" disabled selected>إختيار قسم</option>
                              @foreach($sections as $value)
                                <option value="{{$value->id}}" {{ isset($farmer) && $farmer->section_id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                              @endforeach
                            </select>
                          </div>
                          {{-- password --}}
                          <div class="col-sm-12" style="margin-top: 10px">
                            <div class="from-group">
                              <label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
                              <input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور">
                            </div>
                          </div>
                          {{-- farm name --}}
                          <div class="col-sm-12" style="margin-top: 10px">
                            <div class="from-group">
                              <label class="text-primary"> اسم المزرعة : <span class="text-danger">*</span></label>
                              <input type="text" name="farm_name" class="form-control" value="{{$farmer->farm_name}}" placeholder=" اسم المزرعة" required="">
                            </div>
                          </div>
                        </div>
                      </div>
                      {{-- address --}}
                      <div class="col-sm-6 marbo" style="margin-top: 10px">
                        <label  class="text-primary">العنوان</label>
                        <input type="text" id="pac-input" class="form-control" value="{{$farmer->address}}" placeholder="  " name="address">
                        <div class="validate-input" id="map" style="min-height: 200px;min-width: 250px;"></div>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- submit --}}
                <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto;margin-bottom:30px; " class="btn btn-outline-primary btn-block">حفظ</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  {{-- images --}}
  <div class="tab-pane fade" id="custom-content-below-images" role="tabpanel" aria-labelledby="custom-content-below-images-tab">
    <div class="row">
      <div class="col-sm-12">
        <form action="{{route('storeImagesfarmer')}}" method="post" enctype="multipart/form-data">
          {{csrf_field()}}
          <input type="hidden" name="id" value="{{$farmer->id}}">
          <div class="card-header">
            <h5 class="m-0" style="display: inline;" style="float: right">اضافة صور</h5>
            <div class="btn btn-primary" style="float: left;height:36px;padding:3px;">
              <input type="file" name="image[]" id="gallery"  style="display: none;" accept="image/*" multiple>
              <label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12 marbo img">
                <div class="gallery">
                @foreach($farmer->WaferFarmerImages as $key => $value)
                <div class="filtr-item image col-sm-2"  style="position: relative;display: inline-block;" data-category="1" data-sort="white sample">
                  <input type="hidden" name="idd" value="{{$value->id}}">
                  <button type="button" data-id="{{$value->id}}" class="btn btn-danger btn-sm del close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 5px 10px;color: #fff;border-radius: 50%;top: 30px;right: 20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    
                  <img src="{{asset('uploads/farmers/images/'.$value->image)}}" class="img-fluid mb-2  bounceIn" alt="black sample"/>
                </div>
                @endforeach
                </div>
              </div>
            </div>
          </div>
          <button style="width: 50%; margin-left: auto;margin-top:20px; margin-right: auto; margin-bottom:30px;  " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
        </form>
      </div>
    </div>
  </div>

</div> 
</div> 
</div>

@endsection

@section('script')
<script type="text/javascript">


//choose avatar
function ChooseAvatar(){$("input[name='avatar']").click()}
var loadAvatar = function(event) {
	var output = document.getElementById('avatar');
	output.src = URL.createObjectURL(event.target.files[0]);
};

$(".image").hover(function(){
  $(".del",this).css("display", "block") }, 
  function(){
    $(".del").css("display", "none");
});
$(".del").hover(function(){
  $(this).css("display", "block") }, 
  function(){

});

$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img class="img-fluid mb-2  bounceIn">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#gallery').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});
	
$(".close").click(function(){
    var id = $(this).data("id");
    var $ele = $(this).parent();
    $.ajax(
    {
        url: "{{route('DeleteImagefarmer')}}",
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
            "id": id,
        },
        success: function (){
          $ele.fadeOut().remove();
        }
    });
   
});

function ChooseAvatar1(){$("input[name='image[]']").click()}
</script>

<script>



$("#pac-input").focusin(function() {
  $(this).val('');
});

$('#latitude').val('');
$('#longitude').val('');


// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initAutocomplete() {
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: {{$farmer->latitude}}, lng: {{$farmer->longitude}} },
    zoom: 13,
    mapTypeId: 'roadmap'
  });

  // move pin and current location
  infoWindow = new google.maps.InfoWindow;
  geocoder = new google.maps.Geocoder();
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: {{$farmer->latitude}},
        lng: {{$farmer->longitude}}
      };
      map.setCenter(pos);
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(pos),
        map: map,
        title: 'موقعك الحالي'
      });
      markers.push(marker);
      marker.addListener('click', function() {
        geocodeLatLng(geocoder, map, infoWindow,marker);
      });
      // to get current position address on load
      google.maps.event.trigger(marker, 'click');
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } else {
    // Browser doesn't support Geolocation
    console.log('dsdsdsdsddsd');
    handleLocationError(false, infoWindow, map.getCenter());
  }

  var geocoder = new google.maps.Geocoder();
  google.maps.event.addListener(map, 'click', function(event) {
    SelectedLatLng = event.latLng;
    geocoder.geocode({
      'latLng': event.latLng
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          deleteMarkers();
          addMarkerRunTime(event.latLng);
          SelectedLocation = results[0].formatted_address;
          console.log( results[0].formatted_address);
          splitLatLng(String(event.latLng));
          $("#pac-input").val(results[0].formatted_address);
        }
      }
    });
  });
  function geocodeLatLng(geocoder, map, infowindow,markerCurrent) {
    var latlng = {lat: markerCurrent.position.lat(), lng: markerCurrent.position.lng()};
    /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
    $('#latitude').val(markerCurrent.position.lat());
    $('#longitude').val(markerCurrent.position.lng());

    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        if (results[0]) {
          map.setZoom(8);
          var marker = new google.maps.Marker({
            position: latlng,
            map: map
          });
          markers.push(marker);
          infowindow.setContent(results[0].formatted_address);
          SelectedLocation = results[0].formatted_address;
          $("#pac-input").val(results[0].formatted_address);

          infowindow.open(map, marker);
        } else {
          window.alert('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });
    SelectedLatLng =(markerCurrent.position.lat(),markerCurrent.position.lng());
  }
  function addMarkerRunTime(location) {
    var marker = new google.maps.Marker({
      position: location,
      map: map
    });
    markers.push(marker);
  }
  function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
  }
  function clearMarkers() {
    setMapOnAll(null);
  }
  function deleteMarkers() {
    clearMarkers();
    markers = [];
  }

  // Create the search box and link it to the UI element.
  var input = document.getElementById('pac-input');
  $("#pac-input").val("أبحث هنا ");
  var searchBox = new google.maps.places.SearchBox(input);
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);

  // Bias the SearchBox results towards current map's viewport.
  map.addListener('bounds_changed', function() {
    searchBox.setBounds(map.getBounds());
  });

  var markers = [];
  // Listen for the event fired when the user selects a prediction and retrieve
  // more details for that place.
  searchBox.addListener('places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }

    // Clear out the old markers.
    markers.forEach(function(marker) {
      marker.setMap(null);
    });
    markers = [];

    // For each place, get the icon, name and location.
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function(place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }
      var icon = {
        url: place.icon,
        size: new google.maps.Size(100, 100),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      markers.push(new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      }));


      $('#latitude').val(place.geometry.location.lat());
      $('#longitude').val(place.geometry.location.lng());

      if (place.geometry.viewport) {
        // Only geocodes have viewport.
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
  });
}
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    'Error: Your browser doesn\'t support geolocation.');
  infoWindow.open(map);
}
function splitLatLng(latLng){
  var newString = latLng.substring(0, latLng.length-1);
  var newString2 = newString.substring(1);
  var trainindIdArray = newString2.split(',');
  var lat = trainindIdArray[0];
  var Lng  = trainindIdArray[1];

  $("#latitude").val(lat);
  $("#longitude").val(Lng);
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script>
@endsection


