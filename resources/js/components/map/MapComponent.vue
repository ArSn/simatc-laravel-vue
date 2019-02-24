<template>
	<div class="container mt-2" id="map"></div>
</template>

<script>
    import * as L from "leaflet";
    import {mapGetters, mapMutations, mapState} from "vuex";

    export default {
        data() {
            return {
                position: {},
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

                // map.setView(this.latLng, this.position.zoom);
                map.addLayer(osm);

                map.on('zoomend', function() {
                    console.log(map.getZoom());
                });
                map.on('moveend', function() {
                    console.log(map.getCenter());
                });
		    },
		    zoomToFirstArea() {
                this.position = this.firstMapAreaZoom.position;

                this.map.setView(this.latLng, this.position.zoom);
		    }
	    },

	    computed: {
            ...mapState(['map']),
		    ...mapGetters(['firstMapAreaZoom']),
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
            this.$nextTick(function () {
                this.zoomToFirstArea();
            });
        }
    }
</script>

<style>
	#map {
		height: 500px;
	}
</style>