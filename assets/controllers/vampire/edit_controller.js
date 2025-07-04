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
  }

  updateDisciplines()
  {
    let disciplines = this.disciplinesValue;
    for (const discipline of document.getElementsByClassName("discipline-value")) {
      disciplines[discipline.dataset.id] = parseInt(discipline.value);
    }

    this.disciplinesValue = disciplines;
    this.displayDevotions();
  }

  displayDevotions() {
    for (const devotion of this.devotionTargets) {
      if (this.matchDiscipline(devotion)) {
        devotion.classList.remove("collapse");
      } else {
        devotion.classList.add("collapse");
      }
    }
  }

  matchDiscipline(devotion)
  {
    let devotionDisciplines = JSON.parse(devotion.dataset.disciplines);
    for (const discipline of Object.keys(devotionDisciplines)) {
      if ( devotionDisciplines[discipline] > this.disciplinesValue[discipline] || this.disciplinesValue[discipline] == undefined) {
        return false;
      }
    }

    return true;
  }
}