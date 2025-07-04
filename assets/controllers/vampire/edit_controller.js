import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "potency",
    "devotion"
  ];
  static values = {
    disciplines: {},
  }
  connect() {
    this.updateDisciplines();
    this.updatePotency();
  }

  updatePotency()
  {
    for (const devotion of this.devotionTargets) {
      if (devotion.dataset.potency > this.potencyTarget.value) {
        devotion.classList.add("collapse");
      } else {
        devotion.classList.remove("collapse");
      }
    }
  }

  updateDisciplines()
  {
    let disciplines = this.disciplinesValue;
    for (const discipline of document.getElementsByClassName("discipline-value")) {
      disciplines[discipline.dataset.id] = parseInt(discipline.value);
    }

    this.disciplinesValue = disciplines;
    this.checkDevotionsDisciplines();
  }

  // Make sure the character has the required disciplines
  checkDevotionsDisciplines() {
    for (const devotion of this.devotionTargets) {
      if (this.devotionMatchDisciplines(devotion)) {
        devotion.classList.remove("collapse");
      } else {
        devotion.classList.add("collapse");
      }
    }
  }

  devotionMatchDisciplines(devotion)
  {
    let devotionDisciplines = JSON.parse(devotion.dataset.disciplines);
    for (const discipline of Object.keys(devotionDisciplines)) {
      if (devotionDisciplines[discipline] > this.disciplinesValue[discipline] || this.disciplinesValue[discipline] == undefined) {
        return false;
      }
    }

    return true;
  }
}