export const logoUrl = ({defaultLogoUrl}, getters) => {
    let logoUrl = defaultLogoUrl;
    if (getters.team && getters.team.logoUrl) {
        logoUrl = getters.team.logoUrl;
    }

    if (getters.project && getters.project.logoUrl) {
        logoUrl = getters.project.logoUrl;
    }

    return logoUrl;
};
