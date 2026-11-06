/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './template-parts/**/*.php',
    './woocommerce/**/*.php',
    './page-templates/**/*.php',
    './inc/**/*.php',
    './assets/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#e11242',
          light: '#fff1f2',
          dark: '#be123c',
          darker: '#9f1239',
        },
        crimson: {
          50: '#fff1f2',
          100: '#ffe4e6',
          200: '#fecdd3',
          500: '#f43f5e',
          600: '#e11242',
          700: '#be123c',
          800: '#9f1239',
          900: '#881337',
        },
        slate: {
          850: '#151e2e',
          950: '#0a0f1d',
        }
      },
      fontFamily: {
        vazir: ['Vazirmatn', 'sans-serif'],
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
        '4xl': '2rem',
      },
      fontSize: {
        '2xs': '0.6875rem',
        '3xs': '0.625rem',
      }
    },
  },
  plugins: [],
}
