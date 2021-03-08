<template lang="html">
    <div>
        <div class="col-xs-12 page page--reset flex-middle">
            <div class="row">
                <div class="col-xs-12 ta-c">
                    <h2 class="italic ta-c" :style="theme.paragraph">{{ $t(78) }}</h2>
                    <div v-if="resetPasswordSuccess">
                        <validation-message :message="response.message" :is-success="response.isSuccess">
                        </validation-message>
                        <button class="cta" :style="theme.paragraph">
                            <a @click="login()">{{ $t(5) }}</a>
                        </button>
                    </div>
                    <form v-else @submit.prevent="resetPassword()" class="auth-form">
                        <div class="form-control">
                            <input :style="theme.paragraph" type="email" v-model="reset.email" :placeholder="$t(39)" @change="sendGaTrack('email')">
                        </div>
                        <div class="form-control">
                            <input :style="theme.paragraph" type="password" v-model="reset.password" :placeholder="$t(80)" @change="sendGaTrack('password')">
                        </div>
                        <div class="form-control">
                            <input :style="theme.paragraph" type="password" v-model="reset.confirmPassword" :placeholder="$t(81)" @change="sendGaTrack('password-confirm')">
                        </div>
                        <div>
                            <button :style="theme.paragraph" type="submit" class="cta async" :disabled="progress">
                                {{ $t(79) }}
                                <span class="progress" v-if="progress" :class="{ 'active' : progress }"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <widget-footer :navigate-backwards="navigateBackwards"></widget-footer>
    </div>
</template>

<script>
    //import axios from '@/config/axios'
    //import mixin from '@/mixins';

    export default {
        //mixins: [mixin],
        data: () => ({
            resetPasswordSuccess: false,
            reset: {
                token: '',
                password: '',
                confirmPassword: '',
                email: '',
            },
        }),
        beforeRouteEnter(to, from, next) {
            next(vm => vm.reset.token = decodeURI(to.query.t) || '');
        },
        computed: {
            theme() {
                return this.$store.getters.theme
            },
            currentLocale() {
                return this.$store.getters.currentLocale
            }
        },
        mounted() {
            this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'pageview', '/04-login-forgot-password'])
        },
        methods: {
            sendGaTrack(field) {
                this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', '04-login-forgot-password', field])
            },
            navigateBackwards() {
                this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', '04-login-forgot-password', 'back'])
                this.$router.go(-1);
            },
            resetPassword() {
                this.progress = true;
                this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', '04-login-forgot-password', 'reset'])
                axios.post('auth/reset', this.reset)
                    .then(() => {
                        this.progress = false;
                        this.resetPasswordSuccess = true;
                        this.response = {
                            isSuccess: true,
                            message: this.$t(109)
                        }
                    })
                    .catch((error) => {
                        this.progress = false;
                    });
            },
            login() {
                this.$store.dispatch('sendGoogleAnalytics', ['measmerizeTracker.send', 'event', 'link-click', '/auth/login'])
                this.$router.push('/auth/login');
            }
        },
    }
</script>
