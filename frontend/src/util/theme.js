export const DARK = 'dark';
export const LIGHT = 'light';
export const DEFAULT_THEME = DARK;

export const themes = Object.freeze({
    light: {
        main: '#edf1f5',
        fade: '#ffffff',
        semiDark: '#dee0e2',
        dark: '#ffffff',
        darker: '#dbdbdb',
        middle: '#646EA0',
        light: '#777777',
        lighter: '#555555',
        lightRed: '#c87369',
        lightYellow: '#ccba54',
        lightGreen: '#5FC3A5',
        red: '#C64444',
        yelow: '#ccba54',
        green: '#197252',
        bg: '#edf1f5',
        fg: '#555555',
    },
    dark: {
        main: '#232D4B',
        fade: '#2E3D60',
        semiDark: '#1E2643',
        dark: '#191E37',
        darker: '#111526',
        middle: '#646EA0',
        light: '#8794C4',
        lighter: '#D8DAE5',
        lightRed: '#c87369',
        lightYellow: '#ccba54',
        lightGreen: '#5FC3A5',
        red: '#C64444',
        yelow: '#ccba54',
        green: '#197252',
        bg: '#232D4B',
        fg: '#D8DAE5',
    },
});

/**
 * Returns current theme
 * @return {object}
 */
export default function currentTheme() {
    if (!process.browser) {
        return themes[DEFAULT_THEME];
    }

    let theme = DEFAULT_THEME;
    if (window && window.user && window.user.theme) {
        theme = window.user.theme;
    }

    if (!themes[theme]) {
        theme = DEFAULT_THEME;
    }

    return themes[theme];
}
