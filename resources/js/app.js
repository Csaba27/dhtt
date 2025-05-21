import './bootstrap';
import './dark';
import './modules/tooltip';
import 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

import Prism from 'prismjs';
import 'prismjs/plugins/normalize-whitespace/prism-normalize-whitespace';
import 'prismjs/themes/prism-tomorrow.css';
import 'prismjs/components/prism-markup-templating';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-javascript';
Prism.plugins.NormalizeWhitespace.setDefaults({
	'remove-trailing': true,
	'remove-indent': true,
	'left-trim': true,
	'right-trim': true
});
Prism.highlightAll();
