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
    this.selectTab(`${hash}Tab`);
  }

  show(event) {
    this.selectPage(event.params.target);
    this.selectTab(event.target.id);
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

  selectTab(selected) {
    this.tabTargets.forEach(tab => {
      if (tab.id == selected) {
        tab.classList.add("active");
      } else {
        tab.classList.remove("active");
      }
    });
  }
}
