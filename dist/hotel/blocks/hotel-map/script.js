import './style.css';
import $ from 'jquery';
// console.log("importing for now------>");
const loadGoogleMapsApi = require('load-google-maps-api')
// const apiKey='AIzaSyDNMvYXN2G-wWOl3VLz6PheOSXgCokeIM8';
const apiKey='AIzaSyCxetQrpiitHUOpmGYsHVeoHIw0SWZ-5DE'; // APIkey under nonfiction studios

loadGoogleMapsApi({key:apiKey,v:'weekly'}).then((googleMaps) => {
  if($("#hotel-map").length > 0) {

  
  // div for map container
  let map = document.getElementById("hotel-map");

  // build google map
  let lat = parseFloat(map.dataset.latitude || 51.456034205678705);
  let lng = parseFloat(map.dataset.longitude || -112.69851083930583);

  var customMap = new googleMaps.Map(map, {
    center: { 
      lat: parseFloat(map.dataset.latitude || 51.456034205678705), 
      lng: parseFloat(map.dataset.longitude || -112.69851083930583) 
    },
    zoom: 12,
    styles : [
      {
        "featureType": "administrative.locality",
        "elementType": "labels",
        "stylers": [
          {
            "color": "#000000"
          },
          {
            "saturation": 5
          },
          {
            "visibility": "simplified"
          }
        ]
      },
      {
        "featureType": "landscape.man_made",
        "stylers": [
          {
            "color": "#d2d4b4"
          },
          {
            "visibility": "on"
          }
        ]
      },
      {
        "featureType": "landscape.natural",
        "stylers": [
          {
            "color": "#dfe1cb"
          }
        ]
      },
      {
        "featureType": "landscape.natural.landcover",
        "stylers": [
          {
            "color": "#dfe1cb"
          }
        ]
      },
      {
        "featureType": "landscape.natural.terrain",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.attraction",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "simplified"
          }
        ]
      },
      {
        "featureType": "poi.attraction",
        "elementType": "labels.text",
        "stylers": [
          {
            "color": "#050505"
          }
        ]
      },
      {
        "featureType": "poi.business",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.government",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.medical",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.park",
        "stylers": [
          {
            "visibility": "on"
          }
        ]
      },
      {
        "featureType": "poi.place_of_worship",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.school",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "poi.sports_complex",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road",
        "stylers": [
          {
            "visibility": "on"
          }
        ]
      },
      {
        "featureType": "road.arterial",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road.highway",
        "stylers": [
          {
            "color": "#ffffff"
          }
        ]
      },
      {
        "featureType": "road.highway",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road.highway.controlled_access",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "road.local",
        "elementType": "labels",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "transit",
        "stylers": [
          {
            "visibility": "off"
          }
        ]
      },
      {
        "featureType": "transit.station.airport",
        "stylers": [
          {
            "visibility": "on"
          }
        ]
      },
      {
        "featureType": "water",
        "stylers": [
          {
            "color": "#afc6cc"
          }
        ]
      }
    ]
  })
  var latlng = new googleMaps.LatLng(lat, lng);
  var marker = new googleMaps.Marker({
    position: latlng,
    map: customMap
  });
  var icon = {
    url: '/assets/img/map-pin.png',
    scaledSize: new googleMaps.Size(32, 40)
  }
  marker.setIcon(icon);
  // console.log(marker);
  // Custommap.addMarker(marker)


}

}).catch((error) => {
  console.error(error)
})




