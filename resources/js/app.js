require('./bootstrap');
import PNotify from 'pnotify/dist/es/PNotify';
PNotify.defaults.styling = 'bootstrap4';
PNotify.defaults.icons = 'fontawesome5';
window.PNotify = PNotify;

window.ClipboardJS = require('clipboard/dist/clipboard.min');
$.fn.tooltip.Constructor.Default.whiteList.button = [];
