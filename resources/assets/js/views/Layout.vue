<template>
    <div>
        <navbar></navbar>
        <div id="main-content-height" :class="checkPage()">
            <router-view :key="$route.fullPath"></router-view>
        </div> 
        <!-- <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color menu-navbar d-none d-md-block">
            <div class="container ml-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/search">{{ $t("navbar.search")}}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/conferences">{{ $t("navbar.conferences")}}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/parties">{{ $t("navbar.political_parties")}}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/speakers">{{ $t("navbar.speakers")}}</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/about">{{ $t("navbar.about")}}</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav> -->
    
        <foot></foot>
    </div>
</template>
<style lang="scss" scoped>
    #main-content-height {
        &.landing-page {
            height: 590px!important;
        }
        // height: 600px;
        height: calc(93vh - 182px);
        overflow-y: auto;
        margin-top: 4px;
    }
</style>

<script>
    // import { mapMutations } from 'vuex'
    export default {
        data() {
            return {
                // authenticated: auth.check(),
                // user: auth.user
            };
        },
        methods:{
            // userLogout() {
            //     auth.logout()
            // },
            // ...mapMutations([
            //     'SAVE_USER',
            //     'CHECK_USER'
            // ])
            checkPage() {
                if(this.$route.path == '/'){
                    return 'landing-page'
                } else if(this.$route.path == '/search') {
                    return ''
                } else {
                    return 'container'
                }
            }
        },
        mounted() {
            // Event.$on('userLoggedIn', () => {
            //     this.authenticated = true
            //     this.user = auth.user
            //     if(this.user){
            //         this.SAVE_USER(this.user)
            //     }
            // })

            // Event.$on('userLoggedOut', () => {
            //     this.authenticated = false
            //     this.user = null
            //     this.CHECK_USER()
            //     this.$router.push('/')
            //     //  location.reload()
            // })
        },
    }
</script>