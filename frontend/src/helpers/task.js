import moment from 'moment';

export const createFormData = (data) => {
    let formData = new FormData();

    // Basic Data
    formData.append('name', data.name);
    formData.append('type', data.type);
    formData.append('project', data.project);
    formData.append('content', data.description);
    if (data.planning.phase) {
        formData.append('phase', data.planning.phase.key);
    }
    if (data.planning.milestone) {
        formData.append('milestone', data.planning.milestone.key);
    }
    if (data.details.assignee) {
        formData.append('responsibility', data.details.assignee.key);
    }
    if (data.details.accountable) {
        formData.append('accountability', data.details.accountable.key);
    }
    if (data.details.supportUsers) {
        data.details.supportUsers.map(user => formData.append('supportUsers[]', user.key));
    }
    if (data.details.consultedUsers) {
        data.details.consultedUsers.map(user => formData.append('consultedUsers[]', user.key));
    }
    if (data.details.informedUsers) {
        data.details.informedUsers.map(user => formData.append('informedUsers[]', user.key));
    }
    if (data.statusColor) {
        formData.append('colorStatus', data.statusColor.id);
    }

    // Attachments
    if (data.medias.length) {
        for (let i = 0; i < data.medias.length; i++) {
            formData.append(
                'medias[' + i + '][file]',
                data.medias[i] instanceof window.File
                    ? data.medias[i]
                    : ''
            );
        }
    }

    // External Costs
    for (let i = 0; i < data.externalCosts.items.length; i++) {
        formData.append('costs[' + i + '][name]', data.externalCosts.items[i].name || '');
        formData.append('costs[' + i + '][quantity]', data.externalCosts.items[i].quantity);
        formData.append('costs[' + i + '][rate]', data.externalCosts.items[i].rate);
        formData.append('costs[' + i + '][expenseType]', data.externalCosts.items[i].capex ? 0 : 1);
        formData.append('costs[' + i + '][type]', 1);
        if (data.externalCosts.items[i].customUnit && data.externalCosts.items[i].customUnit.length) {
            formData.append('costs[' + i + '][customUnit]', data.externalCosts.items[i].customUnit);
        } else if (data.externalCosts.items.unit) {
            formData.append(
                'costs[' + i + '][unit]',
                data.externalCosts.items[i].selectedUnit || data.externalCosts.items[i].unit.id
            );
        }
        if (data.externalCosts.items[i].project) {
            formData.append('costs[' + i + '][project]', data.externalCosts.items[i].project);
        }
        if (data.externalCosts.items[i].workPackage) {
            formData.append('costs[' + i + '][workPackage]', data.externalCosts.items[i].workPackage);
        }
    }
    formData.append('externalActualCost', data.externalCosts.actual);
    formData.append('externalForecastCost', data.externalCosts.forecast);

    // Internal Costs
    for (let i = 0; i < data.internalCosts.items.length; i++) {
        let costIndex = i + data.externalCosts.items.length;
        formData.append('costs[' + costIndex + '][resource]', data.internalCosts.items[i].resource.key);
        formData.append('costs[' + costIndex + '][quantity]', data.internalCosts.items[i].quantity);
        formData.append('costs[' + costIndex + '][duration]', data.internalCosts.items[i].duration);
        formData.append('costs[' + costIndex + '][rate]', data.internalCosts.items[i].rate);
        formData.append('costs[' + costIndex + '][type]', 0);
        if (data.internalCosts.items[i].project) {
            formData.append('costs[' + costIndex + '][project]', data.internalCosts.items[i].project);
        }
        if (data.internalCosts.items[i].workPackage) {
            formData.append('costs[' + costIndex + '][workPackage]', data.internalCosts.items[i].workPackage);
        }
    }
    formData.append('internalActualCost', data.internalCosts.actual);
    formData.append('internalForecastCost', data.internalCosts.forecast);

    // Schedule Data
    formData.append('scheduledStartAt', moment(data.schedule.baseStartDate).format('DD-MM-YYYY'));
    formData.append('scheduledFinishAt', moment(data.schedule.baseEndDate).format('DD-MM-YYYY'));
    formData.append('forecastStartAt', moment(data.schedule.forecastStartDate).format('DD-MM-YYYY'));
    formData.append('forecastFinishAt', moment(data.schedule.forecastEndDate).format('DD-MM-YYYY'));
    data.schedule.successors.map(successor => formData.append('dependants[]', successor.key));
    data.schedule.predecessors.map(predecessor => formData.append('dependencies[]', predecessor.key));
    formData.append('duration', data.schedule.durationInDays);
    formData.append('automaticSchedule', data.schedule.automatic);

    // Subtasks
    for (let i = 0; i < data.subtasks.length; i++) {
        formData.append('children[' + i + '][name]', data.subtasks[i].description);
        formData.append('children[' + i + '][type]', 2);
    }

    if (data.details.label) {
        formData.append('labels[]', data.details.label.key);
    }
    if (data.details.assignee) {
        formData.append('responsibility', data.details.assignee.key);
    }
    if (data.details.status) {
        formData.append('workPackageStatus', data.details.status.key);
    }
    // formData.append('label', data.details.label ? data.details.label.key : '');

    return formData;
};
