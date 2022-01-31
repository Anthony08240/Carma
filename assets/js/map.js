// import 'leaflet';
import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('../images/marker-icon-2x.png'),
    iconUrl: require('../images/marker-icon.png'),
    shadowUrl: require('../images/marker-shadow.png'),
});


const shadow = require('../images/marker-shadow.png');

// red marker

var redIcon = L.icon({
    iconUrl: require('../images/marker-icon-red.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

// green marker

var greenIcon = L.icon({
    iconUrl: require('../images/marker-icon-green.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

// green marker

var greyIcon = L.icon({
    iconUrl: require('../images/marker-icon-grey.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

// blue light marker

var bluelightIcon = L.icon({
    iconUrl: require('../images/marker-icon-blue-light.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

var yelowIcon = L.icon({
    iconUrl: require('../images/marker-icon-yelow.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

var geolocationIcon = L.icon({
    iconUrl: require('../images/marker-geolocation.png'),
    shadowUrl: shadow,

    iconAnchor: [5, 32],
    shadowAnchor: [4, 32],
    popupAnchor: [8, -25]
});

// initialisation de la map

/* Les options pour afficher la France */
const mapOptions = {
    center: [49.773510, 4.721191],
    zoom: 16
}
if ($('#map').length != 0) {

    var map = new L.map("map", mapOptions);
    var coordinates;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '<a target="_blank" href="https://www.geoportail.gouv.fr/">Geoportail France</a>',
        bounds: [
            [-75, -180],
            [81, 180]
        ],
        minZoom: 7,
        maxZoom: 19,
        format: 'image/png',
        style: 'normal'
    }).addTo(map);

    // marker blue

    var marker = L.marker([49.774776, 4.719757]).addTo(map);

    marker.bindPopup("<b>Place ducal</b><br>blue marker");

    // marker red

    var markerRed = L.marker([49.772039, 4.721973], {
        icon: redIcon
    }).addTo(map);

    markerRed.bindPopup("<b>Place ducal</b><br>red marker");

    // marker green

    var markerGreen = L.marker([49.773375, 4.721596], {
        icon: greenIcon
    }).addTo(map);

    markerGreen.bindPopup("<b>Place ducal</b><br>green marker");

    // marker green

    var markerGrey = L.marker([49.772898, 4.717535], {
        icon: greyIcon
    }).addTo(map);

    markerGrey.bindPopup("<b>Place ducal</b><br>grey marker");

    // marker green

    var markerBlueLight = L.marker([49.773225, 4.715222], {
        icon: bluelightIcon
    }).addTo(map);

    markerBlueLight.bindPopup("<b>Place ducal</b><br>blue light marker");

    // marker green

    var markerYelow = L.marker([49.774702, 4.723866], {
        icon: yelowIcon
    }).addTo(map);

    markerYelow.bindPopup("<b>Place ducal</b><br>yelow marker");



    /* Les options pour affiner la localisation */
    const locationOptions = {
        maximumAge: 10000,
        timeout: 5000,
        enableHighAccuracy: true
    };

    /* Verifie que le navigateur est compatible avec la géolocalisation */
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(handleLocation, handleLocationError, locationOptions);
    } else {
        /* Le navigateur n'est pas compatible */
        alert("Géolocalisation indisponible");
    }

    function handleLocation(position) {
        /* Zoom avant de trouver la localisation */
        map.setZoom(16);
        /* Centre la carte sur la latitude et la longitude de la localisation de l'utilisateur */
        map.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude));

        coordinates = [
            position.coords.latitude,
            position.coords.longitude
        ]

        var markerGeolocation = L.marker([position.coords.latitude, position.coords.longitude], {
            icon: geolocationIcon
        }).addTo(map);

        markerGeolocation.bindPopup("<b>Ma position</b>");

        console.log(position)
    }

    function handleLocationError(msg) {
        alert("Erreur lors de la géolocalisation");
    }

    const addPointsLinksWrapper = $('.boutton');
    if (addPointsLinksWrapper.length != 0) {
        console.log(addPointsLinksWrapper)
        addPointsLinksWrapper.on('click', '.js-add-point-links a', (e) => {
            if(coordinates == null){
                return alert('Erreur lors de la géolocalisation vous ne pouvez pas jouter de point pour le moment, veuillez réessayer plus tard ou bien activer votre géolocalisation');
            }
            e.preventDefault()
            const href = e.target.getAttribute('href');
            const url = `${href}&latitude=${coordinates[0]}&longitude=${coordinates[1]}`;
                
            window.location.href = url;
        })
    }
}

// function ajoutpoint(form) {

//     $.ajax({
//         type: "POST",
//         url: "/ajout-point",
//         data: form.serialize(),
//         data: {
//             latitude: "data"
//         },
//         success: function () {
//             console.log('Validé')
//         },
//     })

// }

// const newPointForm = $('#newPointForm');
// if (newPointForm.length != 0) {

//     console.log(newPointForm)
//     newPointForm.on('submit', (e) => {
//         console.log(e)
//         e.preventDefault();

//         const send = ajoutpoint(newPointForm);

//         console.log(send)
//     })
// }