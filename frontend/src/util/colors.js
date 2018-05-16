import _ from 'lodash';
import moment from 'moment';

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

/**
 * Forecast finish date color
 *
 * @param {string} scheduledFinishAt
 * @param {string} forecastFinishAt
 * @return {string}
 */
export function getForecastDateColor(scheduledFinishAt, forecastFinishAt) {
    if (!scheduledFinishAt || !forecastFinishAt) {
        return '';
    }

    if (moment(forecastFinishAt).isAfter(scheduledFinishAt)) {
        return trafficLight.yellow;
    }

    return '';
}

/**
 * Actual finish date color
 *
 * @param {string} forecastFinishAt
 * @param {string} actualFinishAt
 * @return {string}
 */
export function getActualDateColor(forecastFinishAt, actualFinishAt) {
    if (!forecastFinishAt || !actualFinishAt) {
        return '';
    }

    if (moment(actualFinishAt).isAfter(forecastFinishAt)) {
        return trafficLight.red;
    }

    return '';
}

export default Object.freeze({
    trafficLight,
    green,
    getTrafficLightColorByStatus,
    getForecastDateColor,
    getActualDateColor,
});

