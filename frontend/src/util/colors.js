import _ from 'lodash';
import {getScheduleForecastTrafficLight, getScheduleActualTrafficLight} from './traffic-light';

export const trafficLight = Object.freeze({
    red: '#c87369',
    yellow: '#ccba54',
    green: '#5FC3A5',
});

export const green = Object.freeze({
    light: trafficLight.green,
});

export const costChart = Object.freeze({
    base: trafficLight.green,
    actual: trafficLight.red,
    forecast: '#646EA0',
    remaining: '#2E3D60',
});

const statusToTrafficLightMap = Object.freeze({
    0: trafficLight.red,
    1: trafficLight.yellow,
    2: trafficLight.green,
});

/**
 * Returns traffic ligth hex color by status
 *
 * @param {int} status
 * @return {string}
 */
export function getTrafficLightColorByStatus(status) {
    status = _.toNumber(status);

    return statusToTrafficLightMap[status];
}

export const schedule = Object.freeze({
    getForecastColor(scheduled, forecast) {
        let tl = getScheduleForecastTrafficLight(scheduled, forecast);

        if (tl.isGreen()) {
            return;
        }

        if (tl.isYellow()) {
            return trafficLight.yellow;
        }

        return trafficLight.red;
    },
    getActualColor(forecast, actual, completed = true) {
        let tl = getScheduleActualTrafficLight(forecast, actual, completed);

        if (tl.isGreen()) {
            return;
        }

        if (tl.isYellow()) {
            return trafficLight.yellow;
        }

        return trafficLight.red;
    },
});

export default Object.freeze({
    trafficLight,
    green,
    getTrafficLightColorByStatus,
    schedule,
});

