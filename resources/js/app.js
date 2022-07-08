import { h, render } from 'preact';
import 'regenerator-runtime/runtime';

let root;

function init() {
  //let App = require('./components/App').default;
  let App = require('./components/App.jsx').default;
	root = render(<App />, document.getElementById('preact-root'), root);
}

init();
