import moment from 'moment';

export const createFormData = (data) => {
    let formData = new FormData();

    if (data.name) {
        formData.append('name', data.name);
    }

    if (data.meetingCategory) {
        formData.append('meetingCategory', data.meetingCategory ? data.meetingCategory.key : null);
    }

    if (data.date) {
        formData.append('date', moment(data.date).format('DD-MM-YYYY'));
    }

    if (data.start) {
        formData.append('start', data.start.HH + ':' + data.start.mm);
    }

    if (data.end) {
        formData.append('end', data.end.HH + ':' + data.end.mm);
    }

    if (data.location) {
        formData.append('location', data.location);
    }

    if (data.distributionLists && data.distributionLists.length) {
        for (let i = 0; i < data.distributionLists.length; i++) {
            formData.append('distributionLists[' + i + ']', data.distributionLists[i] ? data.distributionLists[i].key : null);
        }
    }

    if (data.objectives) {
        for (let i = 0; i < data.objectives.length; i++) {
            formData.append('meetingObjectives[' + i + '][description]', data.objectives[i].description);
        }
    }

    if (data.agendas) {
        for (let i = 0; i < data.agendas.length; i++) {
            formData.append('meetingAgendas[' + i + '][topic]', data.agendas[i].topic);
            formData.append('meetingAgendas[' + i + '][responsibility]', data.agendas[i].responsible.length > 0 ? data.agendas[i].responsible[0] : null);
            formData.append('meetingAgendas[' + i + '][start]', data.agendas[i].startTime.HH + ':' + data.agendas[i].startTime.mm);
            formData.append('meetingAgendas[' + i + '][end]', data.agendas[i].endTime.HH + ':' + data.agendas[i].endTime.mm);
        }
    }

    if (data.medias) {
        for (let i = 0; i < data.medias.length; i++) {
            formData.append(
                'medias[' + i + '][file]',
                data.medias[i] instanceof window.File
                    ? data.medias[i]
                    : ''
            );
        }
    }

    if (data.decisions) {
        for (let i = 0; i < data.decisions.length; i++) {
            formData.append('decisions[' + i + '][title]', data.decisions[i].title);
            formData.append('decisions[' + i + '][description]', data.decisions[i].description);
            formData.append('decisions[' + i + '][responsibility]', data.decisions[i].responsible.length > 0 ? data.decisions[i].responsible[0] : null);
            formData.append('decisions[' + i + '][dueDate]', moment(data.decisions[i].dueDate).format('DD-MM-YYYY'));
            formData.append('decisions[' + i + '][status]', data.decisions[i].status ? data.decisions[i].status.key : null);
        }
    }

    if (data.todos) {
        for (let i = 0; i < data.todos.length; i++) {
            formData.append('todos[' + i + '][title]', data.todos[i].title);
            formData.append('todos[' + i + '][description]', data.todos[i].description);
            formData.append('todos[' + i + '][responsibility]', data.todos[i].responsible.length > 0 ? data.todos[i].responsible[0] : null);
            formData.append('todos[' + i + '][dueDate]', moment(data.todos[i].dueDate).format('DD-MM-YYYY'));
            formData.append('todos[' + i + '][status]', data.todos[i].status ? data.todos[i].status.key : null);
        }
    }

    if (data.infos) {
        for (let i = 0; i < data.infos.length; i++) {
            formData.append('infos[' + i + '][topic]', data.infos[i].topic);
            formData.append('infos[' + i + '][description]', data.infos[i].description);
            formData.append('infos[' + i + '][responsibility]', data.infos[i].responsible.length > 0 ? data.infos[i].responsible[0] : null);
            formData.append('infos[' + i + '][dueDate]', moment(data.infos[i].dueDate).format('DD-MM-YYYY'));
            formData.append('infos[' + i + '][infoStatus]', data.infos[i].infoStatus ? data.infos[i].infoStatus.key : null);
            formData.append('infos[' + i + '][infoCategory]', data.infos[i].infoCategory ? data.infos[i].infoCategory.key : null);
        }
    }

    if (data.meetingParticipants) {
        for (let i = 0; i < data.meetingParticipants.length; i++) {
            formData.append('meetingParticipants[' + i + '][user]', data.meetingParticipants[i].user);
            formData.append('meetingParticipants[' + i + '][isPresent]', data.meetingParticipants[i].isPresent ? 1 : 'false');
            formData.append('meetingParticipants[' + i + '][inDistributionList]', data.meetingParticipants[i].inDistributionList ? 1 : 'false');
        }
    }

    return formData;
};
