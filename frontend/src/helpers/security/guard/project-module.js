import store from '../../../store';

export default {
    async vote(route, config) {
        let project = store.getters.project;

        if ((!project || !project.projectModules) && route.params && route.params.id) {
            await store
                ._actions
                .getProjectById[0](route.params.id)
                .then(
                    () => {
                        return true;
                    },
                    () => {
                        return false;
                    }
                )
            ;
            project = store.getters.project;
        }

        if (!project || !project.projectModules) {
            return false;
        }

        for (let c = 0; c < config.length; c++) {
            if (project.projectModules.indexOf(config[c]) === -1) {
                if (process && process.env.NODE_ENV !== 'production') {
                    console.warn('Required project module(s) not enabled: ' + config[c]);
                }
                return false;
            }
        }

        return true;
    },
    supports(key) {
        return key.toLowerCase() === 'projectmodule';
    },
};
