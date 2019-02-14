<template>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <!-- <div class="sidebar-header">
                 <input v-model="keywords" type="fulltext" class="form-control" id="fulltext-searchbox" placeholder="Search">
            </div> -->
            <ul class="list-unstyled components">
                <input-tag placeholder="tags" v-model="tags"></input-tag>
                <search-plugin></search-plugin>
                
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">{{$t("conferences.datepicker.date")}}</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <div class="datepicker pr-2 pl-2">
                                <!-- <span @click="showInfoDiv = !showInfoDiv" v-show="!showInfoDiv" class="datepkr-float">
                                    <i class="fa fa-info-circle info-icon"></i>
                                </span> -->
                                <transition name="slide-fade">
                                    <div v-if="showInfoDiv" class="alert alert-info" role="alert">
                                        <button @click="showInfoDiv = !showInfoDiv" type="button" class="close" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="alert-heading">{{ $t("conferences.search") }}</h4>
                                        <p>{{ $t("conferences.datepicker.select_date_ranges") }}</p>
                                        <p>Η Επαναφορά καθαρίζει το πεδίο στο οποίο βάλατε την ημερομηνία.</p>
                                    </div>
                                </transition>
                                <div class="datepkr-toggle">
                                    <vs-switch color="#4896e5" v-model="isMultipleFilter" class="switch-btn">
                                        <!-- <span slot="on" class="switch-font">{{ $t("conferences.datepicker.from_to") }}</span> -->
                                        <span slot="on" class="switch-font">Από έως</span>
                                        <!-- <span slot="off" class="switch-font">{{ $t("conferences.datepicker.date") }}</span> -->
                                        <span slot="off" class="switch-font">Ημερομηνία</span>
                                    </vs-switch>
                                </div>
                                <div v-if="isMultipleFilter">
                                    <label>{{ $t("conferences.datepicker.from") }}</label>
                                    <datepicker v-model="startDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε αρχική ημερομηνία"></datepicker>
                                    <label>{{ $t("conferences.datepicker.to") }}</label>
                                    <datepicker v-model="endDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε τελική ημερομηνία"></datepicker>
                                    <div class="datepkr-btn">
                                        <!-- <button class="btn reset-btn datepkr-btn-color" @click="getDates" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button> -->
                                        <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('multiple')" :disabled="isDisabled">Επαναφορά</button>
                                    </div>
                                </div>
                                <div v-else class="datepkr-toggle mt-2">
                                    <!-- <label>{{ $t("conferences.datepicker.select_single_date") }}</label> -->
                                    <datepicker v-model="singleDate" :format="myFormattedDate" :bootstrap-styling="true"
                                        wrapper-class="pickerDiv" placeholder="Επιλέξτε ημερομηνία"></datepicker>
                                    <div class="datepkr-btn">
                                        <!-- <button class="btn reset-btn datepkr-btn-color" @click="getDate" :disabled="isDisabled">{{ $t("conferences.datepicker.submit") }}</button> -->
                                        <button class="btn reset-btn datepkr-btn-color" @click="clearDatesInput('single')" :disabled="isDisabled">Επαναφορά</button>
                                    </div>
                                </div>
                                <!-- <div v-if="search.isDone && !search.hasData" class="col-12 mt-2">
                                    <h4>{{ $t("conferences.no_conferences_available") }}</h4>
                                </div> -->
                            </div>
                        </li>
                        <li>
                            
                        </li>
                    </ul>
                    <v-select multiple v-model="parties_dropdown_selected" :options="parties_dropdown_options" placeholder="Parties"></v-select>
                </li>
            </ul>

            <ul class="list-unstyled CTAs">
                <li>
                    <a v-on:click="do_search" class="do-search">{{ $t("conferences.search") }}</a>
                </li>
                <li>
                    <a class="reset">Reset options</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">

            <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <nav class="navbar navbar-expand-md py-0 px-4 navbar-light navbar-laravel navbar-bg-color menu-navbar d-none d-md-block">
            
                    </nav>
                </div>
            </nav> -->
            <img src="img/search-icon.png">
            <h2>Collapsible Sidebar Using Bootstrap 4</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h2>Lorem Ipsum Dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

            <div class="line"></div>

            <h3>Lorem Ipsum Dolor</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>
</template>
<style lang="scss" scoped>
/*
    perioxi apo to membership 
    koinov periodos (time-period)
    komma

*/

// @import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";
body {
    font-family: 'Poppins', sans-serif;
    background: #fafafa;
}

p {
    font-family: 'Poppins', sans-serif;
    font-size: 1.1em;
    font-weight: 300;
    line-height: 1.7em;
    color: #999;
}

a,
a:hover,
a:focus {
    color: inherit;
    text-decoration: none;
    transition: all 0.3s;
}

.navbar {
    padding: 15px 10px;
    background: #fff;
    border: none;
    border-radius: 0;
    margin-bottom: 40px;
    box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

.navbar-btn {
    box-shadow: none;
    outline: none !important;
    border: none;
}

.line {
    width: 100%;
    height: 1px;
    border-bottom: 1px dashed #ddd;
    margin: 40px 0;
}

/* ---------------------------------------------------
    SIDEBAR STYLE
----------------------------------------------------- */

.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#sidebar {
    min-width: 435px;
    max-width: 435px;
    background: #7386D5;
    color: #fff;
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -250px;
}

#sidebar .sidebar-header {
    padding: 20px;
    background: #6d7fcc;
}

#sidebar ul.components {
    padding: 20px 0;
    border-bottom: 1px solid #47748b;
}

#sidebar ul p {
    color: #fff;
    padding: 10px;
}

#sidebar ul li a {
    padding: 10px;
    font-size: 1.1em;
    display: block;
}

