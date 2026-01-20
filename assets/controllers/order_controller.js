import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "item",
    "container"
  ];
  static values = {
  }

  connect() {
    this.byName();
    this.containerTarget.classList.remove("collapse");
  }

  byName() {
    let ordered = this.itemTargets.sort(function (a, b) {
      if (a.dataset.name == "" && b.dataset.name != "") {
        return 1;
      } else if (b.dataset.name == "" && a.dataset.name != "") {
        return -1;
      }
      return String.prototype.localeCompare.call(a.dataset.name, b.dataset.name);
    });
    this.containerTarget.innerHTML = null;
    ordered.forEach((element) => this.containerTarget.appendChild(element));
  }

  byId() {
    let ordered = this.itemTargets.sort(function (a, b) {
      return a.dataset.id - b.dataset.id;
    });
    this.containerTarget.innerHTML = null;
    ordered.forEach((element) => this.containerTarget.appendChild(element));
  }

  byIdInv() {
    let ordered = this.itemTargets.sort(function (a, b) {
      return b.dataset.id - a.dataset.id;
    });
    this.containerTarget.innerHTML = null;
    ordered.forEach((element) => this.containerTarget.appendChild(element));
  }
}