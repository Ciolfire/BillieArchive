import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["dot", "input"];
  static values = {
    base: 0,
    min: 0,
    last: 0,
  }

  connect() {
    this.updateDots(this.inputTarget.value);
  }

  click(event) {
    event.preventDefault();
    
    let value = event.params.value;

    if (value == this.lastValue) {
      this.dotTarget.checked = true;
      value = this.minValue;
    }
    this.updateDots(value);
  }

  updateDots(value) {
    this.dotTargets.forEach(dot => {
      if (dot.value <= value) {
        dot.checked = true;
      } else {
        dot.checked = false;
      }
    });
    this.lastValue = value;
    this.inputTarget.value = value;
  }
}
