import moment from 'moment';

export const createFormData = (data) => {
    let formData = new FormData();

    // Basic Data
    if (data.name != null) {
        formData.append('name', data.name);
    }

    if (data.type != null) {
        formData.append('type', data.type);
    }

    if (data.project != null) {
        formData.append('project', data.project);
    }

    if (data.description != null) {
        formData.append('content', data.description);
    }

    if (data.planning) {
        if (data.planning.phase) {
            formData.append('phase', data.planning.phase.key);
        }
        if (data.planning.milestone) {
            formData.append('milestone', data.planning.milestone.key);
        }
        if (data.planning.parent) {
            formData.append('parent', data.planning.parent.key);
        }
    }

    if (data.assignments) {
        if (data.assignments.responsibility) {
            formData.append('responsibility', data.assignments.responsibility.key);
        }

        if (data.assignments.accountability ) {
            formData.append('accountability', data.assignments.accountability.key);
        }

        data.assignments.supportUsers.forEach(
            (o) => formData.append('supportUsers[]', o.key));
        data.assignments.consultedUsers.forEach(
            (o) => formData.append('consultedUsers[]', o.key));
        data.assignments.informedUsers.forEach(
            (o) => formData.append('informedUsers[]', o.key));
    }

    if (data.statusColor) {
        formData.append('colorStatus', data.statusColor.id);
    }

    if (data.parent) {
        formData.append('parent', data.parent);
    }

    // Attachments
    if (data.medias && data.medias.length) {
        data.medias.forEach((media, index) => {
            if (!media) {
                return;
            }

            formData.append('medias[' + index + '][file]',
                media instanceof window.File ? media : '');
        });
    }

    if (data.externalCosts) {
        data.externalCosts.items.forEach((item, i) => {
            formData.append('costs[' + i + '][name]', item.name || '');
            formData.append('costs[' + i + '][quantity]', item.quantity);
            formData.append('costs[' + i + '][rate]', item.rate);
            formData.append('costs[' + i + '][expenseType]', item.expenseType);
            formData.append('costs[' + i + '][type]', 1);
            if (item.customUnit && item.customUnit.length) {
                formData.append('costs[' + i + '][customUnit]', item.customUnit);
            } else if (item.unit) {
                let unit = item.unit;
                if (typeof unit === 'object') {
                    unit = unit.id;
                }

                formData.append('costs[' + i + '][unit]', unit);
            }
        });

        if (data.externalCosts.actual) {
            formData.append('externalActualCost', data.externalCosts.actual);
        }

        if (data.externalCosts.forecast) {
            formData.append('externalForecastCost', data.externalCosts.forecast);
        }
    }

    if (data.internalCosts) {
        data.internalCosts.items.forEach((item, i) => {
            let costIndex = i + data.externalCosts.items.length;
            formData.append('costs[' + costIndex + '][resource]', item.resource.key);
            formData.append('costs[' + costIndex + '][quantity]', item.quantity);
            formData.append('costs[' + costIndex + '][duration]', item.duration);
            formData.append('costs[' + costIndex + '][rate]', item.rate);
            formData.append('costs[' + costIndex + '][type]', 0);
        });

        if (data.internalCosts.actual) {
            formData.append('internalActualCost', data.internalCosts.actual);
        }

        if (data.internalCosts.forecast) {
            formData.append('internalForecastCost', data.internalCosts.forecast);
        }
    }

    if (data.schedule) {
        // Schedule Data
        if (data.schedule.baseStartDate) {
            formData.append('scheduledStartAt', moment(data.schedule.baseStartDate).format('DD-MM-YYYY'));
        }

        if (data.schedule.baseEndDate) {
            formData.append('scheduledFinishAt', moment(data.schedule.baseEndDate).format('DD-MM-YYYY'));
        }

        if (data.schedule.forecastStartDate) {
            formData.append('forecastStartAt', moment(data.schedule.forecastStartDate).format('DD-MM-YYYY'));
        }

        if (data.schedule.forecastEndDate) {
            formData.append('forecastFinishAt', moment(data.schedule.forecastEndDate).format('DD-MM-YYYY'));
        }

        if (data.schedule.duration) {
            formData.append('duration', data.schedule.duration);
        }
    }

    if (data.subtasks) {
        // Subtasks
        for (let i = 0; i < data.subtasks.length; i++) {
            formData.append('children[' + i + '][name]', data.subtasks[i].description);
            formData.append('children[' + i + '][type]', 2);
        }
    }

    if (data.details) {
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
    }

    return formData;
};
