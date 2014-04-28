$( document ).ready(function() {
	casualtiesRoller();
	showHelp();
});

function addDatePicker(){
	$('#add_form input#date').datepicker();
}

function showHelp(){
	$('#showhelp').on('mouseover',function(){
		$('#help').show();
	});
	$('#showhelp').on('mouseout',function(){
		$('#help').hide();
	});
	$('#showhelp').on('vmouseover',function(){
		$('#help').show();
	});
	$('#showhelp').on('vmouseout',function(){
		$('#help').hide();
	});
	
}

function casualtiesRoller(){
	$('.casualties').each(function(){
		var v = $(this).text();
		$(this).countTo({
		    from: 0,
		    to: v,
		    speed: 10000,
		    refreshInterval: 10,
		    onComplete: function(value) {
		       // console.debug(this);
		    }
		});
	});

}

function formatNumber(number)
{
    number = number.toFixed(2) + '';
    x = number.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function addCommas(nStr)
{
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}

function addMarker(lat, lng, info) {
 	var pt = new google.maps.LatLng(lat, lng);
 	bounds.extend(pt);
 	var marker = new google.maps.Marker({
 					position: pt,
 					icon: icon,
 					map: map
 				});
 	var popup = new google.maps.InfoWindow({
 					content: info,
 					maxWidth: 300
 				}); 
 
 	google.maps.event.addListener(marker, "click", function() {
 		if (currentPopup != null) {
 			currentPopup.close();
 			currentPopup = null;
 		}
 	popup.open(map, marker);
 	currentPopup = popup;
 	});
 	
 	google.maps.event.addListener(popup, "closeclick", function() {
 		//map.panTo(center);
 		currentPopup = null;
 	});
 }

function setupEvents() {
    reverseGeocodedLast = new Date();
    centerChangedLast = new Date();

    setInterval(function() {
      if((new Date()).getSeconds() - centerChangedLast.getSeconds() > 1) {
        if(reverseGeocodedLast.getTime() < centerChangedLast.getTime())
          reverseGeocode();
      }
    }, 1000);
    

    google.maps.event.addListener(map, 'center_changed', centerChanged);

    google.maps.event.addDomListener(document.getElementById('crosshair'),'click', function() {
       map.setZoom(map.getZoom() + 1);
    });
}

function getCenterLatLngText() {
    return '(' + map.getCenter().lat() +', '+ map.getCenter().lng() +')';
}

function centerChanged() {
    centerChangedLast = new Date();
    var latlng = getCenterLatLngText();
    document.getElementById('latlng').innerHTML = latlng;
    document.getElementById('formatedAddress').innerHTML = '';
    currentReverseGeocodeResponse = null;
}

function reverseGeocode() {
    reverseGeocodedLast = new Date();
    geocoder.geocode({latLng:map.getCenter()},reverseGeocodeResult);
}

function reverseGeocodeResult(results, status) {
    currentReverseGeocodeResponse = results;
    if(status == 'OK') {
      if(results.length == 0) {
        document.getElementById('formatedAddress').innerHTML = 'None';
      } else {
        document.getElementById('formatedAddress').innerHTML = results[0].formatted_address;
      }
    } else {
      document.getElementById('formatedAddress').innerHTML = 'Error';
    }
}

function geocode() {
    var address = document.getElementById("address").value;
    geocoder.geocode({
      'address': address,
      'partialmatch': true}, geocodeResult);
}

function geocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
      map.fitBounds(results[0].geometry.viewport);
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
}

function addMarkerAtCenter() {
    var marker = new google.maps.Marker({
        position: map.getCenter(),
        map: map
    });

    var text = 'Lat/Lng: ' + getCenterLatLngText();
    if(currentReverseGeocodeResponse) {
      var addr = '';
      if(currentReverseGeocodeResponse.size == 0) {
        addr = 'None';
      } else {
        addr = currentReverseGeocodeResponse[0].formatted_address;
      }
      text = text + '<br>' + 'address: <br>' + addr;
    }	
}

function saveData() {
    var date = escape(document.getElementById("date").value);
    var loc = escape(document.getElementById("location").value);
    var latlng = marker.getPosition();
    var txts = escape(document.getElementById("text_short").value);
    var ct0 = escape(document.getElementById("count_0").value);
    var ct1 = escape(document.getElementById("count_1").value);
    var ct2 = escape(document.getElementById("count_2").value);
    var ct3 = escape(document.getElementById("count_3").value);
    var ct4 = escape(document.getElementById("count_4").value);
    var lnk = escape(document.getElementById("link").value);
    
    var url = "_index.php?date=" + date + "&location=" + loc +
               "&lat=" + latlng.lat() + "&lng=" + latlng.lng() + "&text_short="
                + txts + "&count_0=" + ct0 + "&count_1=" + ct1
                + "&count_2=" + ct2 + "&count_3=" + ct3 + "&count_4=" + ct4 + "&link=" + lnk;
    downloadUrl(url, function(data, responseCode) {
      if (responseCode == 200) {
        infowindow.close();
        document.getElementById("submitmessage").innerHTML = "Thank you for adding a new Location. Please <a href='http://www.derkreuzzug.com/' target='_self'>reload now</a>";
        $('<div id="overlay"></div>').appendTo('body');
      }
    });
}


function downloadUrl(url, callback) {
    var request = window.ActiveXObject ?
        new ActiveXObject('Microsoft.XMLHTTP') :
        new XMLHttpRequest;

    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        //request.onreadystatechange = doNothing;
        callback(request.responseText, request.status);
      }
    };
    request.open('GET', url, true);
    request.send(null);
}

(function($) {
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 100,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };
})(jQuery);