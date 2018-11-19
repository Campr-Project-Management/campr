var themes = ['dark', 'light'];

var theme = themes.indexOf(process.env.THEME) === -1 ? themes[0] : themes[themes.indexOf(process.env.THEME)];

module.exports = function () {
    return theme;
};
