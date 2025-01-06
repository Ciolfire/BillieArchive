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
    this.modalTarget.querySelector("#contentModalTitle").innerHTML = event.params.name;
    this.linkValue = event.params.link;

    console.log(this.linkValue);

    window
    .fetch(`${event.params.url}`)
    .then((response) => response.text())
    .then((text) => {
      this.modalTarget.querySelector("#contentModalContainer").innerHTML = text;
    });
  }

  open() {
    window.open(this.linkValue, '_blank').focus();
  }
}