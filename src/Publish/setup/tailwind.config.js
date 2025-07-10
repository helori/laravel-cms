import defaultTheme from 'tailwindcss/defaultTheme'
import colors from 'tailwindcss/colors'

export default {
    theme: {
        colors: {
            // Build your palette here
            transparent: 'transparent',
            current: 'currentColor',
            primary: colors.indigo,

            black: colors.black,
            white: colors.white,
            gray: colors.gray,
            green: colors.green,
            yellow: colors.yellow,
            red: colors.red,
            blue: colors.blue,

            /*teal: colors.teal,
            lime: colors.lime,
            cyan: colors.cyan,
            sky: colors.sky,
            emerald: colors.emerald,
            yellow: colors.amber,
            indigo: colors.indigo,
            violet: colors.violet,
            fuchsia: colors.fuchsia,
            pink: colors.pink,
            rose: colors.rose,
            slate: colors.slate,*/
        },
        extend: {
            fontFamily: {
                sans: ['Quicksand', 'Work Sans', 'Open Sans', 'Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
        }
    },
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.vue',
        './app/Cms/**/*.php',
        './node_modules/helorui/**/*.vue',
        './vendor/helori/laravel-cms/**/*.blade.php'
    ],
    safelist: [

    ],
    darkMode: 'class', // or 'media' or 'class'
    variants: {
        extend: {
            display: ['dark']
        },
    },
    plugins: [

    ]
}
