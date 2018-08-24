

<template>
    <div class="row w-100 mt-2">
        <div class="col-12">
            <div class="input-group mb-3 search-div">
                <input v-model.trim="search_msg" @keypress.enter="callFunc" type="text" class="form-control py-2 border-right-0 border" :placeholder="placeholder_text"/>
                <div class="input-group-append">
                    <button @click="callFunc" class="btn btn-success" type="button">{{btn_text}}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    @media (min-width: 769px) { 
        .search-div{
            width: 70%;
        }
    }
</style>

<script>
    export default {
        props: {
            btn_text: String,
            placeholder_text: String
        },
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
            callFunc(){
               if(this.btn_text == "find_speaker"){
                   this.find_speaker()
               }else if(this.btn_text == "find_conference"){
                    this.find_conference()
               }
            },
            find_speaker(){
                console.log("Hello speaker")
            },
            find_conference(){
                console.log("Hello conference")
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
