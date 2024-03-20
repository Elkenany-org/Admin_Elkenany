@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100%;
    height:250px;
	}
	#avatar:hover{
		width: 100%;
    height:250px;
		cursor: pointer;
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
<div class="row">
  <div class="col-sm-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h5 class="m-0" style="display: inline;">إضافة كورس جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
      </div>
      <div class="card-body">
        <form action="{{route('Storecourses')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        
        <!-- add course -->
          <div class="row">
              {{-- details --}}
              {{-- title --}}
              <div class="col-sm-2" style="margin-top: 10px">
              </div>
              <div class="col-sm-8" style="margin-top: 10px">
                <div class="from-group">
                  <label class="text-primary">عنوان الكورس: <span class="text-danger">*</span></label>
                  <input type="text" name="title" class="form-control" value="{{old('title')}}" placeholder="عنوان الكورس" required="">
                </div>
              </div>

             

              <div class="col-sm-2" style="margin-top: 10px">
              </div>
            
              <div class="col-sm-3" style="margin-top: 10px">
              </div>
              <div class="col-sm-6 marbo" style="margin-top: 10px">
                <label class="text-primary">إختيار صورة <span class="text-danger"> * </span></label><br>
                <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                <img src="{{asset('dist/img/placeholder2.png')}}" onclick="ChooseAvatar()" id="avatar">
              </div>
              <div class="col-sm-3" style="margin-top: 10px">
              </div>
              {{-- desc --}}
              <div class="col-sm-12">
                <label class="text-primary">التفاصيل <span class="text-danger">*</span></label>
                <textarea class="form-control" rows="7" name="desc" value="{{old('desc')}}" placeholder="التفاصيل" required></textarea>
              </div>	
          </div>
  
          <!-- tabs -->
          <div class="card card-primary card-outline" style="margin-top: 20px">
            <div class="card-body">
              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              
                {{-- live --}}
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-live-tab" data-toggle="pill" href="#custom-content-below-live" role="tab" aria-controls="custom-content-below-live" aria-selected="false"> اضافة لايف لكورس</a>
                </li>

                {{-- meeting --}}
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-meeting-tab" data-toggle="pill" href="#custom-content-below-meeting" role="tab" aria-controls="custom-content-below-meeting" aria-selected="false"> اضافة اوفلاين لكورس</a>
                </li>

                {{-- videos --}}
                <li class="nav-item">
                  <a class="nav-link" id="custom-content-below-videos-tab" data-toggle="pill" href="#custom-content-below-videos" role="tab" aria-controls="custom-content-below-videos" aria-selected="false"> اضافة كورس  اونلاين</a>
                </li>

              </ul>
            </div>
            <!-- online-offline-videos -->
            <div class="tab-content" id="custom-content-below-tabContent">

              <!-- online -->
              <div class="tab-pane fade" style="padding-bottom: 30px;" id="custom-content-below-live" role="tabpanel" aria-labelledby="custom-content-below-live-tab">
                <div class="container-fluid">
                  <div class="row">
                  <div class="col-sm-2" style="margin-top: 10px">
                    <div class="from-group">
                      <label class="text-primary"> سعر اللايف: <span class="text-danger">*</span></label>
                      <input type="number" name="price_live" class="form-control" value="{{old('price_live')}}" placeholder=" سعر اللايف" required="">
                    </div>
                  </div>
                  <div class="col-sm-2" style="margin-top: 10px">
                    <div class="from-group">
                      <label class="text-primary"> عدد ساعات اللايف: <span class="text-danger">*</span></label>
                      <input type="number" name="hourse_live" class="form-control" value="{{old('hourse_live')}}" placeholder=" عدد ساعات اللايف" required="">
                    </div>
                  </div>

                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> العنوان: <span class="text-primary">*</span></label>
                        <input type="text" name="title_live" class="form-control" value="{{old('title_live')}}" placeholder=" العنوان">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> التاريخ: <span class="text-primary">*</span></label>
                        <input type="date" name="date_live" class="form-control" value="{{old('date')}}" placeholder=" التاريخ">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> الوقت: <span class="text-primary">*</span></label>
                        <input type="time" name="time_live" class="form-control" value="{{old('time')}}" placeholder=" الوقت">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> الرابط: <span class="text-primary">*</span></label>
                        <input type="text" name="link_live" class="form-control" value="{{old('link')}}" placeholder=" الرابط">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> اسم التطبيق: <span class="text-primary">*</span></label>
                        <input type="text" name="application" class="form-control" value="{{old('application')}}" placeholder=" التطبيق">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
                        <input type="text" name="prof_live" class="form-control" value="{{old('prof_live')}}" placeholder=" المحاضر">
                      </div>
                    </div>
                    <div class="col-sm-4" style="margin-top: 10px">
                      <div class="from-group">
                        <label class="text-primary">  ساعات المحاضرة الواحدة: <span class="text-primary">*</span></label>
                        <input type="number" name="hourse_count_live" class="form-control" value="{{old('hourse_count')}}" placeholder=" عدد الساعات">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- meeting -->
              <div class="tab-pane fade" style="padding-bottom: 30px;" id="custom-content-below-meeting" role="tabpanel" aria-labelledby="custom-content-below-meeting-tab">
                <div class="container-fluid">
                  <input type="hidden"  value="" id="latitude" name="latitude">
                  <input type="hidden" value="" id="longitude"  name="longitude">
                  <div class="row">
                    <div class="col-sm-6 marbo" style="margin-top: 15px">
                      <label class="text-primary">العنوان:<span class="text-primary">*</span></label>
                      <input type="text" id="pac-input" class="form-control" placeholder="  " name="location">
                      <div class="validate-input" id="map" style="min-height: 300px;min-width: 250px;"></div>
                    </div>
                    <div class="col-sm-6" style="margin-top: 30px">
                      <div class="row">
                      <div class="col-sm-6" style="margin-top: 10px">
                        <div class="from-group">
                          <label class="text-primary"> سعر الميتنج: <span class="text-danger">*</span></label>
                          <input type="number" name="price_meeting" class="form-control" value="{{old('price_meeting')}}" placeholder=" سعر الميتنج" >
                        </div>
                      </div>
                      <div class="col-sm-6" style="margin-top: 10px">
                        <div class="from-group">
                          <label class="text-primary"> عدد ساعات الميتنج: <span class="text-danger">*</span></label>
                          <input type="number" name="hourse_meeting" class="form-control" value="{{old('hourse_meeting')}}" placeholder=" عدد ساعات الميتنج" >
                        </div>
                      </div>
                        <div class="col-sm-12" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> العنوان: <span class="text-primary">*</span></label>
                            <input type="text" name="title_Meeting" class="form-control" value="{{old('title_Meeting')}}" placeholder=" العنوان">
                          </div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> التاريخ: <span class="text-primary">*</span></label>
                            <input type="date" name="date_Meeting" class="form-control" value="{{old('date')}}" placeholder=" التاريخ">
                          </div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> الوقت: <span class="text-primary">*</span></label>
                            <input type="time" name="time_Meeting" class="form-control" value="{{old('time')}}" placeholder=" الوقت">
                          </div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
                            <input type="text" name="prof_Meeting" class="form-control" value="{{old('prof_Meeting')}}" placeholder=" المحاضر">
                          </div>
                        </div>
                        <div class="col-sm-12" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary">  ساعات المحاضرة الواحدة: <span class="text-primary">*</span></label>
                            <input type="number" name="hourse_count_Meeting" class="form-control" value="{{old('hourse_count')}}" placeholder=" عدد الساعات">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- offline -->
              <div class="tab-pane fade" style="padding-bottom: 30px;" id="custom-content-below-videos" role="tabpanel" aria-labelledby="custom-content-below-videos-tab">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12" style="margin-top: 10px">
                      <div class="card-body">
                        <div class="row">
                        <div class="col-sm-4" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> سعر  الاونلاين: <span class="text-danger">*</span></label>
                            <input type="number" name="price_offline" class="form-control" value="{{old('price_offline')}}" placeholder=" سعر  الاونلاين" >
                          </div>
                        </div>
      
                        <div class="col-sm-4" style="margin-top: 10px">
                          <div class="from-group">
                            <label class="text-primary"> عدد ساعات فديوهات الاونلاين: <span class="text-danger">*</span></label>
                            <input type="number" name="hourse_offline" class="form-control" value="{{old('hourse_offline')}}" placeholder=" عدد ساعات  الاونلاين" >
                          </div>
                        </div>
                          <div class="col-sm-8" style="margin-top: 10px">
                            <div class="from-group">
                              <label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
                              <input type="text" name="prof_offline" class="form-control" value="{{old('prof_offline')}}" placeholder=" المحاضر">
                            </div>
                          </div>
                       
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

          {{-- submit --}}
          <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px; " class="btn btn-outline-primary btn-block">حفظ</button>
        </form>
      </div>
    </div>
  </div>
  {{--warning--}}
  <div class="modal fade" id="modal-secondary">
    <div class="modal-dialog">
    <div class="modal-content bg-secondary">
      <div class="modal-body">
      <p>هذه الصفحة خاصة    باضافة كورس</p>
      </div>
    </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function ChooseAvatar1(){$("input[name='videos[]']").click()}
function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};


//////map


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
      center: {lat: 24.740691, lng: 46.6528521 },
      zoom: 13,
      mapTypeId: 'roadmap'
    });
  
    // move pin and current location
    infoWindow = new google.maps.InfoWindow;
    geocoder = new google.maps.Geocoder();
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
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



  //////


</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EGasync defer"></script>

@endsection


