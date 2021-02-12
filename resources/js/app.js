import { h, render } from 'preact';
import 'regenerator-runtime/runtime';

let root;

function init() {
  //let App = require('./components/App').default;
  let App = require('./components/App.jsx').default;
	root = render(<App />, document.getElementById('preact-root'), root);
}

// in development, set up HMR:
if (module.hot) {
	//require('preact/devtools');   // turn this on if you want to enable React DevTools!
	module.hot.accept('./components/AfpOnboarding', () => requestAnimationFrame(init) );
}

init();
