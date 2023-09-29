/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
        sh_font_head:["Cormorant Garamond", "serif"],
        sh_font_para:["Proza Libre", "sans-serif"]
    },
    colors: {
      sh_dark_blue: '#040a35',
      sh_darker_blue: '#01093d',
      sh_yellow:'#FDC442'
  },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}

