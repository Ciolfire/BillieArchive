import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["input", "value", "flag"];
  static values = {
    current: 0,
    id: 0,
    type: 'null',
    inverted: false,
    isLesser: false,
  }

  connect() {
    this.update();
    this.flags();
  }

  switch(event) {
    let target = event.currentTarget;
    // If we click on the current input, we reduce by one
    if (this.currentValue === Number(target.dataset.count)) {
      this.currentValue--;
    } else {
      this.currentValue = Number(target.dataset.count);
    }
    if (this.hasValueTarget) {
      this.valueTarget.innerText = this.currentValue;
    }

    this.inputTargets.forEach(input => {
      let type = false;
      // check/uncheck based on the new value
      if (Number(input.dataset.count) <= this.currentValue) {
        input.dataset.value = 1;
        type = 'on';
      } else {
        input.dataset.value = 0;
        type = "none";
      }
      for (const children of input.children) {
        if (children.classList.contains(type)) {
          // display the ones we need
          children.classList.remove("d-none");
        } else {
          // hide the others
          children.classList.add("d-none");
        }
      }
    });
    this.save();
  }

  update() {
    this.inputTargets.forEach(input => {
      if (input.dataset.value == 1 && this.invertedValue == false || input.dataset.value == 0 && this.invertedValue == true) {
        this.show(input, "on");
      } else {
        this.show(input, "none");
      }
    });
  }

  flags(event) {
    let value = this.currentValue;
    let isOn = 1;
    if (event != undefined) {
      value = parseInt(event.currentTarget.dataset.count);
      isOn = event.currentTarget.dataset.value;
    }
    this.flagTargets.forEach(flag => {
      if ((value == flag.dataset.index && isOn == 0) || 
        (value + 1 == flag.dataset.index & isOn == 1)
      ) {
        flag.classList.add("warning");
      } else {
        flag.classList.remove("warning");
      }
    })
  }

  show(element, type) {
    // check all element of the canvas
    for (const children of element.children) {
      if (children.classList.contains(type)) {
        // display the ones we need
        children.classList.remove("d-none");
      } else {
        // hide the others
        children.classList.add("d-none");
      }
    }
  }

  save() {
    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
      }
    };
    if (this.isLesserValue) {
      xhttp.open("POST", `/en/character/${this.idValue}/lesser/trait/update`, true);
    } else {
      xhttp.open("POST", `/en/character/${this.idValue}/trait/update`, true);
    }
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");
    let data = JSON.stringify({ 'value': this.currentValue, 'trait': this.typeValue });
    xhttp.send(data);
  }
}
