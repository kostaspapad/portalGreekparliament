<template>
    <!-- <div class="footer-top-div"> -->
        <footer class="footer page-footer font-small">
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
                    <a target="_blank" title="follow me on facebook" href="https://www.facebook.com/greekparliament" style="font-size: 2rem;">
                        <i class="fab fa-facebook-square"></i>
                    </a>
                </div>
            </div>
            <!-- Copyright -->
            <!-- <div class="footer-copyright text-center py-3">© 2018 Copyright: -->
            <!-- </div> -->
            <!-- Copyright -->
        </footer>
    <!-- </div> -->
</template>