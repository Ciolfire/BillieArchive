import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal",
    "lazy"
  ];
  static values = {
    link: String,
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

    console.log(this.linkValue);

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
}