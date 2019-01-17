import {themes, DEFAULT_THEME} from './../util/theme';

export default {
    install(Vue, config) {
        config = Object.assign(
            {
                theme() {
                    return DEFAULT_THEME;
                },
            },
            config,
        );

        let theme = config.theme();
        if (!themes[theme]) {
            theme = DEFAULT_THEME;
        }

        Vue.$theme = themes[theme];
        Vue.$themeName = theme;

        Vue.prototype.$theme = themes[theme];
        Vue.prototype.$themeName = theme;
    },
};
