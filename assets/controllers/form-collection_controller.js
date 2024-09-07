import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["collectionContainer", "block"];

  static values = {
    index: Number,
    prototype: String,
  }

  addCollectionElement(event) {
    const item = document.createElement('div');
    item.classList.add("col-12", "col-lg-6", "mb-1");
    item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
    this.collectionContainerTarget.appendChild(item);
    this.indexValue++;
  }

  removeCollectionElement(event) {
    console.log(this.blockTargets);
    this.blockTargets.forEach(element => {
      if (element.contains(event.target)) {
          element.parentElement.remove();
      }
    })
  }
}