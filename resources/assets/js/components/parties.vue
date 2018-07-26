<template>
    <div class="container">
        <div v-if="ajaxData.partiesData.data" class="row">
            <div class="row w-100 mt-2">
                <div class="col-12">
                    <div class="input-group mb-3" style="width:275px;">
                        <input v-model.trim="search_msg" @keypress.enter="findParty" type="text" class="form-control py-2 border-right-0 border" placeholder="Search" style="/*width:200px;*/" />
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent"><i class="fa fa-search"></i></span>
                        </div>
                        <div style="margin: 2px 0 0 5px;">
                            <button @click="findParty" class="btn" style="background-color:#17a2b8;color:white;">Search</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100">
                <div class="col-12">
                    <p>{{search_result_msg}}</p>
                </div>
            </div>
            <div v-if="party_search_result_msg" class="row w-100">
                <div class="row w-100" v-if="!is_search_msg_empty && showResults" style="width:100%">
                    <!-- <div class="col-12 text-center"><p>{{search_result_msg}}</p></div> -->
                    <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageParty" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4"  v-for="party in ajaxData.search_data.data.data" :key="party.party_id" style="margin-bottom: 15px;">
                        <div class="card" style="height: 500px;">
                            <div v-if="party.image != '' " class="card-img-top-bg">
                                <img :src="path + '/' + printImg(party.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div v-else class="card-img-top">
                                <img :src="path + '/' + printImg(party.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div class="card-body">
                                <div class="names">
                                    <h5 class="card-title">{{party.fullname_el}}</h5>
                                    <span v-if="party.fullname_el != '' ">{{party.fullname_el}}</span>
                                    <span v-if="party.fullname_el != '' && party.fullname_en != '' ">/</span>
                                    <span v-if="party.fullname_en != '' ">{{party.fullname_en}}</span>
                                </div>
                                <div class="email addSpace">
                                    <p class="card-text"><a :href="selected_party.url">{{party.url}}</a></p>
                                </div>
                            </div>
                            <div class="links card-footer">
                                <div>
                                    <button @click="showModal(party)" class="btn btn-info btn-sm" style="margin-top:8px;">View more</button>
                                    <a :href="/party/ + party.party_id" class="btn btn-info btn-sm" style="margin-top:8px;">Show party</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <pagination :data="ajaxData.search_data.data.meta" @pagination-change-page="changePageParty" :limit=2>
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
                <div class="row w-100" v-else>
                    <div class="col-12">
                        <pagination :data="ajaxData.partiesData.data.meta" @pagination-change-page="changePage" :limit=2>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4"  v-for="party in ajaxData.partiesData.data.data" :key="party.party_id" style="margin-bottom: 15px;">
                        <div class="card" style="height: 500px;">
                            <!--<div v-if="party.image != '' " class="card-img-top-bg">
                                <img :src="path + '/' + printImg(party.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div v-else class="card-img-top">
                                <img :src="path + '/' + printImg(party.image) " class="img-fluid img-style card-img-top">
                            </div>-->
                            <div class="card-img-top">
                                <img :src="path + '/' + printImg(party.image) " class="img-fluid img-style card-img-top">
                            </div>
                            <div class="card-body">
                                <div class="names">
                                    <h5 class="card-title">Names </h5>
                                    <p class="card-text">
                                        <span v-if="party.fullname_el != '' ">{{party.fullname_el}}</span>
                                        <span v-if="party.fullname_el != '' && party.fullname_en != '' ">/</span>
                                        <span v-if="party.fullname_en != '' ">{{party.fullname_en}}</span>
                                    </p>
                                </div>
                                <div class="email addSpace" v-if="party.url != '' ">
                                    <h5 class="card-title"><a :href="party.url">{{party.url}}</a></h5>
                                </div>
                                
                                <div>
                                    
                                </div>
                            </div>
                            <div class="links card-footer">
                                <div>
                                    <button @click="showModal(party)" class="btn btn-info btn-sm" style="margin-top:8px;">View more</button>
                                    <a :href="/party/ + party.party_id" class="btn btn-info btn-sm" style="margin-top:8px;">Show party</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <pagination :data="ajaxData.partiesData.data.meta" @pagination-change-page="changePage" :limit=1>
                            <span slot="prev-nav">&lt;</span>
                            <span slot="next-nav">&gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
            <div v-else></div>
        </div>
        <div v-else>
            <img :src="path + '/Spinner.gif' "/>
        </div>
        <!--<modal title="Details" :party="selected_party" modalClasses="mine" :is-large="true" v-if="show_modal" @close="show_modal = false">
            <div class="modal-body">
                <div class="img-body"><img :src="path + '/' + selected_party.image" class="" style="width: 30%;"></div>
                <div class="details-content text-left">
                     <table class="table bordeless">
                        <thead>
                            <th>Greek name</th>
                            <th>English name</th>
                            <th>Email</th>
                            <th>Links</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table> 
                    <div>
                        <label>Greek name:</label>
                        <span v-if="selected_party.fullname_el != '' ">{{selected_party.fullname_el}}</span>
                        <span v-else>Not available</span>
                    </div>
                    <div>
                        <label>English name:</label>
                        <span v-if="selected_party.fullname_en != '' ">{{selected_party.fullname_en}}</span>
                        <span v-else>Not available</span>
                    </div>
                    <div>
                        <label>url:</label>
                        <span v-if="selected_party.url != '' "><a :href="selected_party.url">{{selected_party.fullname_en}}</a></span>
                        <span v-else>Not available</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" @click="show_modal = false">Close</button>
            </div>
        </modal>-->
    </div>
