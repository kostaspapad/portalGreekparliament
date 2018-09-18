class Auth {
    constructor() {
        this.token = window.localStorage.getItem('token');

        let userData = window.localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;

        if (this.token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.token;
        }
    }

    login (token, user) {        
        window.localStorage.setItem('token', token);
        window.localStorage.setItem('user', JSON.stringify(user));
    
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    
        this.token = token;
        this.user = user;
    
        Event.$emit('userLoggedIn');
    }

    check () {
        return !! this.token;
    }

    getUser() {
        api.call('get', '/api/get-user')
        .then(({data}) => {
            this.user = data;
        });
    }
}

export default Auth;