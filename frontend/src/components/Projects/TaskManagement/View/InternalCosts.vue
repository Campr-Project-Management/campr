<template>
    <div>
        <p class="modal-title">{{ translateText('message.add_internal_costs') }}</p>
        <div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-6">
                        <select-field
                            v-bind:title="translateText('label.resource')"
                            v-bind:options="resourcesForSelect"
                            v-bind:currentOption="newInternalCost.resource"
                            v-model="newInternalCost.resource" />
                     </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.daily_rate')"
                            v-model="newInternalCost.daily_rate" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.qty')"
                            v-model="newInternalCost.qty" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.days')"
                            v-model="newInternalCost.days" />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-space-between">
            <a href="javascript:void(0)" @click="cancellAddInternalCost" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
            <a href="javascript:void(0)" @click="addInternalCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import SelectField from '../../../_common/_form-components/SelectField';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
    },
    methods: {
        ...mapActions(['getProjectResources']),
        addInternalCost: function() {
            this.$emit('input', this.newInternalCost);
        },
        cancellAddInternalCost: function() {
            this.$emit('input', null);
        },
        translateText(text) {
            return this.translate(text);
        },
    },
    created() {
        this.getProjectResources(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            resourcesForSelect: 'projectResourcesForSelect',
        }),
    },
    data() {
        return {
            newInternalCost: {
                resource: '',
                daily_rate: 0,
                qty: 0,
                days: 0,
            },
        };
    },
};
</script>
