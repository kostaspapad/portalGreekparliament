<template>
    <!-- <div> -->
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel navbar-bg-color pl-0">
            <div class="container-fluid">
                <div class="navbar-brand logo">
                    <router-link to="/" class="logo-link">Greekparliament.info</router-link>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" 
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="container ml-0 nav-container">
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
                <div class="collapse navbar-collapse d-none" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                         <!-- Authentication Links -->
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <router-link class="nav-link" to="/conferences">{{ $t("navbar.conferences")}}</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <router-link class="nav-link" to="/parties">{{ $t("navbar.political_parties")}}</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <router-link class="nav-link" to="/speakers">{{ $t("navbar.speakers")}}</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <router-link class="nav-link" to="/about">{{ $t("navbar.about")}}</router-link>
                        </li>
                        <hr class="divider">
                        
                        <!-- @guest -->
                        <li class="nav-item" v-if="!user">
                            <router-link to="/login" class="nav-link">{{$t("auth.login")}}</router-link>
                        </li>
                        <li class="nav-item" v-if="!user">
                            <router-link to="/register" class="nav-link">{{$t("auth.register")}}</router-link>
                        </li>
                        <li v-if="user" class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ user.name }} 
                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link to="/dashboard" class="dropdown-item myHoverBg">Dashboard</router-link>
                                <span @click="userLogout" class="dropdown-item pointer myHoverBg">{{$t("auth.logout")}}</span>
                                <!-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form> -->
                            </div>
                        </li>
                        <!-- @endguest -->
                        <!-- LOCALE -->
                        <!-- <li class="nav-item dropdown mb-2">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img v-if="$i18n.locale == 'en' " src="/img/flags/flag_uk.png">
                                    <img v-if="$i18n.locale == 'el'" src="/img/flags/flag_gr.png">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <span class="dropdown-item pointer myHoverBg"  @click="$i18n.locale = 'el' ">{{$t("navbar.lang_el")}}</span>
                                <span class="dropdown-item pointer myHoverBg"  @click="$i18n.locale = 'en' ">{{$t("navbar.lang_en")}}</span>
                            </div>
                        </li> -->
                        <!-- END OF LOCALE -->
                    </ul>
                </div>
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
