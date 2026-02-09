/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./storage/framework/views/*.php",
    ],
    theme: {
      extend: {
        colors: {
          'primary': '#3b82f6',
          'secondary': '#10b981',
          'danger': '#ef4444',
        },

        fontFamily: {
          'sans': ['Inter', 'sans-serif'],
        },
      },
    },
    plugins: [],
  }