import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ["collectionContainer"]

  static values = {
    index: Number,
    prototype: String,
  }

  addCollectionElement(event) {
    const item = document.createElement('div');
    item.classList.add("row");
    item.classList.add("mb-3");
    item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
    this.collectionContainerTarget.appendChild(item);
    this.indexValue++;
  }
}