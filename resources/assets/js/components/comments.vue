<template>
    <div v-if="ajaxData.comments">
        <div v-chat-scroll class="comments-area" :class="{ 'comments-scroll' : ajaxData.comments.length > 6 }">
            <div v-for="comment in ajaxData.comments" :key="comment.comment_id" class="comment">
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
            <button @click="sendComment" :disabled="isDisabled" :class="{ 'not-allowed': isDisabled }" class="btn comment-send-button">
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
    .comment-send-button {
        // background-color: #CD5C5C;
        // background-color: salmon;
        background: #1ABC9C;
        color: white;
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
                            this.ajaxData.comments.push(data.data.data)
                        }
                        //this.getCommentsOfSpeech()
                    }
                })
            },
            getCommentsOfSpeech(){
                api.call('get', this.api_path + 'comments/' + this.speech_id)
                .then(  data => {
                    if( data.data && data.statusText == "OK" && data.status == 200 ){
                        this.ajaxData.comments = data.data.data
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
                api_path: 'get_api_path',
                speech_comments: 'get_conference_speech_comments'
            })
        },
        created(){
            //this.getCommentsOfSpeech()
            if(this.speech_comments){
                const comments_of_speech = this.speech_comments.filter(obj => 
                    obj.speech_id === this.speech_id
                );

                if(comments_of_speech.length){
                    this.ajaxData.comments = comments_of_speech
                }

            }

        },
        mounted(){
            
        }
    }
</script>
