<template>
	<div class="container mt-2" id="map"></div>
</template>

<script>
    import * as L from "leaflet";
    import "leaflet-draw";
    import {mapGetters, mapMutations, mapState} from "vuex";

    export default {
        data() {
            return {
                position: {},
	            debug: false, // todo: should probably go somewhere else, maybe vuex?
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
                    zoomControl: this.debug,
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
                map.addLayer(osm);

                if (this.debug) {
                    this.registerDebugHandlers();
                }
		    },
		    zoomToFirstArea() {
                this.position = this.firstMapAreaZoom.position;

                this.map.setView(this.latLng, this.position.zoom);
		    },
		    registerDebugHandlers()
		    {
		        // Drawing controls for easy line drawing on map
		        const map = this.map;
                var drawnItems = new L.FeatureGroup();
                map.addLayer(drawnItems);
                var drawControl = new L.Control.Draw({
                    edit: {
                        featureGroup: drawnItems
                    }
                });
                map.addControl(drawControl);

                map.on(L.Draw.Event.CREATED, function (event) {
                    var layer = event.layer;

                    console.log('line created: ', layer);

                    drawnItems.addLayer(layer);
                });

				// Various event outputs
                map.on('zoomstart', function() {
                    try {
                        console.log('zoomstart: ', map.getZoom());
                    } catch (e) {
                        console.log('error on zoomstart: ', e);
                    }
                });
                map.on('zoomend', function() {
                    console.log('zoomend: ', map.getZoom());
                });
                map.on('movestart', function() {
                    try {
                        console.log('movestart: ', map.getCenter());
                    } catch (e) {
                        console.log('error on movestart: ', e);
                    }
                });
                map.on('moveend', function() {
                    console.log('moveend: ', map.getCenter());
                });
                map.on('click', function(ev){
                    var latlng = map.mouseEventToLatLng(ev.originalEvent);
                    console.log('clicked!: ', latlng);

                });
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

                // draw line here for debug purpose
	            // todo: should go to reasonable place

	            // RWY 26R EDDT
                var pointA = new L.LatLng(52.561878143251654, 13.309024572372438);
                var pointB = new L.LatLng(52.55779012076495, 13.267173069373499);
                var pointList = [pointA, pointB];

                var firstpolyline = new L.Polyline(pointList, {
                    color: 'red',
                    weight: 3,
                    opacity: 0.5,
                    smoothFactor: 1
                });
                firstpolyline.addTo(this.map);
            });
        }
    }
</script>

<style>
	#map {
		height: 500px;
	}
</style>