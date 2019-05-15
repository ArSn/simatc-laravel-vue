<template>
	<div class="container mt-2" id="map"></div>
</template>

<script>
    import * as L from "leaflet";
    import "leaflet-draw";
    import "leaflet-geometryutil";
    import {mapGetters, mapMutations, mapState} from "vuex";
    import {JgfNode, JgfEdge, JgfGraph} from "jay-gee-eff-for-web";

    export default {
        data() {
            return {
                position: {},
	            debug: true, // todo: should probably go somewhere else, maybe vuex?
            };
        },

	    methods: {
            ...mapMutations(['setMap', 'setPaths']),
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

                map.on('click', this.handleClick);
		    },
		    handleClick(event) {
                const map = this.map;
                let latlng = map.mouseEventToLatLng(event.originalEvent);
                // todo: dead code?
                // let marker = L.marker(e.latlng).addTo(map).bindPopup(e.latlng + '<br/>' + e.layerPoint).openPopup();

                let closestPointToPolygon;

                _.forEach(this.paths, (path) => {
                    closestPointToPolygon = L.GeometryUtil.closest(map, path, event.latlng);
                    L.marker(closestPointToPolygon).addTo(map).bindPopup('Closest point on polygon1');
                });
		    },
            /**
             * @param {JgfGraph} graph
             */
		    drawPaths(graph) {
                _.forEach(graph.edges, (edge) => {
                    const firstNode = graph.getNodeById(edge.source);
                    const secondNode = graph.getNodeById(edge.target);

                    const line = new L.Polyline([
                        new L.LatLng(firstNode.metadata.lat, firstNode.metadata.lng),
                        new L.LatLng(secondNode.metadata.lat, secondNode.metadata.lng)
                    ], {
                        color: this.determinePathColor(edge),
                        weight: 3,
                        opacity: 0.5,
                        smoothFactor: 1
                    });

                    this.paths.push(line);

                    line.addTo(this.map);
                });
		    },
            /**
             * @param {JgfEdge} edge
             * @return {string}
             */
            determinePathColor(edge) {
		        switch (edge.relation) {
			        case 'is-runway': {
			            return 'red';
			        }
                    case 'is-taxiway': {
                        return 'yellow';
                    }
                }
		        return 'black';
            }
	    },

	    computed: {
            ...mapState(['map', 'paths']),
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

                const graph = new JgfGraph();
                // todo: which IDs to use?
                graph.addNode(new JgfNode('left', 'Left Point', { lat: 52.561878143251654, lng: 13.309024572372438 }));
                graph.addNode(new JgfNode('right', 'Right Point', { lat: 52.55779012076495, lng: 13.267173069373499 }));
                graph.addEdge(new JgfEdge('left', 'right', 'is-runway', 'RWY 08L/26R'));

                graph.addNode(new JgfNode('top', 'Top Point', { lat: 52.55916, lng: 13.28113 }));
                graph.addNode(new JgfNode('bottom', 'Bottom Point', { lat: 52.555913156301095, lng: 13.281908543057986 }));
                graph.addEdge(new JgfEdge('top', 'bottom', 'is-taxiway', 'Taxiways TM and TS'));

                this.drawPaths(graph);
                this.zoomToFirstArea();

	            // const structure = this.$store.airportStructure;

            });
        }
    }
</script>

<style>
	#map {
		height: 500px;
	}
</style>