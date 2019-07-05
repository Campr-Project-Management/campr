import {
    MODULE_PHASES_AND_MILESTONES,
    MODULE_INTERNAL_COSTS,
    MODULE_EXTERNAL_COSTS,
    MODULE_RISKS_AND_OPPORTUNITIES,
    MODULE_TODOS,
    MODULE_DECISIONS,
    MODULE_INFOS,
} from '../../../frontend/src/helpers/project-module';

export default {
    data () {
        return {
            activeModules: []
        };
    },
    methods: {
        isModuleActive(module) {
            return this.activeModules
                && typeof this.activeModules.indexOf === 'function'
                && this.activeModules.indexOf(module) >= 0;
        },
        isPhasesAndMilestoneModuleActive() {
            return this.isModuleActive(MODULE_PHASES_AND_MILESTONES);
        },
        isInternalCostsModuleActive() {
            return this.isModuleActive(MODULE_INTERNAL_COSTS);
        },
        isExternalCostsModuleActive() {
            return this.isModuleActive(MODULE_EXTERNAL_COSTS);
        },
        isRiskAndOpportunitiesModuleActive() {
            return this.isModuleActive(MODULE_RISKS_AND_OPPORTUNITIES);
        },
        isTodosModuleActive() {
            return this.isModuleActive(MODULE_TODOS);
        },
        isInfosModuleActive() {
            return this.isModuleActive(MODULE_INFOS);
        },
        isDecisionsModuleActive() {
            return this.isModuleActive(MODULE_DECISIONS);
        }
    }
};
