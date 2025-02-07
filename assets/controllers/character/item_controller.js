import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["item", "container", "choiceContainer", "choiceCharacter", "closeMove", "closeDrop"];
  static values = {
    character: 0,
    id: 0,
  }

  connect() {
    // this.update();
  }

  prepare(event) {
    let params = event.params;
    this.idValue = params.id;
  }

  reset(event) {
    if (event.target.name == "character") {
      this.choiceContainerTarget.value = 0;
    } else {
      this.choiceCharacterTarget.value = 0;
    }
  }

  move() {
    let type = "container";
    let target = this.choiceContainerTarget.value;
    if (this.choiceCharacterTarget.value != 0) {
      type = "character";
      target = this.choiceCharacterTarget.value;
    }

    window
    .fetch(`/${document.location.pathname.split('/')[1]}/item/${this.idValue}/move/${type}/${target}`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "POST"
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

    })
    .then(() => {
      let target = this.itemTargets.find(target => target.dataset.id == this.idValue);
      if (type == "character") {
        target.remove();
      } else {
        let container = this.containerTargets.find(target => target.dataset.container == this.choiceContainerTarget.value);
        container.appendChild(target);
      }
      this.closeMoveTarget.click();
    });
  }

  drop() {
    window
    .fetch(`/${document.location.pathname.split('/')[1]}/item/${this.idValue}/drop`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "POST"
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

    })
    .then(() => {
      let target = this.itemTargets.find(target => target.dataset.id == this.idValue);
      target.parentElement.remove();
      this.closeDropTarget.click();
    });
  }

  destroy() {
    window
    .fetch(`/${document.location.pathname.split('/')[1]}/item/${this.idValue}/delete`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      method: "DELETE"
    })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }

    })
    .then(() => {
      let target = this.itemTargets.find(target => target.dataset.id == this.idValue);
      target.parentElement.remove();
      this.closeDropTarget.click();
    });
  }

  save(action) {
    // window
    // .fetch(`/${document.location.pathname.split('/')[1]}/item/${this.idValue}/save`, {
    //   headers: {
    //     "Content-Type": "application/json",
    //     'X-Requested-With': 'XMLHttpRequest'
    //   },
    //   body: JSON.stringify({'value': this.values }),
    //   method: "POST"
    // })
    // .then((response) => {
    //   if (!response.ok) {
    //     throw new Error(`HTTP error! Status: ${response.status}`);
    //   }
    //   return response.json();
    // })
    // .then((data) => {
    // });
  }
}
