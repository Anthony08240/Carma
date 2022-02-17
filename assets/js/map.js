import L from 'leaflet';
delete L.Icon.Default.prototype._getIconUrl;


var idleTime = 0;
$(document).on("DOMContentLoaded", function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 60000);

    //Zero the idle timer on mouse movement.
    $(this).on("mousemove", function (e) {
        idleTime = 0;
    });
    $(this).on("keypress", function (e) {
        idleTime = 0;
    });
    //Zero the idle timer on touch events.
    $(this).on('touchstart', function () {
        idleTime = 0;
    });
    $(this).on('touchmove', function () {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 1) {
        window.location.href = "../";
    }
}

var data = document.getElementById("map")

var points = JSON.parse(data.dataset.point)


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

var yellowIcon = L.icon({
    iconUrl: require('../images/marker-icon-yellow.png'),
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

    const categories = points.map(point => point.categorie).filter(function (ele, pos) {
        return points.map(point => point.categorie).indexOf(ele) == pos;
    })

    const filteredPoints = []

    categories.forEach(category => filteredPoints.push([]))

    points.map(function (entry) {

        var marker = L.marker([entry.point.latitude, entry.point.longitude])

        switch (entry.categorie) {
            case 'Centre sociaux':
                marker.setIcon(redIcon)
                break;
            case 'Hébergement':
                marker.setIcon(greenIcon)
                break;
            case 'Hygiène':
                marker.setIcon(bluelightIcon)
                break;
            case 'Matériel':
                marker.setIcon(greyIcon)
                break;
            case 'Alimentaire':
                marker.setIcon(yellowIcon)
                break;
            default:
                break;
        }

        //create popup contents
        var customPopup = `
        <div class="modalPoint">
            <div>
                <h1>${entry.id_user.etablissement}</h1>
            </div>
                <br/>
            <div>
            <img class="popup-img" src="../img_upload/${entry.img}" alt="">
            </div>
                <br/>
            <div>
                <p>${entry.description}</p>
            </div>
                <br/>
            <div>
                <p>${entry.id_user.tel}</p>
            </div>
            <div>
                <a href="https://www.google.fr/maps/dir//${entry.point.latitude}, ${entry.point.longitude}/">Itinéraire</a>
            </div>
        </div>
        `;

        //specify popup options 
        var customOptions = {
            'className': 'popupCustom',
        }

        function clickZoom(e) {
            map.setView(e.target.getLatLng(), map.getZoom());
        }

        marker.bindPopup(customPopup, customOptions).on('click', clickZoom);

        filteredPoints[categories.indexOf(entry.categorie)] = [
            ...filteredPoints[categories.indexOf(entry.categorie)],
            marker
        ]
    })

    const layers = {}

    categories.forEach((categorie, index) => {
        layers[categorie] = L.layerGroup(filteredPoints[index])
    })


    $('.js-point-color').on("click", function (e) {

        var category = $(this).attr('data-is-point');
        categories.forEach((categorie, index) => {
            layers[categorie].removeFrom(map);
        })

        if (category == "Tous") {
            categories.forEach((categorie, index) => {
                layers[categorie].addTo(map)
            })
        } else {
            if (layers[category]) {
                layers[category].addTo(map)
            }
        }

    })




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

        markerGeolocation.bindTooltip(`<b>Ma position</b>`, {
            direction: "top",
            offset: L.point({
                x: 8,
                y: -34
            })
        });
    }

    function handleLocationError(msg) {
        alert("Erreur lors de la géolocalisation");
    }

    const addPointsLinksWrapper = $('.boutton');

    if (addPointsLinksWrapper.length != 0) {
        addPointsLinksWrapper.on('click', '.js-add-point-links a', (e) => {
            if (coordinates == null) {
                return alert('Erreur lors de la géolocalisation vous ne pouvez pas jouter de point pour le moment, veuillez réessayer plus tard ou bien activer votre géolocalisation');
            }
            e.preventDefault()
            const href = e.target.getAttribute('href');
            const url = `${href}&latitude=${coordinates[0]}&longitude=${coordinates[1]}`;

            window.location.href = url;
        })
    }
}