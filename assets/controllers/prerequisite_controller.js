import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "type",
    "special",
    "list",
    "id"
  ];

  connect()
  {
    this.load();
    this.listTarget.name = "";
    if (this.idTarget.value != "") {
      this.listTarget.value = this.idTarget.value;
    }
  }

  log()
  {
    console.log(this.typeTarget.value);
  }

  select() {
    this.idTarget.value = this.listTarget.value;
  }

  load() {
    window
    .fetch(`/fetch/${document.location.pathname.split('/')[1]}/load/prerequisites`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({'value': this.typeTarget.value }),
      method: "POST"
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      let target = this.listTarget;
      target.innerHTML = data.choices;
      this.specialTarget.classList.add("d-none");
      target.closest(".row").classList.remove("d-none");
      if (target.length == 0) {
        target.closest(".row").classList.add("d-none");
        if (this.typeTarget.value == "special") {
          this.specialTarget.classList.remove("d-none");
        }
      }
      if (this.idTarget) {
        target.value = this.idTarget.value;
      }
    });
  }
}