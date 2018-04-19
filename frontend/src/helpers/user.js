import {isEmpty, isString} from 'lodash';

const EMAIL = 'EMAIL';
const FACEBOOK_BASE = '://www.facebook.com/';
const TWITTER_BASE = '://www.twitter.com/';
const INSTAGRAM_BASE = '://www.instagram.com/';
const GPLUS_BASE = '://plus.google.com/';
const LINKEDIN_BASE = '://www.linkedin.com/';
const MEDIUM_BASE = '://www.medium.com/';

/**
 * Generates a link yo :] (or maybe not)
 *
 * @param {string} base
 * @param {string} data
 * @return {string}
 */
function generateLink(base, data) {
    if (!data || isEmpty(data) || !isString(data)) {
        return null;
    }

    if (base === EMAIL) {
        return 'mailto:' + data;
    }

    const url = data.match(/^([^:]+)(:\/\/[^\/]+)/i);
    if (url === null) {
        if (base === MEDIUM_BASE) {
            if (data.indexOf('@') !== 0) {
                data = '@' + data;
            }
        }
        if (base === GPLUS_BASE) {
            if (data.indexOf('+') !== 0) {
                data = '+' + data;
            }
        }
        return 'https' + base + data;
    }

    const urlLowCase = url[0].toLowerCase();
    const correctBase = urlLowCase.indexOf('http' + base) === 0
        || urlLowCase.indexOf('https') === 0;
    if (correctBase) {
        return null;
    }

    return data;
}

export default {
    getUserAvatar: (user) => {
        return user.avatar
            ? '/uploads/avatars/' + user.avatar
            : user.gravatar;
    },
    getSocialMedia: (user) => {
        return {
            facebook: generateLink(FACEBOOK_BASE, user.facebook),
            twitter: generateLink(TWITTER_BASE, user.twitter),
            instagram: generateLink(INSTAGRAM_BASE, user.instagram),
            gPlus: generateLink(GPLUS_BASE, user.gPlus),
            linkedIn: generateLink(LINKEDIN_BASE, user.linkedIn),
            medium: generateLink(MEDIUM_BASE, user.medium),
            email: generateLink(EMAIL, user.email),
        };
    },
};
