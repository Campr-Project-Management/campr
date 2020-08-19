import moment from 'moment';
export const calendarNotPast = (messageData, data) => {
    const now = new Date();
    let message = null;
    if (data < now) {
        message =messageData +' '+ moment().format('MMM Do YY');
    } else {
        message = '';
    }
    return message;
};
