// const APIUrl = "https://oplaadpalen.nl/api/maplist/location/04beae18-d5f6-11e9-a9fa-42010a840003";

const vm = new Vue({
    el: '#app',
    data: {
        // stub
        results: [
            {ocpi_evse_id: "NL*NUO*EEVB*P1531033*2", status: "AVAILABLE"},
            {ocpi_evse_id: "NL*NUO*EEVB*P1531033*1", status: "CHARGING"}
        ]
        // results: []
    },
    mounted() {
        // this.getStatus(status);

        // setInterval(function () {
        //   this.getStatus();
        //   console.log('Polling');
        // }.bind(this), 60000);
    },
    methods: {

        // getStatus() {
        //     axios.get(APIUrl).then((response) => {
        //         // Dive in the JSON
        //         let chargingSpots = response.data.data.chargingstation.chargingspots

        //         this.results = chargingSpots

        //     }).catch(error => { console.log(error); });

        // },

         isAvailable(chargingspot) {
            return chargingspot.status == 'AVAILABLE'
        }
    }
});
