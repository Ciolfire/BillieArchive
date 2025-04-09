import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "character",
    "locale",
    "type",
    "method",
    "elements",
    "submit"
  ];

  connect()
  {
    this.methodTarget.classList.add('collapse');
    this.elementsTarget.classList.add('collapse');
    this.load();
  }

  load() {
    let elements = this.elementsTarget;
    let method = this.methodTarget;
    let submit = this.submitTarget;

    submit.classList.add('disabled');

    if (this.typeTarget.value == "") {
      elements.classList.add('collapse');
      method.classList.add('collapse');
      return;
    }
    
    window.fetch("/fetch/load/removable", {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "POST",
      body: JSON.stringify({'type': this.typeTarget.value, 'character': this.characterTarget.value, 'locale': this.localeTarget.value})

    })
    .then((response) => {
      if (response.ok) {
        return response.json();
      }
    })
    .then((response) => {
      elements.innerHTML = response.choices;
      elements.classList.remove('collapse');
      method.classList.remove('collapse');
      method.innerHTML = response.methods;
      if (response.choices != null) {
        elements.innerHTML = response.choices
        elements.classList.remove('collapse');
      } else {
        elements.classList.add('collapse');
        if (method.length <= 1) {
          submit.classList.remove('disabled');
        }
      }
    })
    ;
  }

  unlock() {
    if (this.elementsTarget.value != "" || this.elementsTarget.length == 0) {
      this.submitTarget.classList.remove('disabled');
    } else {
      this.submitTarget.classList.add('disabled');
    }
  }
}