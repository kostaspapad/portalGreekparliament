<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding-bottom: 0;">
            <a class="navbar-brand"><i class="fa fa-bullhorn" style="font-size:25px;"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                        <!-- <router-link to="/">Go to home</router-link> -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/conferences">Conferences</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/political-parties">Political Parties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/speakers">Speakers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/speeches">Speeches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                        <!-- <router-link to="/about">Go to about</router-link> -->
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
