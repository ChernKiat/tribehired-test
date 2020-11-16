// window.$ = window.jQuery = require('jquery');
// // window.dt = require('datatables.net')(window, $);
// window.dt = require('datatables.net-bs4')(window, $);
// window.buttons = require('datatables.net-buttons')(window, $);

import $ from 'jquery';
// import dt from 'datatables.net';
import dt from 'datatables.net-bs4';
import buttons from 'datatables.net-buttons';
window.$ = $;
window.dt = dt;
window.buttons = buttons;
