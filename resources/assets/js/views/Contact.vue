<template>
    <div>
        <div class="row justify-content-center py-2 mr-0">
            <div style="width: 750px;">
                <div class="card">
                    <div class="card-header text-center">{{ $t("contact.title") }}</div>
                    <div class="card-body">
                        <form v-on:submit.prevent="contact" aria-label="contact">
                            <div class="form-group">
                                <div class="col-md-6 m-auto">
                                    <input 
                                    v-model.trim="$v.name.$model" 
                                    id="name`" 
                                    type="name" 
                                    class="form-control"
                                    name="name" 
                                    required
                                    :placeholder="$t('contact.input_name')">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 m-auto">
                                    <input 
                                        v-model.trim="$v.email.$model" 
                                        id="email" 
                                        type="email" 
                                        class="form-control"
                                        name="email" 
                                        required
                                        placeholder="Email"
                                    >
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 m-auto">
                                    <textarea 
                                        v-model.trim="$v.message.$model"
                                        id="message"
                                        class="form-control"
                                        rows="3"
                                        :placeholder="$t('contact.input_message')"
                                        required
                                    >
                                    </textarea>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-info" :disabled="!$v.email.email" :class="{ 'not-allowed': !$v.email.email }">
                                    {{ $t("contact.submit") }}
                                </button>
                            </div>
                            <div class="text-center mt-4" v-if="ajax.isLoaded">
                                <h2>Sending <i class="fas fa-spinner fa-spin"></i></h2>
                            </div>
                        </form>
                        <div class="error" v-if="!$v.email.email">{{ $t("contact.mail_example") }}</div>
                        <p class="error" v-if="submitStatus === 'ERROR'">{{ $t("contact.form_err") }}</p>
                        <p class="ok" v-if="submitStatus === 'OK'">{{ $t("contact.submit_ok") }}</p>
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
                ajax: {
                    isLoaded: false
                },
                name: null,
                email: null,
                message: null,
                submitStatus: null,
                error_msg: null
            };
        },
        validations: {
            name: {
                required
            },
            email: {
                required,
                email
            },
            message: {
                required
            }
        },
        methods: {
            contact() {
                this.error_msg = null
                let data = {
                    name: this.name,
                    email: this.email,
                    message: this.message
                };
                
                this.$v.$touch()

                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'

                } else {
                    this.ajax.isLoaded = true
                    setTimeout(() => {
                        api.call('post', '/api/v1/contact', data)
                        .then(({data}) => {
                            this.submitStatus = 'OK'
                            this.clearVariables()
                        })
                        .catch( (error) => {
                            if( error.data.status == 400 ){
                                console.log(error)
                                this.submitStatus = 'ERROR'
                                this.error_msg = "Error"
                            }
                        })
                    }, 1000)
                }
            },
            clearVariables() {
                this.name = ''
                this.email = ''
                this.message = ''
                this.submitStatus = null
                this.ajax.isLoaded = false
            }
        }
    }
</script>