</template>

<script>
    export default {
        props: {
            //Parties: Object,
            path: String
        },
        data(){
            return {
                ajaxData: {
                    partiesData: [],
                    search_data: []
                },
                selected_party: null,
                show_modal: false,
                defaultImg: 'polical_party_default_image.png',
                search_msg: '',
                search_result_msg: null,
                showResults: false
            }
        },
        methods:{
            showModal(party){
                this.show_modal = true;
                this.selected_party = party;
            },
            findParty(){
                var self = this;
                axios.get(self.$parent.host+'/api/v1/parties/search/'+self.search_msg)
                .then(function(response){
                    
                    if(response.status == 200 && response.data.data.length > 0){
                        self.ajaxData.search_data = response;
                        self.search_result_msg = "Search Results";
                        self.showResults = true;
                    }else{
                        self.search_result_msg = 'No results found';
                        self.showResults = false;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                }); 
            },
            changePage(page){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/parties?page=' + page)
                .then(function(response){
                    self.ajaxData.partiesData = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePageParty(page){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/parties?page=' + page+'&fullname_el='+this.search_msg)
                .then(function(response){
                    self.ajaxData.search_data = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            isJsonString(str) {
                var json;
                try {
                    json = JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return json;
            },
            getParties(){
                var self = this;
                axios.get(this.$parent.host+'/api/v1/parties')
                .then(function(response){
                    self.ajaxData.partiesData = response;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            printImg(img){
                if(img == 'polical_party_default_image.png' || img == null || img == ''){
                    return this.defaultImg;
                }else{
                    return img;
                }
            },
            checkLinks(party){
                // if(party.wiki_el != '' || Party.wiki_en != '' || Party.website != '' || Party.twitter != ''){
                //     return true;
                // }else{
                //     return false;
                // }
                return true;
            }
        },
        computed:{
            is_search_msg_empty(){
                //when search_msg is empty return true to show the initial "data"
                if(this.search_msg.length > 0){
                    return false;
                }else{
                    if(this.search_msg.length == 0){
                        this.showResults = false;
                        this.search_result_msg = null;
                        return true;
                    }
                }
            },
            party_search_result_msg(){
                if(this.search_result_msg == 'Search Results' || this.search_result_msg == null){
                    return true;
                }else{
                    console.log(this.search_msg.length)
                    if(this.search_msg.length == 0){
                        this.showResults = false;
                        this.search_result_msg = '';
                        return false;
                    }
                }
            }
        },
        created() {
            this.getParties();
        }
    }
</script>
