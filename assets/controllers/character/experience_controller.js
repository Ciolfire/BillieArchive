import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "input",
    "total",
    "used"
  ];
  static values = {
    id: 0,
  }

  update(event) {
    let xhttp = new XMLHttpRequest();
    var target = this.totalTarget;

    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        target.innerText = JSON.parse(xhttp.responseText).total;
      }
    };
    xhttp.open("POST", `/en/character/${this.idValue}/experience/update`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");

    let data = JSON.stringify({'value': this.inputTarget.value, 'method': event.params.method });
    xhttp.send(data);
  }
}