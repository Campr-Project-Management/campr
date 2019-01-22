import _ from 'lodash';
import {
    getScheduleForecastTrafficLight,
    getScheduleActualTrafficLight,
} from './traffic-light';
import currentTheme from './theme';

let theme = currentTheme();

export const LIGHT_RED = theme.lightRed;
export const LIGHT_YELLOW = theme.lightYellow;
export const LIGHT_GREEN = theme.lightGreen;
export const RED = theme.red;
export const YELLOW = theme.yellow;
export const GREEN = theme.green;

export const trafficLight = Object.freeze({
    red: LIGHT_RED,
    yellow: LIGHT_YELLOW,
    green: LIGHT_GREEN,
});

export const green = Object.freeze({
    light: trafficLight.green,
});

export const costChart = Object.freeze({
    base: trafficLight.green,
    actual: trafficLight.red,
    forecast: theme.middle,
    remaining: theme.light,
});

const statusToTrafficLightMap = Object.freeze({
    0: trafficLight.red,
    1: trafficLight.yellow,
    2: trafficLight.green,
});

export const riskManagement = Object.freeze({
    risk: {
        _colorByPriority: {
            0: GREEN,
            1: LIGHT_GREEN,
            2: LIGHT_YELLOW,
            3: LIGHT_RED,
            4: RED,
        },
        getColorByPriority(priority) {
            return this._colorByPriority[priority];
        },
    },
    opportunity: {
        _colorByPriority: {
            0: RED,
            1: LIGHT_RED,
            2: LIGHT_YELLOW,
            3: LIGHT_GREEN,
            4: GREEN,
        },
        getColorByPriority(priority) {
            return this._colorByPriority[priority];
        },
    },
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
    getForecastColor(scheduled, forecast, actual) {
        let tl = getScheduleForecastTrafficLight(scheduled, forecast, actual);

        if (tl.isGreen()) {
            return;
        }

        if (tl.isYellow()) {
            return trafficLight.yellow;
        }

        return trafficLight.red;
    },
    getActualColor(base, forecast, actual, completed = true) {
        let tl = getScheduleActualTrafficLight(base, forecast, actual,
            completed);

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

