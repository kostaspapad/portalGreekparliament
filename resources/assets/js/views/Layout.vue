<template>
    <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color">
        <div class="container-fluid ml-0">
            <div class="navbar-brand logo" <router-link to="/"></router-link>>
                Greekparliament.info
            </div>
        </div>
        <div>
            <router-link to="/">Home</router-link>
            <router-link to="/about">About</router-link>
        </div>

        <div>
            <div v-if="authenticated && user">
                <p>Hello, {{ user.name }}</p>

                <router-link to="/logout">Logout</router-link>
            </div>

            <router-link to="/login" v-else>Login</router-link>
        </div>

        <div style="margin-top: 2rem">
            <router-view></router-view>
        </div>
    </nav>
</template>
<script>
export default {
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user
        };
    },

    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });
    },
}
</script>