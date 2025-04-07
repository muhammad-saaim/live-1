/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        "./node_modules/flowbite/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                sky: require('tailwindcss/colors').sky,
                stone: require('tailwindcss/colors').stone,
                neutral: require('tailwindcss/colors').neutral,
                gray: require('tailwindcss/colors').gray,
                slate: require('tailwindcss/colors').slate,
                'ml-color': {
                    lime: '#EBF2D8',
                    green: '#8CB368',
                    sega: '#C2C49E',
                    orange: '#F37028',
                    sunset: '#FFBE55',
                    sky: '#3A9EE6',
                    gray: '#808080',
                },
            },
        },
        // Include all default colors
        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            white: '#fff',
            black: '#000',
            ...require('tailwindcss/colors'),
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
};
