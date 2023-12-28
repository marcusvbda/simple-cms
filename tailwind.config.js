/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './vendor/marcusvbda/vstack/**/*.vue',
        './vendor/marcusvbda/vstack/**/*.blade.php',
    ],
    theme: {
        extend: {
            // colors: {
            //     blue: {
            //         700: '#D61920',
            //     },
            // },
        },
    },
    plugins: [],
};
