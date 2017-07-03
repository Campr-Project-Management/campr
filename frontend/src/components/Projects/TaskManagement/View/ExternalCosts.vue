<template>
    <div>
        <p class="modal-title">{{ translateText('message.add_external_costs') }}</p>
        <div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-10">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.cost_description')"
                            v-model="newExternalCost.name"
                            v-bind:content="newExternalCost.name" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.qty')"
                            v-model="newExternalCost.qty" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group last-form-group">
                    <div class="col-md-8">
                        <radio-field
                            name="units"
                            v-bind:options="projectUnitsForSelect"
                            v-bind:currentOption="newExternalCost.selectedUnit"
                            v-model="newExternalCost.selectedUnit" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.custom')"
                            v-model="newExternalCost.customUnit"
                            v-bind:content="newExternalCost.customUnit"
                            v-bind:disabled="newExternalCost.selectedUnit !== 'custom'" />
                    </div>
                    <div class="col-md-2">
                        <input-field
                            type="text"
                            v-bind:label="translateText('label.external_cost_unit_rate')"
                            v-model="newExternalCost.rate"
                            v-bind:content="newExternalCost.rate" />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-space-between">
            <a href="javascript:void(0)" @click="cancellAddExternalCost" class="btn-rounded btn-empty danger-color danger-border">{{ translateText('button.cancel') }}</a>
            <a href="javascript:void(0)" @click="addExternalCost" class="btn-rounded">{{ translateText('message.yes') }} </a>
        </div>
    </div>
</template>

<script>
import InputField from '../../../_common/_form-components/InputField';
import SelectField from '../../../_common/_form-components/SelectField';
import RadioField from '../../../_common/_form-components/RadioField';
import {mapActions, mapGetters} from 'vuex';

export default {
    components: {
        InputField,
        SelectField,
        RadioField,
    },
    methods: {
        ...mapActions(['getProjectUnits']),
        addExternalCost: function() {
            this.$emit('input', this.newExternalCost);
        },
        cancellAddExternalCost: function() {
            this.$emit('input', null);
        },
        translateText(text) {
            return this.translate(text);
        },
    },
    created() {
        this.getProjectUnits(this.$route.params.id);
    },
    computed: {
        ...mapGetters({
            projectUnitsForSelect: 'projectUnitsForSelect',
        }),
    },
    data() {
        return {
            newExternalCost: {
                name: '',
                qty: 0,
                selectedUnit: null,
                customUnit: null,
                rate: null,
            },
        };
    },
};
</script>
