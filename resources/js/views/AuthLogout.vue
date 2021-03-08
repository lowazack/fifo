<template lang="html">
    <div>
        <div class="col-xs-12 page page--login">

            <div class="row middle-xs h100">

                <div class="col-xs-12 center-xs">
                    <form @submit.prevent="loginWithLocalStrategy()" v-if="!showForgotPasswordForm" class="auth-form col-xs-12">
                        <div class="form-control">
                            <input :style="theme.paragraph" v-model="userLogin.email" type="email" :placeholder="$t(39)" required :class="{ error : errors.has('email') }" @change="sendGaTrack('email')">
                        </div>
                        <div class="form-control">
                            <input :style="theme.paragraph" v-model="userLogin.password" type="password" :placeholder="$t(55)" required :class="{ error : errors.has('password') }" @change="sendGaTrack('password')">
                        </div>
                        <div class="form-control">
                            <button :style="theme.paragraph" class="button cta async" type="submit" :disabled="progress">
                                {{ $t(5) }}
                                <span class="progress" v-if="progress" :class="{ 'active' : progress }"></span>
                            </button>
                        </div>
                    </form>
                    <a class="italic" :style="theme.paragraph" @click="forgotPassword()">{{ $t(56) }}</a>
                </div>

            </div>

        </div>
        <widget-footer :navigate-backwards="back"></widget-footer>
    </div>
</template>

<script>
    //import axios from '@/config/axios';
    //import store from '@/store';
    //import mixin from '@/mixins';
    //import {
    //    open
    //} from '@/helpers/popup';

    export default {
        //mixins: [mixin],
        data: () => ({
            showPasswordError: false,
            showUserError: false,
            showForgotPasswordForm: false,
            forgotPasswordSuccess: '',
            forgotPasswordEmail: '',
            progress: false,
            userLogin: {
                email: '',
                password: '',
            },
        }),
        mounted() {
            this.$validator.attach('email', 'required', {
                prettyName: 'Email'
            });
            this.$validator.attach('password', 'required', {
                prettyName: 'Password'
            });
            this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'pageview', '/04-login'])
        },
        methods: {
            sendGaTrack(field) {
                this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', '04-login', field])
            },
            back() {
                this.sendGaTrack('back')
                this.$router.go(-1);
            },
            forgotPassword() {
                this.sendGaTrack('forgot-password')
                this.$router.push('/auth/forgot');
            },
            loginWithLocalStrategy() {
                this.progress = true;
                this.showUserError = false;
                this.showPasswordError = false;
                this.sendGaTrack('continue');
                this.$validator.validateAll({
                    email: this.userLogin.email,
                    password: this.userLogin.password
                }).then((result) => {
                    if (result) {
                        axios.post('/auth/login', this.userLogin)
                            .then((response) => {
                                this.progress = false;
                                this.$router.push('/your-size')
                            })
                            .catch((errors) => {
                                this.progress = false;
                                if (errors.response.data.error == "Incorrect password") {
                                    this.showPasswordError = true;
                                } else if (errors.response.data.error == "User not found") {
                                    this.showUserError = true;
                                }
                            });
                    }
                });
            },
            openAuthWindow(provider) {
                this.sendGaTrack('oauth-'+provider);
                /*
                 * When the popup is closed by the user, the callback
                 * function below is called, with the server response
                 * IF successful 'unfillerror' will be false, and the
                 * user is navigated to the 'your-size' view.
                 *
                 * Otherwise they are redirected to the first page
                 * in the process, telling them they don't have
                 * a profile yet.
                 */
                //this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', 'form-interaction', 'login-oauth'])
                //this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', 'link-click', `${this.apiPath}/auth/${provider}?islogin=true`])
                open(`${this.apiPath}/auth/${provider}?islogin=true`, (err, code) => {
                    const unfillerror = code.unfillerror || null;
                    const token = code.token || null;
                    if (unfillerror === 'true' || token.length < 1) {
                        this.$router.push('/edit-profile-details?unfillerror=true')
                    } else {
                        window.localStorage.setItem('authtoken', token);
                        this.$router.push('/your-size')
                    }
                });
            },
        },
        computed: {
            apiPath() {
                return this.$store.getters.apiPath;
            },
            theme() {
                return this.$store.getters.theme
            },
            currentLocale() {
                return this.$store.getters.currentLocale
            },
            iframeContext() {
                return this.$store.getters.iframeContext
            }
        },
    };
</script>
