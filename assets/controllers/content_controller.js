import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal"
  ];
  static values = {
    link: String,
  }

  connect() {
  }

  load(event) {
    this.modalTarget.querySelector("#contentModalTitle").innerHTML = event.params.type;
    this.linkValue = event.params.link;

    console.log(this.linkValue);

    window
    .fetch(`${event.params.link}`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then((response) => response.text())
    .then((text) => {
      this.modalTarget.querySelector("#contentModalContainer").innerHTML = text;
    });
  }

  open() {
    window.open(this.linkValue, '_blank').focus();
  }
}