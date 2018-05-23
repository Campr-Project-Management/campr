import moment from 'moment';

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
     * @return {int}
     */
    getValue() {
        return this.value;
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
 * @return {TrafficLight}
 */
export function getScheduleForecastTrafficLight(scheduled, forecast) {
    if (!scheduled.finishAt || !forecast.finishAt) {
        return TrafficLight.createGreen();
    }

    if (moment(forecast.finishAt).isAfter(scheduled.finishAt)) {
        return TrafficLight.createYellow();
    }

    return TrafficLight.createGreen();
}

/**
 * @param {object} forecast
 * @param {object} actual
 * @param {boolean} completed
 * @return {TrafficLight}
 */
export function getScheduleActualTrafficLight(
    forecast, actual, completed = true) {
    if (!forecast.finishAt || !actual.finishAt) {
        return TrafficLight.createGreen();
    }

    if (moment(actual.finishAt).isAfter(forecast.finishAt)) {
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
