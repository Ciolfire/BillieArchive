import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "rule",
    "rote"
  ];

  static values = {
    arcana: {},
  }

  connect() {
    this.ruleTargets.forEach(rule => {
      
      rule.innerText = document.querySelector(`input[name="${rule.dataset.type}"]:checked`).value;
    });
  }

  updateValue({ params: {type} , currentTarget}) {
    this.ruleTargets.forEach(rule => {
      if (rule.dataset.type == type) {
        rule.innerText = currentTarget.value;
      }
    });
  }

  updateArcana()
  {
    let arcana = this.arcanaValue;
    for (const arcanum of document.getElementsByClassName("arcanum-value")) {
      arcana[arcanum.dataset.id] = parseInt(arcanum.value);
    }

    this.arcanaValue = arcana;
    this.displayRotes();
  }

  displayRotes() {
    for (const rote of this.roteTargets) {
      if (this.roteMatchArcana(rote)) {
        rote.classList.remove("collapse");
      } else {
        rote.classList.add("collapse");
      }
    }
  }

  roteMatchArcana(rote)
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