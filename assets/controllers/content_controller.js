import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal",
    "lazy",
    "edit"
  ];
  static values = {
    link: String,
    edit: String,
  }

  connect() {
    this.lazyTargets.forEach(element => {
      this.onVisible(element, () => this.lazyLoad(element));
    });
  }

  onVisible(element, callback) {
    new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if(entry.intersectionRatio > 0) {
          callback(element);
          observer.disconnect();
        }
      });
    }).observe(element);
    if(!callback) return new Promise(r => callback=r);
  }

  lazyLoad(element) {
    window
    .fetch(`${element.dataset.fetch}`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then((response) => response.text())
    .then((text) => {
      element.innerHTML = text;
    });
  }

  load(event) {
    this.modalTarget.querySelector("#contentModalTitle").innerHTML = event.params.type;
    this.linkValue = event.params.link;
    
    if (event.params.edit != undefined) {
      this.editValue = event.params.edit;
      this.editTarget.classList.remove("d-none");
    } else {
      this.editTarget.classList.add("d-none");
    }

    console.debug(this.linkValue);

    window
    .fetch(`${event.params.link}`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then((response) => response.text())
    .then((text) => {
      this.modalTarget.querySelector("#contentModalContainer").innerHTML = text;
    });
  }

  open() {
    window.open(this.linkValue, '_blank').focus();
  }

  edit() {
    window.open(this.editValue, '_blank').focus();
  }
}