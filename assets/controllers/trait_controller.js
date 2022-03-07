import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["input"];
  static values = {
    current: 0,
    id: 0,
    type: 'null',
  }

  connect() {
    this.update();
  }

  switch() {
    if (this.currentValue == 1) {
      this.currentValue = 0;
    } else {
      this.currentValue = 1;
    }
    this.update();
    this.save();
  }

  update() {
    if (this.currentValue == 1) {
      this.show("none");
    } else {
      this.show("on");
    }
  }

  show(type) {
    for (const children of this.inputTarget.children) {
      if (children.classList.contains(type)) {
        children.classList.remove("d-none");
      } else {
        children.classList.add("d-none");
      }
    }
  }

  save() {
    let xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        console.log(xhttp.responseText);
      }
    };
    xhttp.open("POST", `/character/${this.idValue}/trait/update`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");
    let data = JSON.stringify({'value': this.currentValue, 'trait': this.typeValue});
    xhttp.send(data);
  }
}
