import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "character",
    "locale",
    "type",
    "elements",
    "value",
    "icon",
    "submit",
  ];

  static values = {
    timer: 0,
  }

  connect() {
    if (this.hasElementsTarget) {
      this.elementsTarget.parentElement.classList.add('collapse');
      this.load();
    }
  }

  load() {
    let elements = this.elementsTarget;
    let submit = this.submitTarget;

    if (this.typeTarget.value == "") {
      elements.parentElement.classList.add('collapse');
      submit.classList.remove('disabled');
      return;
    }
    submit.classList.add('disabled');

    window.fetch("/fetch/load/status", {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "POST",
      body: JSON.stringify({ 'type': this.typeTarget.value, 'character': this.characterTarget.value, 'locale': this.localeTarget.value })

    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
      })
      .then((response) => {
        elements.innerHTML = response.choices;
        elements.parentElement.classList.remove('collapse');
        if (response.choices != null) {
          elements.innerHTML = response.choices
          elements.parentElement.classList.remove('collapse');
        } else {
          elements.parentElement.classList.add('collapse');
          submit.classList.remove('disabled');
        }
      })
      ;
  }

  unlock() {
    if ((this.elementsTarget.value != "" || this.elementsTarget.length == 0)) {
      this.submitTarget.classList.remove('disabled');
    } else {
      this.submitTarget.classList.add('disabled');
    }
  }

  isBuff() {
    if (this.valueTarget.value > 0) {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-debuff');
        icon.classList.add('status-buff');
      });
    } else if (this.valueTarget.value < 0) {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-buff');
        icon.classList.add('status-debuff');
      });
    } else {
      this.iconTargets.forEach(icon => {
        icon.classList.remove('status-debuff');
        icon.classList.remove('status-buff');
      });
    }
  }

  delete(event) {
    clearTimeout(this.timerValue);
    let target = event.currentTarget;
    console.log(target);

    // Animation
    let iconSpiningKeyframes = new KeyframeEffect(
      target,
      [
        { transform: "scale(1)" },
        { transform: "scale(0)" },
      ],
      { duration: 1001, iterations: 1 }
    );
    let spiningAnimation = new Animation(iconSpiningKeyframes, document.timeline);
    spiningAnimation.play();
    // End animation
    // after long press...
    this.timerValue = window.setTimeout(function () {
      window.fetch("/fetch/delete/status", {
        headers: {
          "Content-Type": "application/json",
          'X-Requested-With': 'XMLHttpRequest'
        },
        method: "POST",
        body: JSON.stringify({ 'id': event.params.id })

      })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
      })
      let icon = event.target.closest('span');
      console.log(icon);
      let tooltip = document.getElementById(icon.getAttribute('aria-describedBy'));
      if (tooltip) {
        tooltip.remove();
      }
      target.remove();
      clearTimeout(this.timerValue);
    }, 1000);
  }

  cancel(event) {
    clearTimeout(this.timerValue);
    let animations = event.currentTarget.getAnimations();
    if (animations[0] != undefined) {
      animations[0].cancel();
    }
  }

  locked(event) {
    clearTimeout(this.timerValue);
    let statusLockedKeyframes = new KeyframeEffect(
      event.currentTarget,
      [
        { transform: 'translateX(0%)' }, 
        { transform: 'translateX(10%)' },
        { transform: 'translateX(0%)' }, 
      ],
      { duration: 100, iterations: 10 }
    );
    let statusLockedAnimation = new Animation(statusLockedKeyframes, document.timeline);
    this.timerValue = window.setTimeout(function () {
      statusLockedAnimation.play();
      clearTimeout(this.timerValue);
    }, 500);
  }
}