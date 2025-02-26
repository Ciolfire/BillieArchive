import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "choice",
    "title",
    "description",
    "modifier",
    "details"
  ];

  connect()
  {
    this.choiceTarget.insertAdjacentHTML('afterend', '<div data-bloodbather--bath-target="details" class="block d-none rounded mt-2 p-2"></div>');
    // window
    // .fetch(`/fetch/${document.location.pathname.split('/')[1]}/load/prerequisites`, {
    //   headers: {
    //     "Content-Type": "application/json",
    //     'X-Requested-With': 'XMLHttpRequest'
    //   },
    //   body: JSON.stringify({'value': this.typeTarget.value, 'type': event.params.type, 'homebrew': event.params.homebrew }),
    //   method: "POST"
    // })
    // .then((response) => {
    //   if (!response.ok) {
    //     throw new Error(`HTTP error! Status: ${response.status}`);
    //   }
    //   return response.json();
    // })
    // .then((data) => {
    //   let target = this.listTarget;
    //   target.innerHTML = data.choices;
    //   this.specialTarget.classList.add("d-none");
    //   target.closest(".row").classList.remove("d-none");
    //   if (target.length == 0) {
    //     target.closest(".row").classList.add("d-none");
    //     if (this.typeTarget.value == "special") {
    //       this.specialTarget.classList.remove("d-none");
    //     }
    //   }
    //   if (this.idTarget) {
    //     target.value = this.idTarget.value;
    //   }
    // });

    // Should start load
    this.choiceTarget.dispatchEvent(new Event("change"));

    // this.listTarget.name = "";
    // if (this.idTarget.value != "") {
    //   this.listTarget.value = this.idTarget.value;
    // }
  }

  log()
  {
    console.log(this.typeTarget.value);
  }

  select() {
    this.idTarget.value = this.listTarget.value;
  }

  custom() {
    this.detailsTarget.classList.add('d-none');
    this.titleTarget.parentElement.parentElement.classList.remove('d-none');
    this.descriptionTarget.parentElement.parentElement.classList.remove('d-none');
    this.modifierTarget.parentElement.parentElement.classList.remove('d-none');
  }

  display(data) {
    this.detailsTarget.classList.remove('d-none');
    this.titleTarget.parentElement.parentElement.classList.add('d-none');
    this.descriptionTarget.parentElement.parentElement.classList.add('d-none');
    this.modifierTarget.parentElement.parentElement.classList.add('d-none');
    this.detailsTarget.innerHTML = `
    <strong>Description:</strong><br>${data.description}
    <br>
    <strong>Modifier:</strong> ${data.modifier}
    `;
    this.titleTarget.value = data.title;
    this.descriptionTarget.value = data.description;
    this.modifierTarget.value = data.modifier;
  }

  load(event) {
    let options = this.choiceTarget.children;

    for (let i = 0; i < options.length; i++) {
      if (options[i].value == this.choiceTarget.value && options[i].value == "") {
        this.custom();
        break;
      } else if (options[i].value == this.choiceTarget.value) {
        this.display(options[i].dataset);
        break;
      }
    }
  }
}