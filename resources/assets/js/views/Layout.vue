<template>
    <div>
        <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color">
            <div class="container-fluid ml-0">
                <div class="navbar-brand logo">
                    <router-link to="/" class="logo-link">Greekparliament.info</router-link>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" 
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
        <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color menu-navbar d-none d-md-block">
            <div class="container ml-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
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
        </nav>
        <div class="footer-top-div">
            <router-view :key="$route.fullPath"></router-view>
            <footer class="footer page-footer font-small pt-4">
                <div class="container-footer text-center ">
                    <div class="col-md-12 mt-md-0 mt-3">
                        <h5 class="font-weight-bold p-3 m-0">Greekparliament.info</h5>
                    </div>
                    <!-- <hr class="clearfix w-100 d-md-none pb-3"> -->
                    <div class="col-12">
                        <a href="mailto:info@greekparliament.info" title="Email us" class="email-link">
                            <span>info@greekparliament.info</span> 
                        </a>
                    </div>
                    <div class="mb-md-0 mt-1 text-center">
                        <ul class="list-unstyled">
                            <li>
                                <router-link class="nav-link" to="/about">{{ $t("footer.about_us")}}</router-link>
                            </li>
                            <!-- <li>
                                <router-link class="nav-link" to="/news">{{ $t("footer.news")}}</router-link>
                            </li> -->
                            <li>
                                <router-link class="nav-link" to="/contact">{{ $t("footer.contact")}}</router-link>
                            </li>
                            <!-- <li>
                                <router-link class="nav-link" to="/donate">Donate</router-link>
                            </li> --> 
                            <!-- <li>
                                <router-link class="nav-link" to="/policy">{{ $t("footer.policy")}}</router-link>
                            </li> -->
                        </ul>
                        <a target="_blank" title="follow me on facebook" href="https://www.facebook.com/greekparliament">
                            <img alt="follow us on facebook" src="img/facebook30x30.png" border=0>
                        </a>
                    </div>
                </div>
                <!-- Copyright -->
                <!-- <div class="footer-copyright text-center py-3">© 2018 Copyright: -->
                <!-- </div> -->
                <!-- Copyright -->
            </footer>
        </div>
    </div>
</template>
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