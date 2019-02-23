<template>
	<div class="container" id="map"></div>
</template>

<script>
    import * as L from "leaflet";

    export default {
        mounted() {
            const map = new L.Map('map');

            // create the tile layer with correct attribution
            const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
            const osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
            const osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 20, zoomSnap: 0.1, attribution: osmAttrib});

            // start the map in South-East England
            map.setView(new L.LatLng(52.55835117305416, 13.289337158203127), 14);
            map.addLayer(osm);

            map.zoomIn(0.2);

            map.on('zoomend', function() {
                console.log(map.getZoom());
            });
            map.on('moveend', function() {
                console.log(map.getCenter());
            });
        }
    }
</script>
