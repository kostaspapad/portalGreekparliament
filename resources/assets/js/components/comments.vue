<template>
    <div v-if="speech_comments">
        <div v-chat-scroll class="comments-area" :class="{ 'comments-scroll' : speech_comments.length > 6 }">
            <div v-for="comment in speech_comments" :key="comment.comment_id" class="comment">
                <!-- <div v-if="">

                </div> -->
                <p class="m-0">{{comment.comment}}</p>
                <small>{{myFormattedDate(comment.created_at.date)}} - {{comment.user_name}}</small>
            </div>
        </div>
        <div class="comment-textarea">
            <TextareaAutogrow 
                v-model="comment"
                placeholder="Type your comment"
                classes="form-control form-control-line textarea"
            />
        </div>
        <div class="send-comment text-right mt-1 mr-1">
            <button @click="sendComment" :disabled="isDisabled" :class="{ 'not-allowed': isDisabled }" class="btn send-button">
                Send <i class="fas fa-paper-plane"></i> 
            </button>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .comment-textarea {
        margin-top: 0.4em;
    }
    .comment-textarea textarea {
        width: 100%;
        border-radius: 20px;
        resize: none;
    }
    .comments-scroll {
        height: 350px;
        overflow-y: scroll;
    }
    .comments-area {
        word-break: break-all;
    }
    .comment > p {
        font-size: 1.3rem;
    }
</style>

<script>
    import { mapState, mapGetters } from 'vuex'
    import moment from 'moment'
    export default {
        props: {
            speech_id:{
                type: String,
                required: true
            }
        },
        data() {
            return {
                ajaxData: {
                    comments: []
                },
                comment: '',
                comment_height: null
            }
        },
        methods: {
            sendComment(){
                //initialize data to send
                let data = {
                    speech_id: this.speech_id,
                    comment: this.comment
                };
                api.call('post',this.api_path + 'comments/create',data)
                .then( data => {
                    if(data.data.msg == "Comment Sent" && data.statusText == "Created" && data.status == 201){
                        this.clearVariables()
                        if(data.data){
                            this.speech_comments.push(data.data.data)
                        }
                    }
                })
            },
            myFormattedDate(date) {
                return moment(date).format('DD/MM/YYYY HH:mm');
            },
            clearVariables(){
                this.comment = ''
            }
        },
        computed: {
            isDisabled(){
                return this.comment ? false : true
            },
            ...mapGetters({
                api_path: 'get_api_path'
                // speech_comments: 'get_conference_speech_comments'
            }),
            speech_comments(){
                return this.$store.getters.get_conference_speech_comments.filter(obj => 
                    obj.speech_id === this.speech_id
                );
            }
        },
        created(){

        },
        mounted(){
            
        }
    }
</script>
