import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        mapAreaZooms: [],
        paths: [],

        // todo: airport structure temporarily comes from this vuex store until it is retrieved from some database
        airport: {
            structure: {
                runways: {

                }
            }
        }
    },

    mutations: {
        // ToDo: I do not like that there appears to be no mapping helper to map mutators AND getters at the same time
        // with the same name, but apparently this is currently the case with Vuex right now
        setMap(state, map) {
            state.map = map;
        },
        setPaths(state, paths) {
            state.paths = paths;
        },
        registerMapAreaZoom(state, areaZoom) {
            state.mapAreaZooms.push(areaZoom);
        }
    },

    getters: {
        firstMapAreaZoom(context) {
            return context.mapAreaZooms[0];
        },
        airportStructure() {
            // let container = new JGFContainer();

            // TODO: strip everything that handles files and work great in the web with this! https://github.com/ArSn/jay-gee-eff

            // const airport = new JGFGraph('airport', 'EDDT', false);
            //
            // airport.addNode('rwy26r-east', 'RWY 26R East', { lat: 52.561878143251654, lng: 13.309024572372438 });
            // airport.addNode('rwy26r-west', 'RWY 26R West', { lat: 52.55779012076495, lng: 13.267173069373499 });
            //
            // airport.addEdge('rwy26r-east', 'rwy26r-west', 'is-runway', 'RWY 26R');
            //
            // console.log(airport.json);
        }
    }

});