import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["item", "container", "choice", "closeMove", "closeDrop"];
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

  move() {
    window
    .fetch(`/en/item/${this.idValue}/move/${this.choiceTarget.value}`, {
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
      let container = this.containerTargets.find(target => target.dataset.container == this.choiceTarget.value);
      container.appendChild(target.parentElement);
      this.closeMoveTarget.click();
    });
  }

  drop() {
    window
    .fetch(`/en/item/${this.idValue}/drop`, {
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
    .fetch(`/en/item/${this.idValue}/delete`, {
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

  save(action) {
    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        //ok
      }
    };
    xhttp.open("POST", `/en/character/${this.idValue}/wounds/update`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Content-Type", "application/json");
    let data = JSON.stringify({'value': this.currentValue, 'action': action});
    xhttp.send(data);
  }
}
