<template>
    <div>
        <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color">
            <div class="container-fluid ml-0">
                <div class="navbar-brand logo">
                    <router-link to="/">Greekparliament.info</router-link>
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
                            <!-- <a class="nav-link {{ Request::path() === 'conferences' ? 'active' : null }}" href="/conferences">{{ __('navbar.conferences') }}</a> -->
                            <router-link class="nav-link" to="/conferences">Conferences</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <!-- <a class="nav-link {{ Request::path() === 'parties' ? 'active' : null }}" href="/parties">{{ __('navbar.political_parties') }}</a> -->
                            <router-link class="nav-link" to="/parties">Parties</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <!-- <a class="nav-link {{ Request::path() === 'speakers' ? 'active' : null }}" href="/speakers">{{ __('navbar.speakers') }}</a> -->
                            <router-link class="nav-link" to="/speakers">Speakers</router-link>
                        </li>
                        <li class="nav-item d-block d-sm-block d-md-none">
                            <!-- <a class="nav-link {{ Request::path() === 'about' ? 'active' : null }}" href="/about">{{ __('navbar.about') }}</a> -->
                            <router-link class="nav-link" to="/about">About</router-link>
                        </li>
                        <hr class="divider">
                        
                        <!-- @guest -->
                        <li class="nav-item" v-if="!user">
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li class="nav-item" v-if="!user">
                            <router-link to="/register" class="nav-link">Register</router-link>
                        </li>

                        <li v-if="user" class="nav-item dropdown">
                            <router-link id="navbarDropdown" class="nav-link dropdown-toggle" to="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ user.name }} 
                                <span class="caret"></span>
                            </router-link>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link to="/dashboard" class="dropdown-item">Dashboard</router-link>
                                <span @click="userLogout" class="dropdown-item pointer">Logout</span>
                                <!-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form> -->
                            </div>
                        </li>
                        <!-- @endguest -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuButton" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (app()->getLocale() === 'en')
                                    <img src="/img/flags/flag_uk.png" alt="{{__('navbar.lang')}}">
                                @elseif (app()->getLocale() === 'el')
                                    <img src="/img/flags/flag_gr.png" alt="{{__('navbar.lang')}}">
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="/locale/en">{{ __('navbar.lang_en') }}</a>
                                <a class="dropdown-item" href="/locale/el">{{ __('navbar.lang_el') }}</a>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
            <!-- <div>
                <router-link to="/">Home</router-link>
                <router-link to="/about">About</router-link>
            </div>

            <div>
                <div v-if="authenticated && user">
                    <p>Hello, {{ user.name }}</p>

                    <router-link to="/logout">Logout</router-link>
                </div>

                <router-link to="/login" v-else>Login</router-link>
            </div> -->

        </nav>
        <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color menu-navbar d-none d-md-block">
            <div class="container ml-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <!-- <a class="nav-link {{ Request::path() === 'conferences' ? 'active' : null }}" href="/conferences">{{ __('navbar.conferences') }}</a> -->
                            <router-link class="nav-link" to="/conferences">Conferences</router-link>
                        </li>
                        <li class="nav-item">
                            <!-- <a class="nav-link {{ Request::path() === 'parties' ? 'active' : null }}" href="/parties">{{ __('navbar.political_parties') }}</a> -->
                            <router-link class="nav-link" to="/parties">Parties</router-link>
                        </li>
                        <li class="nav-item">
                            <!-- <a class="nav-link {{ Request::path() === 'speakers' ? 'active' : null }}" href="/speakers">{{ __('navbar.speakers') }}</a> -->
                            <router-link class="nav-link" to="/speakers">Speakers</router-link>
                        </li>
                        <li class="nav-item">
                            <!-- <a class="nav-link {{ Request::path() === 'about' ? 'active' : null }}" href="/about">{{ __('navbar.about') }}</a> -->
                            <router-link class="nav-link" to="/about">About</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div style="margin-top: 2rem">
            <router-view :key="$route.fullPath"></router-view>
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
                'SAVE_USER'
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
                this.authenticated = false;
                this.user = null;
            })
        },
    }
</script>