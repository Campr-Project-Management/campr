import _ from 'lodash';

export const convertImageToBlog = (dataURI) => {
    let byteString;
    let mimestring;

    if(dataURI.split(',')[0].indexOf('base64') !== -1) {
        byteString = atob(dataURI.split(',')[1]);
    } else {
        byteString = decodeURI(dataURI.split(',')[1]);
    }

    mimestring = dataURI.split(',')[0].split(':')[1].split(';')[0];

    let content = [];
    for (let i = 0; i < byteString.length; i++) {
        content[i] = byteString.charCodeAt(i);
    }

    return new Blob([new Uint8Array(content)], {type: mimestring});
};

export const projectHasValidContract = (project) => {
    if (typeof project !== 'object') {
        return false;
    }

    if (-1 === Object.keys(project).indexOf('contracts')) {
        return false;
    }

    if (!_.isArray(project.contracts)) {
        return false;
    }

    const contract = _.last(project.contracts);

    return contract.frozen && null !== contract.approvedAt;
};