#sidebar ul li a:hover {
    color: #7386D5;
    background: #fff;
}

#sidebar ul li.active>a,
a[aria-expanded="true"] {
    color: #fff;
    background: #6d7fcc;
}

a[data-toggle="collapse"] {
    position: relative;
}

.dropdown-toggle::after {
    display: block;
    position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50%);
}

ul ul a {
    font-size: 0.9em !important;
    padding-left: 30px !important;
    background: #6d7fcc;
}

ul.CTAs {
    padding: 20px;
}

ul.CTAs a {
    text-align: center;
    font-size: 0.9em !important;
    display: block;
    border-radius: 5px;
    margin-bottom: 5px;
}

a.reset {
    background: #fff;
    color: #7386D5;
}

a.article,
a.article:hover {
    background: #6d7fcc !important;
    color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */

#content {
    width: 100%;
    padding: 20px;
    min-height: 100vh;
    transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */

@media (max-width: 768px) {
    #sidebar {
        margin-left: -250px;
    }
    #sidebar.active {
        margin-left: 0;
    }
    #sidebarCollapse span {
        display: none;
    }
}
</style>
<script>
    import moment from 'moment';
    import { mapState, mapGetters } from 'vuex'
    export default {
        props: {
            name: String,
            path: String
        },
        data(){
            return {
                tags: [],
                isActive: true,
                search: {
                    singleDate: [],
                    multipleDates: [],
                    hasData: false,
                    isDone: false,
                    type: ''
                },
                isMultipleFilter: false,
                showInfoDiv: false,
                selected_date: [],
                startDate: null,
                endDate: null,
                singleDate: null,
                parties_dropdown_selected: [],
                parties_dropdown_options: [],
                parties: []
            }
        },
        methods:{
            do_search() {
                let data = {
                    tags: this.tags,
                    parties: this.parties_dropdown_selected,
                    start_date: this.startDate,
                    end_date: this.endDate
                };
                console.log(data)
                setTimeout(() => {
                    api.call('post',this.api_path + 'search', data)
                    .then( response => {
                        console.log(111)
                    })
                }, 500)
            },
            load_parties_dropdown() {
                var self = this

                setTimeout(() => {
                    api.call('get', this.api_path + 'parties')
                    .then( response => {
                        if (response.status == 200 && response.statusText == "OK") {
                            if (response.data != null) {
                                response.data.data.forEach(function (arrayItem) {
                                    self.parties_dropdown_options.push(arrayItem.fullname_el);
                                });
                            }
                        }
                    })
                }, 500)
            },
            clear_parties_dropdown() {
                this.parties_dropdown_options = []
            },
            clearDatesInput(mode) {
                this.datepicker.hasData = false
                this.datepicker.isDone = false
                this.datepicker.type = ''
                if(mode == 'single'){
                    this.datepicker.singleDate = []
                    this.singleDate = ''
                }else if(mode == 'multiple'){
                    this.startDate = ''
                    this.endDate = ''
                    this.datepicker.multipleDates = []
                }
            },
            getDate() {
                this.datepicker.isDone = false
                this.datepicker.type = 'single'
                if (this.singleDate) {
                    this.singleDate = moment(this.singleDate).format('YYYY-MM-DD')
                    axios.get(this.api_path + 'conference/date/' + this.singleDate)
                    .then( response => {
                        if (response.status == 200 && response.statusText == "OK") {
                            if (response.data.data != null) {
                                this.datepicker.hasData = true
                                this.datepicker.singleDate = response.data.data
                            } else {
                                this.datepicker.hasData = false
                            }
                        }
                        this.ajaxDone = true
                        this.datepicker.isDone = true
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
                }
            },
            getDates(date) {
                //get dates from datepicker
                this.datepicker.isDone = false
                this.datepicker.type = 'multiple'
                if (this.startDate && this.endDate) {
                    this.startDate = moment(this.startDate).format('YYYY-MM-DD')
                    this.endDate = moment(this.endDate).format('YYYY-MM-DD')

                    axios.get(this.api_path + 'conference/start/' + 
                              this.startDate + '/end/' + 
                              this.endDate + '?order_field=' + 
                              this.order_field +'&orientation=' + 
                              this.order_orientation)
                        .then(response => {

                            if (response.status == 200 && response.statusText == "OK") {
                                if (response.data.data.length > 0) {
                                    this.datepicker.hasData = true
                                    this.datepicker.multipleDates = response
                                } else {
                                    this.datepicker.hasData = false
                                }
                            }
                            this.ajaxDone = true
                            this.datepicker.isDone = true
                        }).catch(function (error) {
                            console.log(error)
                        });
                }
            },
            resetDates() {
                //reset selected dates from dropdown
                this.selected_date = [];
                this.ajaxData.details = [];
            },
            myFormattedDate(date) {
                return moment(date).format('DD/MM/YYYY');
            },
            reset_all_opts: function () {
                this.isActive = true,
                this.search.singleDate = [],
                this.search.multipleDates = [],
                this.search.hasData = false,
                this.search.isDone = false,
                this.search.type = '',
                this.isMultipleFilter = false,
                this.showInfoDiv = false,
                this.selected_date = [],
                this.startDate = null,
                this.endDate = null,
                this.singleDate = null,
                this.parties_dropdown_selected = [],
                this.parties_dropdown_options = [],
                this.parties = []
            }
        },
        computed:{
            isDisabled() {
                if (this.singleDate && !this.isMultipleFilter) {
                    return (this.singleDate ? false : true)
                } else {
                    if (this.startDate && this.endDate && this.isMultipleFilter) {
                        return false;
                    } else {
                        return true;
                    }
                }
            },
            ...mapGetters({
                api_path: 'get_api_path'
            })
        },
        created() {
            this.load_parties_dropdown()
        }
    }
</script>