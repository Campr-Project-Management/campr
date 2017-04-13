export const createFormData = (data) => {
    let formData = new FormData();

    // Basic Data
    formData.append('name', data.name);
    formData.append('type', data.type);
    formData.append('content', data.description);
    formData.append('labels[0]', data.details.label);
    formData.append('parent', data.planning.milestone);
    formData.append('responsibility', data.details.assignee);
    formData.append('colorStatus', data.statusColor);

    // Attachments
    if (data.attachments.length) {
        for (let i = 0; i < data.attachments.length; i++) {
            formData.append('medias[' + i + '][file]', attachment);
        }
    }

    // External Costs
    for (let i = 0; i < data.externalCosts.items; i++) {
        formData.append('costs[' + i + '][name]', data.externalCosts.items[i].description);
        formData.append('costs[' + i + '][quantity]', data.externalCosts.items[i].qty);
        formData.append('costs[' + i + '][rate]', data.externalCosts.items[i].unitRate);
        formData.append('costs[' + i + '][expenseType]', data.externalCosts.items[i].capex ? 0 : 1);
        formData.append('costs[' + i + '][type]', 1);
        formData.append('costs[' + i + '][customUnit]', data.externalCosts.items[i].customUnit);
        formData.append('costs[' + i + '][unit]', data.externalCosts.items[i].unit);
    }

    // Internal Costs
    for (let i = 0; i < data.internalCosts.items; i++) {
        formData.append('costs[' + i + '][resource]', data.internalCosts.items[i].resource.id);
        formData.append('costs[' + i + '][quantity]', data.externalCosts.items[i].qty);
        formData.append('costs[' + i + '][duration]', data.externalCosts.items[i].days);
        formData.append('costs[' + i + '][rate]', data.externalCosts.items[i].resource.rate);
        formData.append('costs[' + i + '][type]', 0);
    }

    // Schedule Data
    formData.append('scheduledStartAt', data.schedule.baseStartDate);
    formData.append('scheduledFinishAt', data.schedule.baseEndDate);
    formData.append('forecastStartAt', data.schedule.forecastStartDate);
    formData.append('forecastFinishAt', data.planning.forecastEndDate);
    data.schedule.successors.map(successor => formData.append('dependants[]', successor));
    data.schedule.predecessors.map(predecessor => formData.append('dependencies[]', predecessor));

    // Subtasks
    for (let i = 0; i < data.subtasks.length; i++) {
        formData.append('children[' + i + '][name]', data.subtasks[i].description);
        formData.append('children[' + i + '][type]', 2);
    }

    return formData;
};
