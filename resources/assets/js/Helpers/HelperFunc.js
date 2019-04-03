import moment from 'moment'
export default {
    myFormattedDate: (date,lang) => {
        if(lang == 'el') {
            return moment(date).format('DD/MM/YYYY');
        } else if(lang == 'en') {
            return moment(date).format('YYYY-MM-DD')
        }
    }
}
