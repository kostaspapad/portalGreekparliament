class Auth {
    constructor() {
        this.token = null
        this.user = null

        this.token = window.localStorage.getItem('token')

        let userData = window.localStorage.getItem('user')
        this.user = userData ? JSON.parse(userData) : null

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token
            this.getUser()
        }
    }

    login (token, user) {        
        window.localStorage.setItem('token', token)
        window.localStorage.setItem('user', JSON.stringify(user))
    
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
    
        this.token = token
        this.user = user
    
        Event.$emit('userLoggedIn');
    }

    logout () {
        api.call('post', '/api/v1/logout')
        .then(({data}) => {
            if( data.status == 200 ){
                this.token = null
                this.user = null

                //empty localstorage
                window.localStorage.clear('token')
                window.localStorage.clear('user')
                Event.$emit('userLoggedOut')
            }
        })
    }

    check () {
        return !! this.token
    }

    getUser() {
        api.call('get', '/api/v1/get-user')
        .then(({data}) => {
            this.user = data
        })
    }
}

export default Auth;