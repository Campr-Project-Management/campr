export const FIRST_STEP_LOCALSTORAGE_KEY = 'projectCreateFirstStepData';
export const SECOND_STEP_LOCALSTORAGE_KEY = 'projectCreateSecondStepData';
export const THIRD_STEP_LOCALSTORAGE_KEY = 'projectCreateThirdStepData';

export const processProjectModules = (thirdStepData) => {
    let projectModules = [];

    for (let key in thirdStepData.modulesConfiguration) {
        if (thirdStepData.modulesConfiguration.hasOwnProperty(key)) {
            projectModules.push({
                'module': key,
                'isEnabled': thirdStepData.modulesConfiguration[key],
            });
        }
    }
    return projectModules;
};

export const processProjectConfiguration = (secondStepData) => {
    if (!secondStepData) {
        return {
            'projectDuration': 0,
            'projectBudget': 0,
            'projectInvolved': 0,
            'departmentsInvolved': 0,
            'strategicalMeaning': 0,
            'risks': 0,
        };
    }

    return {
        'projectDuration': secondStepData.projectDuration,
        'projectBudget': secondStepData.projectBudget,
        'projectInvolved': secondStepData.projectInvolved,
        'departmentsInvolved': secondStepData.departmentsInvolved,
        'strategicalMeaning': secondStepData.strategicalMeaning,
        'risks': secondStepData.risks,
    };
};

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
