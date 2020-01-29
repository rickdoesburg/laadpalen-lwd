const APIUrl = "https://oplaadpalen.nl/api/maplist/location/04beae18-d5f6-11e9-a9fa-42010a840003";

const vm = new Vue({
    el: '#app',
    data: {
        results: []
    },
    mounted() {
           this.getStatus();
       },
       methods: {

           getStatus() {
               axios.get(APIUrl).then((response) => {
                   // Dive in the JSON
                   let chargingSpots = response.data.data.chargingstation.chargingspots
                   
                   this.results = chargingSpots

               }).catch( error => { console.log(error); });
           },

           getClass(property){
             return {
               'is-available': this.results[property],
               'is-unavailable': !this.results[property]
             }
           }

    }
});