import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["item", "container", "choice", "close"];
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
      this.closeTarget.click();
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
    });
  }

  // take() {
  //   this.currentValue = this.currentValue + 1;
  //   this.update();
  //   this.save('take');
  // }

  // heal(event) {
  //   event.preventDefault();

  //   this.currentValue = this.currentValue - 1;
  //   this.update();
  //   this.save('heal');

  //   return false;
  // }

  // show(type) {
  //   for (const children of this.inputTarget.children) {
  //     if (children.classList.contains(type)) {
  //       children.classList.remove("d-none");
  //     } else {
  //       children.classList.add("d-none");
  //     }
  //   }
  // }

  // update() {
  //   if (this.currentValue == 1) {
  //     this.show("bashing");
  //   } else if (this.currentValue == 2) {
  //     this.show("lethal");
  //   } else if (this.currentValue == 3) {
  //     this.show("aggravated");
  //   } else {
  //     this.currentValue = 0;
  //     this.show("none");
  //   }
  // }

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
