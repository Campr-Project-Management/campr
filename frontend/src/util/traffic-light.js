import moment from 'moment';
import {getTrafficLightColorByStatus} from './colors';
// import {trans} from './Translator';
import {trans} from 'Translator';

/**
 * Traffic Light
 */
export class TrafficLight {
    /**
     * @param {int} value
     */
    constructor(value) {
        this.value = value;
    }

    /**
     * @return {boolean}
     */
    isRed() {
        return this.getValue() === TrafficLight.RED;
    }

    /**
     * @return {boolean}
     */
    isYellow() {
        return this.getValue() === TrafficLight.YELLOW;
    }

    /**
     * @return {boolean}
     */
    isGreen() {
        return this.getValue() === TrafficLight.GREEN;
    }

    /**
     * @return {string}
     */
    getCode() {
        return TrafficLight.CODES[this.getValue()];
    }

    /**
     * @return {string}
     */
    getColor() {
        return getTrafficLightColorByStatus(this.value);
    }

    /**
     * @return {int}
     */
    getValue() {
        return this.value;
    }

    /**
     * @return {string}
     */
    getLabel() {
        return trans(`traffic_light.${this.getCode()}`);
    }
}

TrafficLight.RED = 0;
TrafficLight.YELLOW = 1;
TrafficLight.GREEN = 2;

TrafficLight.CODES = {
    [TrafficLight.RED]: 'red',
    [TrafficLight.YELLOW]: 'yellow',
    [TrafficLight.GREEN]: 'green',
};

TrafficLight.VALUES = Object.keys(TrafficLight.CODES);

TrafficLight.createRed = () => {
    return new TrafficLight(TrafficLight.RED);
};

TrafficLight.createYellow = () => {
    return new TrafficLight(TrafficLight.YELLOW);
};

TrafficLight.createGreen = () => {
    return new TrafficLight(TrafficLight.GREEN);
};

/**
 * @param {object} scheduled
 * @param {object} forecast
 * @param {object} actual
 * @return {TrafficLight}
 */
export function getScheduleForecastTrafficLight(scheduled, forecast, actual) {
    if (!scheduled.finishAt || !forecast.finishAt) {
        return TrafficLight.createGreen();
    }

    let isYellow = moment(forecast.finishAt).isAfter(scheduled.finishAt) ||
        moment(forecast.startAt).isAfter(scheduled.startAt) ||
        (actual.finishAt && moment(forecast.finishAt).isAfter(actual.finishAt));
    if (isYellow) {
        return TrafficLight.createYellow();
    }

    return TrafficLight.createGreen();
}

/**
 * @param {object} base
 * @param {object} forecast
 * @param {object} actual
 * @param {boolean} completed
 * @return {TrafficLight}
 */
export function getScheduleActualTrafficLight(
    base, forecast, actual, completed = true) {
    let today = moment().format('YYYY-MM-DD');

    if (!actual.finishAt && moment(forecast.finishAt).isBefore(today)) {
        return TrafficLight.createRed();
    }

    if (!forecast.finishAt || !actual.finishAt) {
        return TrafficLight.createGreen();
    }

    if (moment(actual.finishAt).isBefore(base.finishAt)
        && moment(actual.finishAt).isAfter(forecast.finishAt)) {
        return TrafficLight.createYellow();
    }

    if (!moment(actual.finishAt).isAfter(forecast.finishAt)) {
        return TrafficLight.createRed();
    }

    if (moment(actual.finishAt).isBefore(forecast.finishAt) && !completed) {
        return TrafficLight.createYellow();
    }

    return TrafficLight.createGreen();
}

export default {
    TrafficLight,
    getScheduleActualTrafficLight,
    getScheduleForecastTrafficLight,
};
