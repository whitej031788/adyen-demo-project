const path = require('path');

module.exports = {
  mode: 'development',
  //input
  entry: './resources/js/app.js',

  //output
  output: {
    path: path.join(__dirname, './public/js'),
    filename: 'app.js'
  },

  //transformations
  module: {
    rules: [
      {
        test: /\.jsx?/i,
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env'],
          plugins: [
              ["@babel/plugin-transform-react-jsx", {
                "pragma": "h",
                "pragmaFrag": "Fragment",
              }]
            ]
        }
      }
    ]
  },

  //sourcemaps
  devtool: 'source-map',

  //server
  devServer: {
    contentBase: path.join(__dirname, 'src'),
    compress: true,
    historyApiFallback: true
  },

  resolve: {
    alias: {
      react: "preact/compat",
      "react-dom/test-utils": "preact/test-utils",
      "react-dom": "preact/compat"
     // Must be below test-utils
    },
  }
}
