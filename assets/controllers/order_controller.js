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
    this.containerTarget.classList.remove("collapse");
  }
}