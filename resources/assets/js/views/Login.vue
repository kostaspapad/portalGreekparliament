<template>
    <div>
        <div class="row justify-content-center py-2 mr-0">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ $t("login.login") }}</div>
                    <div class="card-body">
                        <form v-on:submit.prevent="login" aria-label="login">
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label text-md-right">{{ $t("login.email") }}</label>
                                <div class="col-md-6">
                                    <input 
                                        v-model.trim="$v.email.$model" 
                                        id="email" 
                                        type="email" 
                                        class="form-control"
                                        :class="{ 'border-color-error': !$v.email.email }"
                                        name="email" 
                                        required 
                                        autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ $t("login.pass") }}</label>
                                <div class="col-md-6">
                                    <input v-model.trim="$v.password.$model" id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-info" :disabled="!$v.email.email" :class="{ 'not-allowed': !$v.email.email }">
                                        {{ $t("login.login") }}
                                    </button>
                                    <a class="btn btn-link" href="/forgot-password" style="color: #17a2b8;">
                                        {{ $t("login.forgot_pass") }}
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="error" v-if="!$v.email.email">{{ $t("login.not_valid_mail") }}</div>
                        <p class="error" v-if="submitStatus === 'ERROR'">{{ $t("login.form_err") }}</p>
                        <!-- <p class="pending" v-if="submitStatus === 'PENDING'">Loggin in...</p> -->
                        <p class="error">{{error_msg}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { required, minLength, email } from 'vuelidate/lib/validators'
    export default {
        data() {
            return {
                email: null,
                password: null,
                submitStatus: null,
                error_msg: null
            };
        },
        validations: {
            email: {
                required,
                email
            },
            password: {
                required
            }
        },
        methods: {
            login() {
                this.error_msg = null
                let data = {
                    username: this.email,
                    password: this.password
                };
                this.$v.$touch()
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    // do your submit logic here
                    this.submitStatus = 'PENDING'
                    setTimeout(() => {
                        this.submitStatus = 'OK'
                        api.call('post', '/api/v1/login', data)
                        .then(({data}) => {
                            auth.login(data.token, data.user)
                            this.$router.push('/')
                        })
                        .catch( (error) => {
                            if( error.data.status == 400 ){
                                console.log(error)
                                this.error_msg = "Wrong email or password"
                            }
                        })
                    }, 500)
                }
            }
        }
    }
</script>