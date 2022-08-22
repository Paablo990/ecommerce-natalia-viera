const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./view/*.html', '.controller/js/*.js'],
  theme: {
    extend: {
      colors: {
        'natalia-blue': {
          300: '#26C0F1',
          400: '#2653F1',
          500: '#1D3BA7',
        },
      },
    },
    container: {
      padding: '1em',
    },
  },
  plugins: [forms],
};
