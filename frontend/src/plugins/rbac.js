import _ from 'lodash';

export default {
    install(Vue, config) {
        config = Object.assign({user: {}}, config);

        Vue.mixin({
            beforeCreate() {
                let options = this.$options || {};
                let store = this.$store || {};

                options.methods = options.methods || {};

                options.methods.$can = (role, subject) => {
                    return isGranted(role, getUser(store, config.user), subject);
                };
            },
            beforeMount() {
                Vue.component(canComponent.name, canComponent);
            },
        });
    },
};

let canComponent = {
    name: 'can',
    props: {
        role: {
            type: String,
            required: true,
        },
        subject: {
            type: [Object],
            required: false,
        },
        denyMessage: {
            type: String,
            required: false,
            default: 'Access denied.',
        },
        silent: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    render(h) {
        if (this.$can(this.role, this.subject)) {
            return h('div', this.$slots.default);
        }

        if (this.silent) {
            return null;
        }

        return h(
            'div',
            {
                class: ['access-denied-message'],
            },
            this.translate(this.denyMessage)
        );
    },
};

/**
 * Get user
 * @param {object} store
 * @param {function|object} user
 * @return {object}
 */
function getUser(store, user) {
    return typeof user === 'function' ? user(store) : user;
}

/**
 * Check authorization role
 * @param {string} role
 * @param {object} user
 * @param {object} subject
 * @return {boolean}
 */
function isGranted(role, user, subject) {
    if (!user || !subject) {
        return false;
    }

    if (user.roles == null) {
        console.warn('User does not have a property: roles');
        return false;
    }

    if (!Array.isArray(user.roles)) {
        console.warn(`Invalid user roles: ${typeof user.roles}`);
        return false;
    }

    if (user.roles.indexOf('ROLE_SUPER_ADMIN') >= 0) {
        return true;
    }

    let roles = [];
    roles.push(...user.roles);

    let projectUser = _.find(user.projectUsers, (projectUser) => {
        return projectUser.project === subject.id;
    });

    if (projectUser) {
        roles.push(...projectUser.projectRoleNames);
    }

    let found = _.find(role.split('|'), (r) => roles.indexOf(r.trim()) >= 0);

    return !!found;
}
