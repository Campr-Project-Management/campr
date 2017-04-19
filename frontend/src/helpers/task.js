import moment from 'moment';

export const createFormData = (data) => {
    let formData = new FormData();

    // Basic Data
    formData.append('name', data.name);
    formData.append('type', data.type);
    formData.append('project', data.project);
    formData.append('content', data.description);
    if (data.details.label) {
        formData.append('labels[]', data.details.label.key);
    }
    if (data.planning.phase) {
        formData.append('parent', data.planning.phase.key);
    }
    if (data.details.assignee) {
        formData.append('responsibility', data.details.assignee.key);
    }
    if (data.statusColor) {
        formData.append('colorStatus', data.statusColor.id);
    }

    // Attachments
    if (data.attachments.length) {
        for (let i = 0; i < data.attachments.length; i++) {
            formData.append('medias[' + i + '][file]', data.attachments[i]);
        }
    }

    // External Costs
    for (let i = 0; i < data.externalCosts.items.length; i++) {
        formData.append('costs[' + i + '][name]', data.externalCosts.items[i].description);
        formData.append('costs[' + i + '][quantity]', data.externalCosts.items[i].qty);
        formData.append('costs[' + i + '][rate]', data.externalCosts.items[i].unitRate);
        formData.append('costs[' + i + '][expenseType]', data.externalCosts.items[i].capex ? 0 : 1);
        formData.append('costs[' + i + '][type]', 1);
        if (data.externalCosts.items[i].customUnit.length) {
            formData.append('costs[' + i + '][customUnit]', data.externalCosts.items[i].customUnit);
        } else {
            formData.append('costs[' + i + '][unit]', data.externalCosts.items[i].unit);
        }
    }

    // Internal Costs
    for (let i = 0; i < data.internalCosts.items.length; i++) {
        let costIndex = i + data.externalCosts.items.length;
        formData.append('costs[' + costIndex + '][resource]', data.internalCosts.items[i].resource.key);
        formData.append('costs[' + costIndex + '][quantity]', data.internalCosts.items[i].qty);
        formData.append('costs[' + costIndex + '][duration]', data.internalCosts.items[i].days);
        formData.append('costs[' + costIndex + '][rate]', data.internalCosts.items[i].rate);
        formData.append('costs[' + costIndex + '][type]', 0);
    }

    // Schedule Data
    formData.append('scheduledStartAt', moment(data.schedule.baseStartDate).format('DD-MM-YYYY'));
    formData.append('scheduledFinishAt', moment(data.schedule.baseEndDate).format('DD-MM-YYYY'));
    formData.append('forecastStartAt', moment(data.schedule.forecastStartDate).format('DD-MM-YYYY'));
    formData.append('forecastFinishAt', moment(data.planning.forecastEndDate).format('DD-MM-YYYY'));
    data.schedule.successors.map(successor => formData.append('dependants[]', successor.key));
    data.schedule.predecessors.map(predecessor => formData.append('dependencies[]', predecessor.key));

    // Subtasks
    for (let i = 0; i < data.subtasks.length; i++) {
        formData.append('children[' + i + '][name]', data.subtasks[i].description);
        formData.append('children[' + i + '][type]', 2);
    }

    return formData;
};
