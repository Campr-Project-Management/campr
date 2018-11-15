export const createFormData = (data) => {
    let formData = new FormData();

    // Basic Data
    if (data.title != null) {
        formData.append('title', data.title);
    }

    if (data.description != null) {
        formData.append('description', data.description);
    }

    if (data.dueDate != null) {
        formData.append('dueDate', data.dueDate);
    }

    if (data.project != null) {
        formData.append('project', data.project);
    }

    if (data.distributionList != null) {
        formData.append('distributionList', data.distributionList);
    }

    if (data.done != null) {
        formData.append('done', data.done ? 1 : 0);
    }

    if (data.responsibility != null) {
        formData.append('responsibility', data.responsibility);
    }

    if (data.decisionCategory != null) {
        formData.append('decisionCategory', data.decisionCategory);
    }

    // Attachments
    if (data.medias && data.medias.length) {
        data.medias.forEach((media, index) => {
            if (!media) {
                return;
            }

            formData.append('medias[' + index + ']', media.id);
        });
    }

    return formData;
};

export const createFormDataDecision = (data) => {
    let formData = new FormData();

    // Basic Data
    if (data.title != null) {
        formData.append('title', data.title);
    }

    if (data.description != null) {
        formData.append('description', data.description);
    }

    if (data.dueDate != null) {
        formData.append('dueDate', data.dueDate);
    }

    if (data.project != null) {
        formData.append('project', data.project);
    }

    if (data.meeting != null) {
        formData.append('meeting', data.meeting);
    }

    if (data.done != null) {
        formData.append('done', data.done);
    }

    if (data.responsibility != null) {
        formData.append('responsibility', data.responsibility);
    }

    if (data.decisionCategory != null) {
        formData.append('decisionCategory', data.decisionCategory);
    }

    if (data.distributionList != null) {
        formData.append('distributionList', data.distributionList);
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

    return formData;
};
