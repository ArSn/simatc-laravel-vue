<template>
	<div class="container mt-2" id="map"></div>
</template>

<script>
    import * as L from "leaflet";
    import {mapMutations, mapState} from "vuex";

    export default {
        data() {
            return {
                position: {
                    lat: 52.55835117305416,
	                lng: 13.289337158203127,
                    zoom: 14,
                },
            };
        },

	    methods: {
            ...mapMutations(['setMap']),
		    flyTo() {
                this.map.flyTo(this.latLng, this.position.zoom);
		    },
		    initializeMap()
		    {
                const map = new L.Map('map', {
                    zoomSnap: 0.1,
                    zoomDelta: 0.1,
                    zoomControl: false
                });
                this.setMap(map);

                // create the tile layer with correct attribution
                const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                const osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
                const osm = new L.TileLayer(osmUrl, {
                    minZoom: 8,
                    maxZoom: 20,
                    attribution: osmAttrib
                });

                map.setView(this.latLng, this.position.zoom);
                map.addLayer(osm);

                map.on('zoomend', function() {
                    console.log(map.getZoom());
                });
                map.on('moveend', function() {
                    console.log(map.getCenter());
                });
		    },
		    zoomToFirstArea() {
			    // todo: zoom to position of first button here instead of having a duplicate default in this component
		    }
	    },

	    computed: {
            ...mapState(['map']),
            latLng() {
                return new L.LatLng(this.position.lat, this.position.lng);
            },
	    },

        watch: {
            position: {
                handler() { this.flyTo(); },
                deep: true,
            },
        },

        mounted() {
            this.initializeMap();
            this.zoomToFirstArea();
        }
    }
</script>

<style>
	#map {
		height: 500px;
	}
</style>