module.exports = {
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
  theme: {
    extend: {},
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
