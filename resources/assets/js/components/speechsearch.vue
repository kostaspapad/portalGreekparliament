<template>
    <div class="container">
        <div class="col-12">
            <div class="input-group">
                <input v-model.trim="search_msg" @keypress.enter="search" type="text" class="form-control py-2 border-right-0 border" placeholder="Search" style="/*width:200px;*/" />
                <div class="input-group-append">
                    <span class="input-group-text bg-transparent"><i class="fa fa-search"></i></span>
                </div>
                <div style="margin: 2px 0 0 5px;">
                    <button @click="search" class="btn" style="background-color:#17a2b8;color:white;">Search</button>
                </div>
            </div>
        </div>

        <!--<div v-if="search_data" class="row w-100">
            if
        </div>
        <div v-else>
            else
        </div> -->
    </div>
</template>

<script>
    export default {
        // props: {
        //     path: String
        // },
        data(){
            return {
                ajaxData: {
                    search_data: []
                },
                search_msg: '',
                search_result_msg: null,
                showResults: false
            }
        },
        methods:{
            search(search_msg){
                console.log(search_msg);
            }            
        },
        computed:{
            // is_search_msg_empty(){
            //     //when search_msg is empty return true to show the initial "data"
            //     if(this.search_msg.length > 0){
            //         return false;
            //     }else{
            //         if(this.search_msg.length == 0){
            //             this.showResults = false;
            //             this.search_result_msg = null;
            //             return true;
            //         }
            //     }
            // },
            // party_search_result_msg(){
            //     if(this.search_result_msg == 'Search Results' || this.search_result_msg == null){
            //         return true;
            //     }else{
            //         console.log(this.search_msg.length)
            //         if(this.search_msg.length == 0){
            //             this.showResults = false;
            //             this.search_result_msg = '';
            //             return false;
            //         }
            //     }
            // }
        },
        created() {
            
        }
    }
</script>
