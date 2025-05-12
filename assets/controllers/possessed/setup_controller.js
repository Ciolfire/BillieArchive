import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "vice",
    "viceTotal",
    "vicePrimary",
    "vestment",
    "submit",
  ];

  static values = {
    primary: Number,
    vices: Object,
    sum: Number,
  }

  connect()
  {
    let vices = this.vicesValue;

    // Initialize the value
    this.viceTargets.forEach(vice => {
      vices[vice.dataset.id] = parseInt(vice.value);
    });
    this.vicesValue = vices;
    this.vicesCheck();
  }

  vicesCheck()
  {
    let isLocked = false;
    // Check if primary vice is bigger
    isLocked = this.vicePrimaryCheck(isLocked);
    // Compare total to 3
    isLocked = this.viceTotalUpdate(isLocked);
    // Lock form if there are issues
    if (isLocked) {
      this.submitTarget.classList.add("disabled");
    } else {
      this.submitTarget.classList.remove("disabled");
    }
    // Update the list of available vestments
    this.vestmentUpdate();
  }

  vicesUpdate(event)
  {
    let vices = this.vicesValue;
    if (vices[event.params.vice] == event.params.value) {
      // Same value, we reset it
      if (event.params.vice == this.primaryValue) {
        vices[event.params.vice] = 1;
      } else {
        vices[event.params.vice] = 0;
      }
    } else {
      // or we update the value
      vices[event.params.vice] = event.params.value;
    }
    // We update the static value
    this.vicesValue = vices;
    // Verify all data for display
    this.vicesCheck();
  }

  vicePrimaryCheck(isLocked)
  {
    let current = 0;
    this.sumValue = 0;
    Object.values(this.vicesValue).forEach(vice => {
      current = Math.max(vice, current);
      this.sumValue += vice;
      console.log(this.sumValue, vice);
    });
    console.log(this.sumValue);
    // Update sum value
    this.viceTotalTarget.innerText = this.sumValue;
    console.log(current, this.viceTargets[this.primaryValue]);
    if (current > this.vicesValue[this.primaryValue]) {
      // If any vice has more dots than the main one, we display the issue, and block
      this.vicePrimaryTarget.classList.add("ko");
      isLocked = true;
    } else {
      this.vicePrimaryTarget.classList.remove("ko");
    }
    return isLocked;
  }

  viceTotalUpdate(isLocked)
  {
    if (this.sumValue == 3)
      {
      // If the number of dots is 3, we display it's ok
      this.viceTotalTarget.classList.add("ok");
      this.viceTotalTarget.classList.remove("accent-pale");
      this.viceTotalTarget.classList.remove("ko");
    } else if (this.sumValue > 3) {
      // if it's higher, it's ok
      this.viceTotalTarget.classList.remove("ok");
      this.viceTotalTarget.classList.add("accent-pale");
      this.viceTotalTarget.classList.remove("ko");
    } else {
      // if it's lower, we block
      this.viceTotalTarget.classList.remove("ok");
      this.viceTotalTarget.classList.remove("accent-pale");
      this.viceTotalTarget.classList.add("ko");
      isLocked = true;
    }
    return isLocked;
  }

  vestmentUpdate() {
    this.vestmentTargets.forEach(vestment => {
      if (this.vicesValue[vestment.dataset.vice] >= vestment.dataset.level ) {
        vestment.classList.remove("d-none");
      } else {
        vestment.classList.add("d-none");
      }
    });
  }

  clean(event) {
    this.vestmentTargets.forEach(vestment => {
      if (vestment.classList.contains("d-none")) {
        Array.prototype.forEach.call(vestment.getElementsByClassName("btn-check"), function(input) {
          console.log(input);
          input.setAttribute('name', '');
        });
      }
    });
  }
}