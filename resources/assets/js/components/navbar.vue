<template>
    <!-- <div> -->
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel navbar-bg-color pl-0">
            <div class="container-fluid">
                    <router-link to="/" class="logo-link navbar-brand logo">Greekparliament.info</router-link>
                <!-- <div class="navbar-brand logo">
                </div> -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" 
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- <div class="container ml-0 nav-container"> -->
                    <div class="collapse navbar-collapse my-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item">
                                <router-link class="nav-link" to="/search" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.search")}}</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/conferences" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.conferences")}}</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/parties" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.political_parties")}}</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/speakers" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.speakers")}}</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/about" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.about")}}</router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" to="/contact" data-toggle="collapse" data-target=".navbar-collapse.show">{{ $t("navbar.contact")}}</router-link>
                            </li>
                        </ul>
                        <ul class="navbar-nav ml-auto">
                            <!-- LOCALE -->
                            <li class="nav-item dropdown mb-2">
                                <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img v-if="$i18n.locale == 'en' " src="/img/flags/flag_uk.png">
                                        <img v-if="$i18n.locale == 'el'" src="/img/flags/flag_gr.png">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <span class="dropdown-item pointer myHoverBg"  @click="$i18n.locale = 'el' ">{{$t("navbar.lang_el")}}</span>
                                    <span class="dropdown-item pointer myHoverBg"  @click="$i18n.locale = 'en' ">{{$t("navbar.lang_en")}}</span>
                                </div>
                            </li>
                            <!-- END OF LOCALE -->
                            <li class="nav-item" v-if="!user">
                                <router-link to="/login" class="nav-link" data-toggle="collapse" data-target=".navbar-collapse.show">{{$t("auth.login")}}</router-link>
                            </li>
                            <li class="nav-item" v-if="!user">
                                <router-link to="/register" class="nav-link" data-toggle="collapse" data-target=".navbar-collapse.show">{{$t("auth.register")}}</router-link>
                            </li>
                            <li v-if="user" class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ user.name }} 
                                    <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right border-0" aria-labelledby="navbarDropdown">
                                    <!-- <router-link to="/dashboard" class="dropdown-item myHoverBg">Dashboard</router-link> -->
                                    <span @click="userLogout" class="dropdown-item pointer" data-toggle="collapse" data-target=".navbar-collapse.show">{{$t("auth.logout")}}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                <!-- </div> -->
            </div>
        </nav>
    <!-- </div> -->
</template>
<style lang="scss" scoped>
    .nav-item {
        // font-size: 1.2em;
        // border-bottom: 1px solid rgba(0,0,0,.2);
    }
    .nav-container {
        background: white;
        z-index: 9999999999;
        position: relative;
        top: -1px;
    }
    .my-collapse {
        padding-left: 1.5em;
        // &.show {
        //     padding-left: 1.5em;
        // }
    }
</style>

<script>
    import { mapMutations } from 'vuex'
    export default {
        data() {
            return {
                authenticated: auth.check(),
                user: auth.user
            };
        },
        methods:{
            userLogout() {
                auth.logout()
            },
            ...mapMutations([
                'SAVE_USER',
                'CHECK_USER'
            ])
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true
                this.user = auth.user
                if(this.user){
                    this.SAVE_USER(this.user)
                }
            })

            Event.$on('userLoggedOut', () => {
                this.authenticated = false
                this.user = null
                this.CHECK_USER()
                this.$router.push('/')
                //  location.reload()
            })
        },
    }
</script>
