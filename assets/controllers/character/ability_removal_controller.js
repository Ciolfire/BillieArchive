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
    let xhttp = new XMLHttpRequest();
    let elements = this.elementsTarget;
    let method = this.methodTarget;
    let submit = this.submitTarget;

    submit.classList.add('disabled');

    if (this.typeTarget.value == "") {
      elements.classList.add('collapse');
      method.classList.add('collapse');
      return;
    }
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        let response = JSON.parse(xhttp.responseText);
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
      }
    };
    xhttp.open("POST", `/ajax/load/removable`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");

    let data = JSON.stringify({'type': this.typeTarget.value, 'character': this.characterTarget.value, 'locale': this.localeTarget.value});
    xhttp.send(data);
  }

  unlock() {
    if (this.elementsTarget.value != "" || this.elementsTarget.length == 0) {
      this.submitTarget.classList.remove('disabled');
    } else {
      this.submitTarget.classList.add('disabled');
    }
  }
}