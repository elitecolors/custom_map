function createSearchableMap(locations = allLocations) {
  var bounds = new google.maps.LatLngBounds();
  var mapOptions = {mapTypeId: 'roadmap'};
  var markers = [];
  var infoWindowContent = [];
  var map = new google.maps.Map(document.getElementById('locations-near-you-map'), mapOptions);
  
  map.setTilt(45);

  locations.forEach(function(location) {
    markers.push([location.name, location.lat, location.lng]);

   divimg='';
    if(location.URLLogo){
      divimg+='<div class="coach_map_img"><img width="60" src="http://appli.newtritioncoach.com/' + location.URLLogo + 
                            '"/></div>';
    }

    divphone='';
    
    if(location.Tel1){
      divphone+=location.Tel1;
    }

    if(location.Tel2){
      divphone+=location.Tel2;
    }

    divdescription='';
    if(location.keyword){
      divdescription+=location.keyword;
    }
    
    div ='';
    div +='<div class="infoWindow">'+divimg+'<h3><a href="' + location.URLSite + '">' + location.Nom +'</a></h3><p>' + location.email + '<br />' + location.Ville + 
                            ', ' + location.Rue + ' ' + location.CodePostal + '</p><p>' + divdescription +'</p><p>Phone ' + 
                            divphone + '</p></div>';
    infoWindowContent.push([div]);
  });	    

  var infoWindow = new google.maps.InfoWindow(), marker, i;
  
  // Place the markers on the map
  for (i = 0; i < markers.length; i++) {
    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
    bounds.extend(position);
    marker = new google.maps.Marker({
      position: position,
      map: map,
      title: markers[i][0]
    });
    
    // Add an infoWindow to each marker, and create a closure so that the current
    // marker is always associated with the correct click event listener
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infoWindow.setContent(infoWindowContent[i][0]);
        infoWindow.open(map, marker);
      }
    })(marker, i));

    // Only use the bounds to zoom the map if there is more than 1 location shown
    if (locations.length > 1) {
      map.fitBounds(bounds);
    } else {
      var center = new google.maps.LatLng(locations[0].lat, locations[0].lng);
      map.setCenter(center);
      map.setZoom(15);
    }
  }
}

function formattAdress(){

}

function filterLocations() {
  var userLatLng;
  var geocoder = new google.maps.Geocoder();
  var userAddress = document.getElementById('userAddress').value.replace(/[^a-z0-9\s]/gi, '');
  console.log(userAddress);
  var maxRadius = parseInt(document.getElementById('maxRadius').value, 10);
  
  if (userAddress && maxRadius) {
    userLatLng = getLatLngViaHttpRequest(userAddress);
  } 

  function getLatLngViaHttpRequest(address) {
    // Set up a request to the Geocoding API
    // Supported address format is City, City + State, just a street address, or any combo
    var addressStripped = address.split(' ').join('+');
    var key = map_key;
    var request = 'https://maps.googleapis.com/maps/api/geocode/json?components=country:be|country:ie&address=' + addressStripped + '&key=' + key;
    
    // Call the Geocoding API using jQuery GET, passing in the request and a callback function 
    // which takes one argument "data" containing the response
    jQuery.get( request, function( data ) {
      var searchResultsAlert = document.getElementById('location-search-alert');

      // Abort if there is no response for the address data
      if (data.status === "ZERO_RESULTS") {
        searchResultsAlert.innerHTML = "Désolé, '" + address + "' semble être une adresse invalide.";
        return;
      }

      var userLatLng = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
      var filteredLocations = allLocations.filter(isWithinRadius);
      
      if (filteredLocations.length > 0) {
        createSearchableMap(filteredLocations);
        createListOfLocations(filteredLocations);
        //searchResultsAlert.innerHTML = 'Ok  ' + maxRadius + ' miles de ' + userAddress + ':';
        new_text_ok=text_ok_map.replace("_klm", maxRadius);
        new_text_ok=new_text_ok.replace("_adr", userAddress);
        searchResultsAlert.innerHTML=new_text_ok;
      } else {
        console.log("nothing found!");
        document.getElementById('locations-near-you').innerHTML = '';
        searchResultsAlert.innerHTML = "Désolé, aucun résultat n'a été trouvé dans "+ maxRadius + ' miles de ' + userAddress + '.';
      }

      function isWithinRadius(location) {
        var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
        var distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(locationLatLng, userLatLng);

        return convertMetersToMiles(distanceBetween) <= maxRadius;
      }
    });  
  }
}

function convertMetersToMiles(meters) {
  return (meters * 0.000621371);
}

function createListOfLocations(locations) {
  var locationsList = document.getElementById('locations-near-you');
  
  // Clear any existing locations from the previous search first
  locationsList.innerHTML = '';
  
  locations.forEach( function(location) {
    var specificLocation = document.createElement('div');
    var locationInfo = "<h4>" + location.Nom + "</h4><p>" + location.Pays + "</p>" +
                       "<p>"  + location.email + ", " + location.Ville + " " + location.CodePostal + "</p><p>" + location.Tel2 + "</p>";
    specificLocation.setAttribute("class", 'location-near-you-box');
    specificLocation.innerHTML = locationInfo;
    locationsList.appendChild(specificLocation);
  });
}



jQuery(document).ready(function($){

  $('#submitLocationSearch').on('click', function(e) {
    e.preventDefault();
    filterLocations();
  });

 	$('input.typeahead').typeahead({
	    source:  function (query, process) {
        return $.get($('#url_plugin').val()+"classes/search_ajax.php", { query: query }, function (data) {
        		data = $.parseJSON(data);
	            return process(data);
	        });
	    },
	    afterSelect: function (data) {
	    	$('#userAddress').val('');
	      if(data){
	      		var res = data.split("-");
	      		$('#userAddress').val(res[(res.length)-2]+''+res[(res.length)-1]);
	      }
    }
	});


	$( "input.typeahead" ).keyup(function() {
  		$('#userAddress').val($(this).val());		
	});

 
 
});     