<template lang="html">
    <div>
        <div class="col-xs-12 page flex-middle">
            <div class="row">
                <div class="col-xs-12">
                    <message v-if="hasResetPassword" :message="response.message" :is-success="response.isSuccess"></message>
                    <form @submit.prevent="resetPassword()" class="auth-form" v-else>
                        <div class="form-control">
                            <input type="email" v-model="email" placeholder="Email" :class="{ error : errors.has('email') }">
                        </div>
                        <div class="form-control">
                            <button type="submit" class="cta async" :disabled="progress">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import axios from '@/config/axios';
    //import mixin from '@/mixins';

    import Message from '../components/Message.vue';

    export default {
        //mixins: [mixin],
        components: {
          Message
        },
        data: () => ({
            progress: false,
            hasResetPassword: false,
            email: '',
            response: {}
        }),
        // computed: {
        //     theme() {
        //         return this.$store.getters.theme
        //     },
        //     currentLocale() {
        //         return this.$store.getters.currentLocale
        //     }
        // },
        mounted() {
            this.$validator.attach('email', 'required', {
                prettyName: 'Email'
            });
        },
        methods: {
            resetPassword() {
                this.progress = true;
                axios.post('/auth/forgot', {
                        email: this.email
                    })
                    .then(() => {
                        this.progress = false;
                        this.hasResetPassword = true
                        this.response = {
                            isSuccess: true,
                            message: 'changed'
                        }
                    })
                    .catch(error => {
                        this.progress = false;
                        this.hasResetPassword = true
                        if (error.response) {
                            this.response = {
                                isSuccess: false,
                                message: 'Failed'
                            }
                        }
                    });
            },
        }
    }
</script>
