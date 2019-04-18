<template>
    <div class="container-imagebox">
        <div class="row m-0" style="">
            <div class="image-container col-12 col-sm-6 col-md-4" >
                <img src="/img/mainpage_logo.png" class="img-fluid">
                <div>
                    <h3 class="image-text">Αναζήτηση ομιλητή</h3>
                    <search-plugin></search-plugin>
                </div>
            </div>
            <div class="imagebox-text col-12 col-sm-6 col-md-8">
                <h3 class="imagebox-text-title">Μια προσπάθεια εξόρυξης και ταξινόμησης δεδομένων απο τα πρακτικά ομιλιών του Ελληνικού Κοινοβουλίου.</h3>
                <h3 class="imagebox-text-subtitle d-block d-sm-none text-center">Δοκιμαστική έκδοση</h3>
                <h3 class="imagebox-text-subtitle d-none d-sm-block">Δοκιμαστική έκδοση</h3>
                <div class="imagebox-text">
                    <p>Βρείτε πληροφορίες για βουλευτές, κομματικές παρατάξεις και συνεδριάσεις</p>
                    <p>Αναζητήστε φράσεις και λέξεις κλειδιά σε όλα τα πρακτικά</p>
                    <!-- <p>Δημιουργίστε λίστες με λέξεις στις οποίες θέλετε να ενημερώνεστε όταν αναφέρονται σε κάποια συνεδρίαση</p> -->
                    <!-- <ul>
                        <li>5.058 συνεδριάσεις (3 Ιουλίου 1989 μέχρι σήμερα).</li>
                        <li>1.184.079 ομιλίες.</li>
                        <li>1.786 ομιλητές.</li>
                        <li>26 κόμματα.</li>
                    </ul> -->
                </div>
            </div>
        </div>
        <!-- <div v-for="ajaxData.speakers">
            <timeline 
                :data="ajaxData.timelineData" 
                :colors="ajaxData.memberships.party_colors"
            ></timeline>
        </div> -->
    </div> 
</template>

<script>
import { mapState, mapGetters } from 'vuex'
export default {
    data() {
        return {
            ajaxData: {
                speakers: [
                    
                ]
            }
        }
    },
    
    // timelineData: []
    methods: {
        getTimelineMembershipData() {
            
            setTimeout(() => {
                api.call('get', this.api_path + 'speakers/timeline')
                .then( response => {
                    
                    if(response.status == 200 && response.data){
                        response.data.forEach( jsonEntry => {
                            console.log(jsonEntry[0])
                        
                        })
                    }
                })
            }, 500)
        }
    },
    computed: {
        ...mapGetters({
            api_path: 'get_api_path'
        })
    },
    created() {
        // this.getTimelineMembershipData()
    }
}
</script>