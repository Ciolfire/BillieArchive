import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["input"];
  static values = {
    current: 0,
    id: 0,
    type: 'null',
    inverted: false,
  }

  connect() {
    this.update();
  }

  switch(event) {
    let isLesser = event.params.lesser;
    if (this.currentValue == 1) {
      this.currentValue = 0;
    } else {
      this.currentValue = 1;
    }
    this.update();
    this.save(isLesser);
  }

  update() {
    if (this.currentValue == 1 && this.invertedValue == false || this.currentValue == 0 && this.invertedValue == true) {
      this.show("on");
    } else {
      this.show("none");
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

  save(isLesser) {
    let xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
        console.log(xhttp.responseText);
      }
    };
    if (isLesser) {
      xhttp.open("POST", `/en/character/${this.idValue}/lesser/trait/update`, true);
    } else {
      xhttp.open("POST", `/en/character/${this.idValue}/trait/update`, true);
    }
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");
    let data = JSON.stringify({'value': this.currentValue, 'trait': this.typeValue});
    xhttp.send(data);
  }
}
