import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal"
  ];
  connect() {
  }

  load(event) {
    this.modalTarget.querySelector("#contentModalTitle").innerHTML = event.params.name;

    window
    .fetch(`${event.params.url}`)
    .then((response) => response.text())
    .then((text) => {
      this.modalTarget.querySelector("#contentModalContainer").innerHTML = text;
    });
      // .fetch(`${event.params.url}`, {
      //   headers: {
      //     "Content-Type": "application/json",
      //     'X-Requested-With': 'XMLHttpRequest'
      //   }
      // })
      // .then((response) => {
      //   if (!response.ok) {
      //     throw new Error(`HTTP error! Status: ${response.status}`);
      //   } 
      //   return response.blob();
      // }).then((data) => {
      //   console.log(data);
      //   // this.modalTarget.querySelector("#contentModalContainer").innerHTML = response.html;
      // });
  }
}