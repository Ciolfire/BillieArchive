import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["tab", "page", "parent"];
  static values = {
    initial: String,
    parent: String
  };

  connect() {
    if (window.location.hash) {
      let hash = window.location.hash.substring(1);
      // Check if the hash is for this tab group
      if (this.selectPage(hash)) {
        console.log(hash);
        document.getElementById(`${hash}Tab`).classList.add("active");
        // Found the tab, make sure parent tabs are on
        if (window.location.hash && this.parentValue) {
          let parent = document.getElementById(`${this.parentValue}Tab`);
          parent.click();
        }
        return;
      }
    }
    // Hash not found/not matching, default tab
    if (this.initialValue) {
      this.selectPage(this.initialValue);
      let tab = document.getElementById(`${this.initialValue}Tab`);
      if (tab) {
        tab.classList.add("active");
        tab.scrollIntoView({
          behavior: 'smooth'
        });
      } else {
        console.log(`${tab} not found`);
      }
    }
    // this.unselectTab();
  }

  show(event) {
    this.selectPage(event.params.target);
    this.unselectTab(event.target.id);
    event.target.classList.add("active");
  }

  selectPage(selected) {
    let result = false;
    this.pageTargets.forEach(page => {
      if (page.id == selected) {
        page.classList.remove("d-none");
        result = true;
      } else {
        page.classList.add("d-none");
      }
    });

    return result;
  }

  unselectTab() {
    this.tabTargets.forEach(tab => {
      tab.classList.remove("active");
    });
  }
}
