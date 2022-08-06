/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./routes/*.html', './js/*.js'],
  theme: {
    extend: {
      fontFamily: {
        bevietnam: ['Be Vietnam', 'sans-serif'],
      },
      colors: {
        'natalia-blue': {
          400: '#2653F1',
          500: '#1D3BA7',
        },
        'natalia-lightblue': {
          400: '#26C0F1',
        },
        'natalia-gray': {
          300: '#D9D9D9',
          400: '#484848',
        },
      },
    },
    container: {
      center: true,
      padding: '1em',
    },
  },
  plugins: [],
};
