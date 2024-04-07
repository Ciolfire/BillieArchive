import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
  '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
  true,
  /\.(j|t)sx?$/
));

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

// Import other 'tuff
import { Tooltip } from 'bootstrap';
import { Toast } from 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';

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