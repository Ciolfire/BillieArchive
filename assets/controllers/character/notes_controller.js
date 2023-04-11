import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "input",
    "note",
  ];
  static values = {
  }

  connect() {
  }

  update(event) {
    const regex =  new RegExp(".*" + this.inputTarget.value + ".*", "i");
    
    this.noteTargets.forEach(note => {
      if (regex.test(note.dataset.text)) {
        note.parentElement.classList.remove("collapse");
      } else {
        note.parentElement.classList.add("collapse");
      }
    });
  }
}