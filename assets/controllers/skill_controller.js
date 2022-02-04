import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["skill"];
  static values = {
    base: 0,
    min: 0,
    last: 0
  }

  connect() {
    this.updateDots(this.baseValue);
  }

  checkDots(event) {
    event.preventDefault()
    
    let value = event.params.value;
    if (value == this.lastValue) {
      this.skillTarget.checked = true;
      value = this.minValue;
    }
    this.updateDots(value);
  }

  updateDots(value) {
    this.skillTargets.forEach(skill => {
      if (skill.value <= value) {
        skill.checked = true;
      } else {
        skill.checked = false;
      }
    });
    this.lastValue = value;
  }
}
