/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.js',
    './templates/**/*.html.twig',
    './node_modules/tw-elements/js/**/*.js',
  ],
  theme: {
    fontFamily: {
      inter: ['Inter', 'ui-sans-serif'],
      roboto: ['Roboto', 'ui-sans-serif'],
      // mulish: ['Mulish', 'ui-sans-serif'],
    },
    extend: {
      colors: {
        primary: '#C34795',
        secondary: '#f1c40f',
        gray: '#989898',
        black: '#161616',
        white: '#fcfcfc',
        red: '#e51515',
        bg1: '#fef5ea',
        bg2: 'rgba(182, 222, 160, 0.14)',
        bg3: '#fef9f9',
      },
      keyframes: {
        slideIn: {
          '0%': { transform: 'translateX(-100%)' },
          '100%': { transform: 'translateX(0)' },
        },
        slideOut: {
          '0%': { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-100%)' },
        },
      },
      animation: {
        'slide-in': 'slideIn 0.3s ease-out forwards',
        'slide-out': 'slideOut 0.3s ease-out forwards',
      },
      screens: {
        sm: '576px',
        md: '768px',
        lg: '992px',
        xl: '1200px',
        '2xl': '1400px',
      },
    },
  },
  plugins: [require('tw-elements/plugin', 'tailwindcss-scoped-groups')],
}
