<template>
    <div>
        <div class="row justify-content-center mr-0">
            <!-- <div class="col-md-8"> -->
            <div style="width: 750px;">
                <div class="card">
                    <div class="card-header text-center">Contact us</div>
                    <div class="card-body">
                        <form v-on:submit.prevent="contact" aria-label="contact">
                            <div class="form-group">
                                <!-- <label for="name" class="col-sm-4 col-form-label text-md-right">{{$t("contact.input_name")}}</label> -->
                                <div class="col-md-6 m-auto">
                                    <input 
                                        v-model.trim="$v.name.$model" 
                                        id="name`" 
                                        type="name" 
                                        class="form-control"
                                        name="name" 
                                        required
                                        :placeholder="$t('contact.input_name')"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="email" class="col-sm-4 col-form-label text-md-right">Email</label> -->
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
                                <!-- <label for="message" class="col-md-4 col-form-label text-md-right">{{$t("contact.input_message")}}</label> -->
                                <div class="col-md-6 m-auto">
                                    <!-- <input v-model.trim="$v.password.$model" id="password" type="password" class="form-control" name="password" required> -->
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
                                <!-- <div class="col-md-8 offset-md-4"> -->
                                    <button type="submit" class="btn btn-info" :disabled="!$v.email.email" :class="{ 'not-allowed': !$v.email.email }">
                                        Submit
                                    </button>
                                <!-- </div> -->
                            </div>
                        </form>
                        <div class="error" v-if="!$v.email.email">Email is not valid (e.g , test@mail.com)</div>
                        <p class="error" v-if="submitStatus === 'ERROR'">Please fill the form correctly.</p>
                        <p class="ok" v-if="submitStatus === 'OK'">Message sent</p>
                        <p class="error">{{error_msg}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    
</style>


<script>
    import { required, minLength, email } from 'vuelidate/lib/validators'
    export default {
        data() {
            return {
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
                    setTimeout(() => {
                        api.call('post', '/api/v1/contact', data)
                        .then(({data}) => {
                            console.log(data)
                            console.table(data)
                            this.submitStatus = 'OK'
                        })
                        .catch( (error) => {
                            if( error.data.status == 400 ){
                                console.log(error)
                                this.submitStatus = 'ERROR'
                                this.error_msg = "Error"
                            }
                        })
                    }, 500)
                }
            }
        }
    }
</script>