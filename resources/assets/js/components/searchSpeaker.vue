<template>
    <div class="multiselect-div">
        <custom-multiselect 
        v-model="speaker_name" 
        placeholder="Αναζήτηση" 
        open-direction="bottom" 
        :options="ajaxData.search_data"
        track-by="speaker_id"
        label="greek_name"
        :searchable="true" 
        :loading="isLoading" 
        :internal-search="false" 
        :clear-on-select="false" 
        :close-on-select="false" 
        :options-limit="100" 
        :limit="1" 
        :max-height="600" 
        :show-no-results="true" 
        :hide-selected="true"
        :preserveSearch="true" 
        selectLabel=""
        @search-change="asyncFind"
        @select="goToProfile">
            <span slot="noResult">Δεν βρέθηκαν ομιλητές</span>
        </custom-multiselect>
    </div>
</template>

<style scoped>
    .multiselect-div{
        position: relative;
        top: -10px;
    }
</style>

<style>
    .multiselect__content-wrapper{
        /* width: 350px!important; */
    }
</style>


<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        data(){
            return {
                ajaxData: {
                    search_data: []
                },
                selectedCountries: [],
                countries: [],
                isLoading: false,
                speaker_name: null,
                debounceTimer: null
            }
        },
        methods:{
            asyncFind(query){
                if(query.length > 2){
                    const self = this;
                    self.isLoading = true
                    if(this.debounceTimer){
                        clearTimeout(this.debounceTimer)   // clearing debounceTimer
                    }
                    this.debounceTimer = setTimeout( () =>{
                        axios.get(this.api_path+'speakers/search/' + query)
                        .then(function(response){
                            if(response.status == 200 && response.data.data.length > 0){
                                self.isLoading = false
                                self.ajaxData.search_data = response.data.data;
                            }else{
                                self.isLoading = false
                            }
                        })
                    }, 500)
                }
            },
            goToProfile(selected){
                //window.location = this.$root.host + '/speaker/' + selected.greek_name
                this.$router.push({
                    path: '/speaker/' + selected.greek_name 
                })
            }
        },
        computed:{
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            
        }
    }
</script>
