<template>
    <tbody >
        <tr>
            <td>
                <div>
                    <span @click="expand = !expand" v-if="!expand" class="pointer">
                        <i class="material-icons"> keyboard_arrow_right</i>
                    </span>
                    <span @click="expand = !expand" v-else class="pointer">
                        <i class="material-icons"> keyboard_arrow_down</i>
                    </span>
                    <router-link 
                        :to="/conference/+infoData.conference_date+'/speeches'" 
                        class="link_to_speech position-relative"
                        style="top: -7px;"
                    >
                        {{infoData.conference_date}}
                    </router-link>
                </div>
            </td>
            <td>
                <router-link :to="/speech/+infoData.speech_id" class="link_to_speech">
                    {{infoData.speech_id}}
                </router-link>
            </td>
            <td>{{infoData.total_comments}}</td>
            <td>
                <span v-if="infoData.isFavorite"><i class="fa fa-star fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
                <span v-else><i class="fa fa-star-o fa-2x" aria-hidden="true" style="color:#fff645"></i></span>
            </td>
        </tr>
        <tr class="expand-data">
            <td class="border-0 p-0" colspan="6">
                <transition-expand>
                    <div v-if="expand" class="expand-content">
                        <!-- <h4>Hello</h4> -->
                    </div>
                </transition-expand>
            </td>
        </tr>
    </tbody>
</template>

<style lang="scss" scoped>
    .link_to_speech {
        color: white;
    }
    .link_to_speech:hover {
        color: salmon;
    }
    
    tr.expand-data:hover {
        background-color: transparent;
    }
    
</style>


<script>
    export default {
        props: {
            infoData: {
                type: Array | Object,
                required: true
            }
        },
        data() {
            return {
                expand: false
            }
        },
        mounted() {
            
        }
    }
</script>