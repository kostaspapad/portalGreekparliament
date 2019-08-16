// import VueInternationalization from 'vue-i18n'
import Locale from './vue-i18n-locales.generated'

import Vue from 'vue';
import VueI18n from 'vue-i18n';

const lang = document.documentElement.lang.substr(0, 2); 
// or however you determine your current app locale

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: lang,
    messages: Locale,
});

export default i18n;

// 7288148259310071
