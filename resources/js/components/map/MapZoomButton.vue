<template>
	<button :lat="lat"
	        :lng="lng"
	        :zoom="zoom"
	        @click="flyTo"
			class="btn btn-primary">
		<slot></slot>
	</button>
</template>

<script>
    import * as L from "leaflet";
    import {mapState} from "vuex";

    export default {
        props: [
            'lat',
            'lng',
            'zoom',
        ],

        methods: {
            flyTo() {
                this.$store.state.map.flyTo(this.latLng, this.zoom);
            }
        },

        computed: {
            ...mapState(['map']),
            latLng() {
                return new L.LatLng(this.lat, this.lng);
            },
	        position() {
                return {
                    lat: this.lat,
                    lng: this.lng,
                    zoom: this.zoom,
                };
	        }
        },

	    mounted() {
            this.$store.commit('registerMapAreaZoom', this);
	    }
    }
</script>
