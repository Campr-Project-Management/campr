import ProjectModule from './guard/project-module';
import _ from 'lodash';

export default {
    modules: [
        ProjectModule,
    ],
    /**
     * Checks for invalid keys in the config.
     *
     * @param {object} guard
     * @return {mixed}
     * @private
     */
    _validateGuardConfig(guard) {
        const keys = Object.keys(guard);
        const supportedKeys = [];

        keys.map((key) => {
            this.modules.map((mod) => {
                if (mod.supports(key) && supportedKeys.indexOf(key) === -1) {
                    supportedKeys.push(key);
                }
            });
        });

        if (supportedKeys.length !== keys.length) {
            return new Error('Found unsupported key(s): ' + _.difference(keys, supportedKeys).join(', '));
        }

        return true;
    },
    /**
     * Will do the actual finding of the guard config, looking into the current route and matched routes.
     *
     * @param {object} route
     * @return {mixed}
     * @private
     */
    _findGuardConfig(route) {
        const guard = route.meta
            ? route.meta.guard
            : undefined;

        if (guard) {
            return guard;
        }

        if (!route.matched || !route.matched.length) {
            return null;
        }

        for (let c = route.matched.length - 1; c >= 0; c--) {
            let g = this._findGuardConfig(route.matched[c]);
            if (g) {
                return g;
            }
        }

        return null;
    },
    /**
     * Checks whether a route can be accessed or not.
     * @param {object} route
     * @return {mixed}
     */
    canAccess(route) {
        const guard = this._findGuardConfig(route);
        if (!guard) {
            return undefined;
        }

        /**
         * If the guard is miss-configured, disallow access to make it obvious.
         */
        const validity = this._validateGuardConfig(guard);
        if (validity instanceof Error) {
            return validity;
        }

        // do the actual checking
        const guardKeys = Object.keys(guard);
        for (let k = 0; k < guardKeys.length; k++) {
            for (let m = 0; m < this.modules.length; m++) {
                if (this.modules[m].supports(guardKeys[k]) && this.modules[m].vote(route, guard[guardKeys[k]]) === false) {
                    return false;
                }
            }
        }

        return undefined;
    },
    /**
     * Hook for VueRouter.beforeEach()
     *
     * @see https://router.vuejs.org/guide/advanced/navigation-guards.html#global-guards
     * @param {object} to
     * @param {object} from
     * @param {function} next
     */
    beforeEach(to, from, next) {
        next(this.canAccess(to));
    },
};
