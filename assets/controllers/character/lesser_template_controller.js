import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "form",
    "description"
  ];

  static values = {
    index: Number,
    prototype: String,
  }

  connect() {
    if (document.querySelector('input[name="lesser_template_form[template]"]:checked')) {
      this.loadForm();
    }
  }

  showRules() {
    
    let selected = document.querySelector('input[name="lesser_template_form[template]"]:checked').value;    
    
    console.log(selected, this.descriptionTargets);
    this.descriptionTargets.forEach(description => {
      if (description.id == selected) {
        description.classList.remove("d-none");
      } else {
        description.classList.add("d-none");
      }
    });
  }

  loadForm() {
    window
    .fetch(`/fetch/${document.location.pathname.split('/')[1]}/entity/form`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({'entity': document.querySelector('input[name="lesser_template_form[template]"]:checked').value }),
      method: "POST"
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      if (response.status == 204) {
        this.formTarget.innerHTML = null;
        throw new Error(`No form`, { cause: 204});
      }

      return response.json();
    })
    .then((response) => {
      this.formTarget.innerHTML = response.data;
    })
    .catch((error) => {
      if (error.cause == 204) {
        console.log("no Form");
      } else {
        console.log(error);
      }
    });
  }
}
