import VueFlashMessage from 'vue-flash-message';

export default {
    install(Vue) {
        Vue.use(VueFlashMessage);
        Vue.mixin({
            methods: {
                $flashSuccess(message) {
                    this.flash(this.translate(message), 'success', {
                        timeout: 3000,
                    });
                },
                $flashError(message) {
                    this.flash(this.translate(message), 'error', {
                        timeout: 3000,
                    });
                },
                $flashWarning(message) {
                    this.flash(this.translate(message), 'warning', {
                        timeout: 3000,
                    });
                },
                $flashInfo(message) {
                    this.flash(this.translate(message), 'info', {
                        timeout: 3000,
                    });
                },
            },
        });
    },
};
