import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
  ];
  static values = {
  }

  connect() {
    document.getElementsByClassName("form-check").forEach(element => {
      if (element.children[0].classList.contains("order-first")) {
        element.classList.add("order-first");
      }
    });
  }
}