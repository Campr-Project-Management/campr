import {TrafficLight} from './../../util/traffic-light';
import {trans} from './../../util/Translator';

const state = {
    trafficLights: [
        TrafficLight.createGreen(),
        TrafficLight.createYellow(),
        TrafficLight.createRed(),
    ],
    defaultTrafficLight: TrafficLight.createGreen(),
    defaultTrafficLightValue: TrafficLight.createGreen(),
};

const getters = {
    trafficLights: state => state.trafficLights,
    trafficLightsForSelect: (state, getters) => {
        return getters.trafficLights.map((tl) => {
            return {
                key: tl.getValue(),
                label: trans(tl.getLabel()),
            };
        });
    },
    defaultTrafficLight: state => state.defaultTrafficLight,
    defaultTrafficLightValue: (state, getters) => getters.defaultTrafficLight.getValue(),
    trafficLightByValue: () => (value) => {
        return new TrafficLight(value);
    },
    trafficLightColorByValue: ({}, getters) => (value) => {
        return getters.trafficLightByValue(value).getColor();
    },
    trafficLightLabelByValue: ({}, getters) => (value) => {
        return trans(getters.trafficLightByValue(value).getLabel());
    },
};

export default {
    state,
    getters,
};
