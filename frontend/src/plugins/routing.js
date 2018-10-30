export default {
    install(Vue) {
        if (!window.Routing) {
            return;
        }

        Vue.generateUrl = (route, params = [], absolute = false) => {
            return generateUrl(route, params, absolute);
        };

        Vue.prototype.$generateUrl = (route, params = [], absolute = false) => {
            return generateUrl(route, params, absolute);
        };
    },
};

/**
 * Generate route URL
 * @param {string} route
 * @param {object} params
 * @param {boolean} absolute
 * @return {string}
 */
function generateUrl(route, params = [], absolute = false) {
    return Routing.generate(route, params, absolute);
}
