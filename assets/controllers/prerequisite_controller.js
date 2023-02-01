import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "type",
    "list",
    "id"
  ];

  connect()
  {
    this.firstLoad();
    this.listTarget.name = "";
    if (this.idTarget.value != "") {
      this.listTarget.value = this.idTarget.value;
    }
  }

  log(event)
  {
    console.log(this.typeTarget.value);
  }

  select(event) {
    this.idTarget.value = this.listTarget.value;
  }

  load(event) {
    let xhttp = new XMLHttpRequest();
    let target = this.listTarget;

    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        console.log(xhttp.responseText);
        target.innerHTML = JSON.parse(xhttp.responseText).choices;
      }

    };
    xhttp.open("POST", `/ajax/load/prerequisites`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");

    let data = JSON.stringify({'value': this.typeTarget.value });
    xhttp.send(data);
  }

  firstLoad(event) {
    let xhttp = new XMLHttpRequest();
    let target = this.listTarget;
    let id = this.idTarget;

    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        console.log(xhttp.responseText);
        target.innerHTML = JSON.parse(xhttp.responseText).choices;
        target.value = id.value;
      }

    };
    xhttp.open("POST", `/ajax/load/prerequisites`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");

    let data = JSON.stringify({'value': this.typeTarget.value });
    xhttp.send(data);
  }
}