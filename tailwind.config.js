const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
        },
    },
  variants: {},
  plugins: [],
  purge: {
      mode: 'all',
      content: [
          './resources/**/*.php',
          './resources/**/*.js',
          './resources/**/*.vue',
      ],
  }
}
