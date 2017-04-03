export const FIRST_STEP_LOCALSTORAGE_KEY = 'projectCreateFirstStepData';
export const SECOND_STEP_LOCALSTORAGE_KEY = 'projectCreateSecondStepData';
export const THIRD_STEP_LOCALSTORAGE_KEY = 'projectCreateThirdStepData';

export const processProjectModules = (thirdStepData) => {
    let projectModules = [];
    projectModules.push({
        'module': 'project_contract',
        'isEnabled': thirdStepData.projectContract,
    });
    projectModules.push({
        'module': 'project_organization',
        'isEnabled': thirdStepData.projectOrganization,
    });
    projectModules.push({
        'module': 'plan',
        'isEnabled': thirdStepData.plan,
    });
    projectModules.push({
        'module': 'task_management',
        'isEnabled': thirdStepData.taskManagement,
    });
    projectModules.push({
        'module': 'phases_milestones',
        'isEnabled': thirdStepData.phasesMilestones,
    });
    projectModules.push({
        'module': 'costs',
        'isEnabled': thirdStepData.costs,
    });
    projectModules.push({
        'module': 'resources',
        'isEnabled': thirdStepData.resources,
    });
    projectModules.push({
        'module': 'risks_opportunities',
        'isEnabled': thirdStepData.risksOportunities,
    });
    projectModules.push({
        'module': 'communication',
        'isEnabled': thirdStepData.communication,
    });
    projectModules.push({
        'module': 'control_measures',
        'isEnabled': thirdStepData.controlMeasures,
    });
    projectModules.push({
        'module': 'status_report',
        'isEnabled': thirdStepData.statusReport,
    });
    projectModules.push({
        'module': 'meetings',
        'isEnabled': thirdStepData.meetings,
    });
    projectModules.push({
        'module': 'notes',
        'isEnabled': thirdStepData.notes,
    });
    projectModules.push({
        'module': 'todos',
        'isEnabled': thirdStepData.todos,
    });
    projectModules.push({
        'module': 'close_down_project',
        'isEnabled': thirdStepData.closeDownProject,
    });
    projectModules.push({
        'module': 'raci_matrix',
        'isEnabled': thirdStepData.raciMatrix,
    });
    projectModules.push({
        'module': 'task_chart',
        'isEnabled': thirdStepData.taskChart,
    });
    projectModules.push({
        'module': 'gantt_chart',
        'isEnabled': thirdStepData.ganttChart,
    });
    projectModules.push({
        'module': 'context',
        'isEnabled': thirdStepData.context,
    });
    projectModules.push({
        'module': 'decisions',
        'isEnabled': thirdStepData.decisions,
    });

    return projectModules;
};

export const processProjectConfiguration = (secondStepData) => {
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
