export const calendarNotPast = (messageData, data) => {
    const now = new Date();
    let message = null;
    if (data < now) {
        message = messageData;
    } else {
        message = '';
    }
    return message;
};
