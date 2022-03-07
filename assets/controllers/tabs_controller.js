import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["tab", "page"];
  static values = {
    initial: String
  };

  connect() {
    console.log(this.initialValue);
    this.selectPage(this.initialValue);
    this.selectTab(`${this.initialValue}Tab`);
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
