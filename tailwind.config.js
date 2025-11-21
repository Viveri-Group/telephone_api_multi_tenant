/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    safelist: [
        {pattern: /^(border|bg|text)-(blue|gray|green|orange|red)-(200|300|400|500|600|700|800)$/}
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}

