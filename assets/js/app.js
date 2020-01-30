// const APIUrl = "https://oplaadpalen.nl/api/maplist/location/04beae18-d5f6-11e9-a9fa-42010a840003";
const APISmoovURL = "https://www.smoovapp.eu/api/feature/experienceaccelerator/areas/chargepointmap/getchargepoints/04beae18-d5f6-11e9-a9fa-42010a840003";

const vm = new Vue({
    el: '#app',
    data: {
        // stub
        // results: [
        //     {ocpi_evse_id: "NL*NUO*EEVB*P1531033*2", status: "AVAILABLE"},
        //     {ocpi_evse_id: "NL*NUO*EEVB*P1531033*1", status: "CHARGING"}
        // ]
        results: []
    },
    mounted() {
        this.getStatus(status);

        setInterval(function () {
          this.getStatus();
          console.log('Polling');
        }.bind(this), 60000);
    },
    methods: {

        getStatus() {
            axios.get(APISmoovURL).then((response) => {
                // Dive in the JSON
                // Save responseData to var
                let responseData = response.data

                // If the data contains evses (and thus comes from Smoov)
                if (responseData.evses) {

                    // Create empty array
                    var results = []

                    // For each ite (element) in evses, push data to array
                    responseData.evses.forEach(element => {
                        var elementName = element.id.split('|');

                        results.push({
                            ocpi_evse_id: elementName[0],
                            status: (element.status == "Occupied") ? "CHARGING" : "AVAILABLE"
                        })
                    })
                    this.results = results
                } else {
                    let chargingSpots = response.data.data.chargingstation.chargingspots
                    this.results = chargingSpots
                }
            }).catch(error => { console.log(error); });
        },

        // Set class based on availability
        getClass(chargingspot) {
            return (chargingspot.status === 'AVAILABLE')?'is-available':'is-unavailable'
        },

        // Check if spot is available
        isAvailable(chargingspot) {
            return chargingspot.status == 'AVAILABLE'
        }
    }
});
