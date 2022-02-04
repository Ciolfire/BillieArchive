import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["attribute", "input"];
  static values = {
    base: 0,
    min: 0,
    last: 0,
  }

  connect() {
    this.updateDots(this.baseValue);
  }

  checkDots(event) {
    event.preventDefault()
    
    let value = event.params.value;
    if (value == this.lastValue) {
      this.attributeTarget.checked = true;
      value = this.minValue;
    }
    this.updateDots(value);
  }

  updateDots(value) {
    this.attributeTargets.forEach(attribute => {
      if (attribute.value <= value) {
        attribute.checked = true;
      } else {
        attribute.checked = false;
      }
    });
    this.lastValue = value;
    this.inputTarget.value = value;
  }
}
