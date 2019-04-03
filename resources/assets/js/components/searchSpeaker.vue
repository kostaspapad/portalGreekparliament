<template>
    <div class="multiselect-div" v-if="!taggable && !parties">
        <custom-multiselect 
        v-model="speaker_name" 
        placeholder="Αναζήτηση ομιλιτή" 
        open-direction="bottom" 
        :options="ajaxData.search_data"
        track-by="speaker_id"
        label="greek_name"
        :searchable="true" 
        :loading="isLoading" 
        :internal-search="false" 
        :clear-on-select="true" 
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
    <div class="multiselect-div" v-else-if="taggable && !parties" :class="taggable ? 'mt-4' : ''">
        <custom-multiselect 
            v-model="speaker_name" 
            placeholder="Αναζήτηση ομιλιτή" 
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
            :hide-selected="false"
            :preserveSearch="true"
            @search-change="asyncFind"
            tag-placeholder="Add this as new tag"
            :multiple="true"
            :taggable="true"
            @tag="addTag">
            <span slot="noResult">Δεν βρέθηκαν ομιλητές</span>
        </custom-multiselect>
    </div>
    <div class="multiselect-div" v-else-if="parties" :class="parties ? 'mt-4' : ''">
        <custom-multiselect 
            v-model="party_selected" 
            placeholder="Επιλογή κομμάτων" 
            open-direction="bottom" 
            :options="partiesData"
            track-by="party_id"
            label="fullname_el"
            :internal-search="true" 
            :clear-on-select="false" 
            :close-on-select="false" 
            :options-limit="100" 
            :limit="1" 
            :max-height="600" 
            :show-no-results="true" 
            :hide-selected="false"
            tag-placeholder="Add this as new tag"
            :multiple="true"
            :taggable="true">
            <span slot="noResult">Δεν βρέθηκαν κόμματα</span>
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
    @media only screen and (max-width: 767px) {
        .multiselect__content-wrapper{
            /* width: 350px!important; */
            position: relative!important;
            height: 350px!important;
        }
        .multiselect__option{
           /* white-space: normal!important; */
           word-break: break-word;
        }
    }
    @media only screen and (min-width: 768px) {
        .multiselect__option{
           white-space: normal!important;
        }
        .vs-sidebar--items {
            padding: 10px 10px;
            background: inherit;
            overflow-y: auto;
            list-style: none;
        }
    }
</style>


<script>
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            taggable: Boolean,
            parties: Boolean,
            partiesData: Array,
            // selectedParties: Array,
            clearInputs: Boolean
        },
        data(){
            return {
                ajaxData: {
                    search_data: []
                },
                // selectedCountries: [],
                // countries: [],
                isLoading: false,
                speaker_name: null,
                debounceTimer: null,
                party_selected: null,
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
                        axios.get(this.api_path + 'speakers/search/' + query)
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
                    path: '/speaker/' + selected.speaker_id 
                })
            },
            addTag(newTag) {
                //console.log(newTag)
            }
        },
        watch: {
            speaker_name: function(val){
                if (val) {
                    this.$emit('getTaggedSpeakers',[...val]);
                }
            },
            party_selected: function(val){
                if (val) {
                    this.$emit('getTaggedParties',[...val]);
                }
            },
            clearInputs: function(val){
               if (val) {
                   this.speaker_name = null
                   this.party_selected = null
               }
            }
            // ,selectedParties: function(val){
            //     console.log(val)
            //     this.party_selected = [...val]
            // }
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
