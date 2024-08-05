/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        "black": "#060606"

      },
      fontFamily: {
        "hanken-grotesk": ["hanken-grotesk", "Sans-serif"]
    },
    fontSize: {
      "2xs":"10px"

  },
  plugins: [],
}

}
}