<template>
    <div >
        <div class="comments-area">
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem laudantium placeat nam iure repudiandae aperiam quae, laboriosam possimus, corporis tempore perspiciatis veritatis cumque illo neque quia magni, rem voluptates corrupti.</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem laudantium placeat nam iure repudiandae aperiam quae, laboriosam possimus, corporis tempore perspiciatis veritatis cumque illo neque quia magni, rem voluptates corrupti.</p>
        </div>
        <div class="comment-textarea">
            <TextareaAutogrow 
                v-model="comment"
                placeholder="Type your comment"
                classes="form-control form-control-line textarea"
            />
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
</style>

<script>
    export default {
        data() {
            return {
                comment: ''
            }
        }   
    }
</script>
