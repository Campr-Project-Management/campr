// https://msdn.microsoft.com/en-us/library/office/aa220054(v=office.11).aspx
const dateRegExp = /^(\d{4}\-\d{1,2}\-\d{1,2})/;

const extractors = {
    string(el) {
        return el.textContent;
    },
    number(el) {
        return parseInt(el.textContent, 10);
    },
    date(el) {
        const matches = el.textContent.match(dateRegExp);

        if (!matches) {
            return '';
        }

        return matches[0];
    },
};

const xml2objectMapping = {
    uid: {
        to: 'externalId',
        extractor: extractors.number,
    },
    name: {
        to: 'name',
        extractor: extractors.string,
    },
    notes: {
        to: 'content',
        extractor: extractors.string,
    },
    start: {
        to: 'scheduledStartAt',
        extractor: extractors.date,
    },
    finish: {
        to: 'scheduledFinishAt',
        extractor: extractors.date,
    },
    actualstart: {
        to: 'actualStartAt',
        extractor: extractors.date,
    },
    actualfinish: {
        to: 'actualFinishAt',
        extractor: extractors.date,
    },
    cost: {
        to: 'internalForecastCost', // forecast
        extractor: extractors.number,
    },
    actualcost: {
        to: 'internalActualCost',
        extractor: extractors.number,
    },
};

/**
 * This function will take a XML string and return an usable workpackage that can be sent to
 * the backend for processing or that can be used in a store to import in the UI directly.
 *
 * @param {string} xmlString
 * @return {Object}
 */
export function xml2WorkPackage(xmlString) {
    const parser = new DOMParser();
    const document = parser.parseFromString(xmlString, 'application/xml');
    const taskElement = document.documentElement;
    const task = {};

    if (!taskElement || !taskElement.nodeName || taskElement.nodeName.toLowerCase() !== 'task') {
        return task;
    }

    let c = 0;
    for (; c < taskElement.children.length; c++) {
        let node = taskElement.children[c];
        let nodeName = node.nodeName.toLowerCase();
        let mapping = xml2objectMapping[nodeName];

        if (!mapping) {
            continue;
        }

        task[mapping.to] = mapping.extractor(node);
    }

    return task;
}
