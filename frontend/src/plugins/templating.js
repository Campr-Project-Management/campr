export default {
    install(Vue) {
        Vue.mixin({
            filters: {
                defaultValue(value, defaultValue) {
                    if (value == null) {
                        return defaultValue;
                    }

                    return value;
                },
            },
        });
    },
};
