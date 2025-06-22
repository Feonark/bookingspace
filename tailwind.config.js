module.exports = {
    content: [
        './templates/**/*.html.twig',
        './assets/**/*.js',
    ],
    safelist: [
        /^fc-/, // Pour préserver les classes FullCalendar (fc-button, fc-daygrid, etc.)
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
