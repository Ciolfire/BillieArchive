import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "arcana",
    "rote"
  ];
  static values = {
    arcana: {},
  }
  connect() {
  }

  updateArcana()
  {
    let arcana = this.arcanaValue;
    for (const arcanum of document.getElementsByClassName("arcanum-value")) {
      arcana[arcanum.dataset.id] = parseInt(arcanum.value);
    }

    this.arcanaValue = arcana;
    this.display();
  }

  display() {
    for (const rote of this.roteTargets) {
      if (this.matchArcana(rote)) {
        console.log(this.matchArcana(rote));
        rote.classList.remove("collapse");
      } else {
        rote.classList.add("collapse");
      }
    }
  }

  matchArcana(rote)
  {
    let roteArcana = JSON.parse(rote.dataset.arcana);
    for (const arcanum of Object.keys(roteArcana)) {
      if ( roteArcana[arcanum] > this.arcanaValue[arcanum] || this.arcanaValue[arcanum] == undefined) {
        return false;
      }
    }

    return true;
  }
}