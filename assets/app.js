import 'bootstrap/dist/css/bootstrap.min.css';
import './styles/app.css';

import './bootstrap.js';
import './form.js';
// start the rabbit application
import './wrabbit.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

// Import other 'tuff
import { Tooltip } from 'bootstrap';
import { Toast } from 'bootstrap';
// import '@fortawesome/fontawesome-free/js/all.js';

// Activate tooltips
var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

// Activate toasts
const toastElList = document.querySelectorAll('.toast')
const toastList = [...toastElList].map(toastEl => new Toast(toastEl))

toastElList.forEach(toast => {
  const toastBootstrap = Toast.getOrCreateInstance(toast);
  toastBootstrap.show();
});
