import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "character",
    "locale",
    "type",
    "elements",
    "value",
    "icon",
    "submit"
  ];

  static values = {
  }

  connect() {
    this.elementsTarget.parentElement.classList.add('collapse');
    this.load();
  }

  load() {
    let elements = this.elementsTarget;
    let submit = this.submitTarget;

    if (this.typeTarget.value == "") {
      elements.parentElement.classList.add('collapse');
      submit.classList.remove('disabled');
      return;
    }
    submit.classList.add('disabled');

    window.fetch("/fetch/load/status", {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "POST",
      body: JSON.stringify({ 'type': this.typeTarget.value, 'character': this.characterTarget.value, 'locale': this.localeTarget.value })

    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
      })
      .then((response) => {
        elements.innerHTML = response.choices;
        elements.parentElement.classList.remove('collapse');
        if (response.choices != null) {
          elements.innerHTML = response.choices
          elements.parentElement.classList.remove('collapse');
        } else {
          elements.parentElement.classList.add('collapse');
          submit.classList.remove('disabled');
        }
      })
      ;
  }

  unlock() {
    if ((this.elementsTarget.value != "" || this.elementsTarget.length == 0)) {
      this.submitTarget.classList.remove('disabled');
    } else {
      this.submitTarget.classList.add('disabled');
    }
  }

  isBuff() {
    if (this.valueTarget.value > 0) {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-debuff');
        icon.classList.add('status-buff');
      });
    } else if (this.valueTarget.value < 0) {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-buff');
        icon.classList.add('status-debuff');
      });
    } else {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-debuff');
        icon.classList.remove('status-buff');
      });
    }
  }
}