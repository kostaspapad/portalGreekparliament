<template>
    <div>
        <div class="row justify-content-center py-2 mr-0">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">{{ $t("register.register") }}</div>
                    <div class="card-body">
                        <form @submit.prevent="register" aria-label="Register">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">
                                    <i class="fas fa-asterisk asterisk-color"></i>
                                    {{ $t("register.name") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        v-model.trim="$v.registerData.name.$model"
                                        id="name" 
                                        type="text" 
                                        class="form-control" 
                                        name="name"
                                        required 
                                        autofocus
                                    >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">
                                    <i class="fas fa-asterisk asterisk-color"></i>
                                    {{ $t("register.email") }}
                                </label>
                                <div class="col-md-6">
                                    <input 
                                        v-model.trim="$v.registerData.email.$model"
                                        id="email" 
                                        type="email" 
                                        class="form-control"
                                        :class="{ 'border-color-error': !$v.registerData.email.email }"
                                        name="email"
                                        required
                                    >
                                    <div class="error" v-if="!$v.registerData.email.email">{{ $t("register.not_valid_mail") }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">
                                    <i class="fas fa-asterisk asterisk-color"></i>
                                    {{ $t("register.password") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        v-model.trim="$v.registerData.password.$model"
                                        id="password" 
                                        type="password" 
                                        class="form-control"
                                        :class="{ 'border-color-error': !$v.registerData.password.minLength }"
                                        name="password" 
                                        required
                                    >
                                    <div class="error" v-if="!$v.registerData.password.minLength">
                                        {{ $t("register.pass_least") }} {{$v.registerData.password.$params.minLength.min}} {{ $t("register.letters") }}
                                    </div>
                                    <!-- <div class="error" v-if="!$v.registerData.password.number">
                                        {{ $t("register.least_one_number") }}
                                    </div> -->
                                    <!-- <div class="error" v-if="!$v.registerData.password.uppercaseLetter">
                                        {{ $t("register.least_one_uppercase") }}
                                    </div>
                                    <div class="error" v-if="!$v.registerData.password.lowercaseLetter">
                                        {{ $t("register.least_one_lowercase") }}
                                    </div> -->
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                    <i class="fas fa-asterisk asterisk-color"></i>
                                    {{ $t("register.confirm_pass") }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        v-model.trim="$v.registerData.confirm_password.$model"
                                        id="password-confirm" 
                                        type="password" 
                                        class="form-control"
                                        :class="{ 'border-color-error': !$v.registerData.confirm_password.sameAsPassword }"
                                        name="password_confirmation" 
                                        required
                                    >
                                    <div class="error" v-if="!$v.registerData.confirm_password.sameAsPassword">{{ $t("register.identical_pass") }}</div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 text-center">
                                <div class="col-12">
                                    <button type="submit" class="btn" :disabled="isDisabled" :class="{ 'not-allowed': isDisabled }" style="background-color: #17a2b8;color:white;">
                                        {{ $t("register.register") }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <p class="mt-1">{{ $t("register.fields_with") }} <i class="fas fa-asterisk asterisk-color"></i> {{ $t("register.required") }}</p>
                        <p class="info">{{ $t("register.cap_and_lowercase") }}</p>
                        <!-- <p class="error" v-if="submitStatus === 'ERROR'">Please fill the form correctly.</p>
                        <p class="pending" v-if="submitStatus === 'PENDING'">Loggin in...</p>-->
                        <p class="error">{{error_msg}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { required, minLength, email, sameAs, helpers } from 'vuelidate/lib/validators'
    const number = helpers.regex('number', /[0-9]+/);
    const uppercaseLetter = helpers.regex('uppercaseLetter', /[A-Z]+/)
    const lowercaseLetter = helpers.regex('lowercaseLetter', /[a-z]+/)
    export default {
        data(){
            return {
                error_msg: null,
                registerData:{
                    name: '',
                    email: '',
                    password: '',
                    confirm_password: ''
                }
            }
        },
        validations: {
            registerData: {
                name: {
                    required
                },
                email: {
                    required,
                    email
                },
                password: {
                    required,
                    minLength: minLength(8),
                    // number,
                    // uppercaseLetter,
                    // lowercaseLetter,
                },
                confirm_password:{
                    sameAsPassword: sameAs('password')
                }
            }
        },
        methods: {
            register(){
                setTimeout(() => {
                    this.submitStatus = 'OK'
                    api.call('post', '/api/v1/register', this.registerData)
                    .then(({data}) => {
                        this.$router.push('/login');
                    })
                    .catch( (error) => {
                        if( error.data.status == 400 ){
                            this.error_msg = error.data.message
                        }
                    })
                }, 500)
            }
        },
        computed:{
            isDisabled(){
                this.$v.$touch()
                //if all fields are not empty
                if(this.$v.registerData.$model.name && this.$v.registerData.$model.email && this.$v.registerData.$model.password && this.$v.registerData.$model.confirm_password){
                    return false
                }else{
                    return true
                }
                
            }
        }
    }
</script>
