/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: ['./templates/**/*.twig'],
  theme: {
    extend: {
      fontFamily: {
        sans: ["Satoshi-Variable", ...defaultTheme.fontFamily.sans],
      },
      gridTemplateRows: {
        'md': '48px repeat(7, minmax(0, 1fr))'
      },
      gridTemplateColumns: {
        'md': 'repeat(12, minmax(0, 1fr))',
        'xl': 'repeat(16, minmax(0, 1fr))'
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}

