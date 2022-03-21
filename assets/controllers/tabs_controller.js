import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["tab", "page"];
  static values = {
    initial: String
  };

  connect() {
    let hash = this.initialValue;
    if (window.location.hash) {
      hash = window.location.hash.substring(1);
    }
    this.selectPage(hash);
    this.unselectTab();
    document.getElementById(`${hash}Tab`).classList.add("active");
  }

  show(event) {
    this.selectPage(event.params.target);
    this.unselectTab(event.target.id);
    event.target.classList.add("active");
  }

  selectPage(selected) {
    this.pageTargets.forEach(page => {
      if (page.id == selected) {
        page.classList.remove("d-none");
      } else {
        page.classList.add("d-none");
      }
    });
  }

  unselectTab() {
    this.tabTargets.forEach(tab => {
      tab.classList.remove("active");
    });
  }
}
