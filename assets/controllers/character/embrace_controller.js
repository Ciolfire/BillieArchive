import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    'clan',
    'clanAttribute',
    'clanDescription',
    'clanDiscipline',
    'disciplineValue',
    'disciplinesFavored',
    'disciplinesTotal',
    'submit',
  ];
  static values = {
    clan: Number,
    disciplines: Array
  }


  connect() {
    this.clanTargets.forEach(clan => {
      if (clan.previousSibling.checked) {
        clan.dispatchEvent(new Event("click"));
      }
    });
  }

  disciplineUpdate() {
    let favored = 0;
    
    let total = 0;
    this.disciplineValueTargets.forEach(discipline => {
      if (this.disciplines.includes(discipline.parentElement.dataset.id)) {
        favored = favored + +discipline.value;
      }
      total = total + +discipline.value;
    });
    if (total == 3) {
      this.disciplinesTotalTarget.classList.add("ok");
    } else {
      this.disciplinesTotalTarget.classList.remove("ok");
    }
    if (favored == 2 || favored == 3) {
      this.disciplinesFavoredTarget.classList.add("ok");
    } else {
      this.disciplinesFavoredTarget.classList.remove("ok");
    }
    this.disciplinesTotalTarget.innerText = total;
    this.disciplinesFavoredTarget.innerText = favored;
    if ((favored == 2 || favored == 3) && total == 3) {
      this.submitTarget.classList.remove("disabled");
    } else {
      this.submitTarget.classList.add("disabled");
    }
  }

  clanPicked(event) {
    this.clan = event.target.dataset.clan;
    this.disciplines = event.target.dataset.disciplines.split(' ');
    let attributes = event.target.dataset.attributes.split(' ');
    
    this.toggle(this.clanDescriptionTargets, this.clan);
    this.toggleAll(this.clanAttributeTargets, attributes);
    this.toggleDisciplines();
    this.disciplineUpdate();
  }

  toggleAll(elements, keys) {
    elements.forEach(element => {
      let hide = true;
      for (const key of keys) {
        if (element.classList.contains(key)) {
          hide = false;
        }
      }
      if (hide) {
        element.classList.add("d-none");
      } else {
        element.classList.remove("d-none");
      }
    });
  }

  toggle(elements, key) {
    elements.forEach(element => {
      if (element.classList.contains(key)) {
        element.classList.remove("d-none");
        return;
      }
      element.classList.add("d-none");
    });
  }

  toggleDisciplines() {
    this.clanDisciplineTargets.forEach(discipline => {
      let block = discipline.closest('.discipline-block');
      let input = discipline.getElementsByTagName('input')[0];
      block.classList.add("order-2");
      block.classList.remove("d-none");
      for (const key of this.disciplines) {
        if (discipline.dataset.id == key) {
          block.classList.remove("order-2");
          discipline.getElementsByTagName("label")[1].classList.remove("d-hide");
          discipline.getElementsByTagName("label")[2].classList.remove("d-hide");
          return;
        }
      }
      if (discipline.dataset.restricted == 0) {
        if (input.value > 1) {
          input.value = 1;
          input.dispatchEvent(new Event("change"));
        }
        discipline.getElementsByTagName("label")[1].classList.add("d-hide");
        discipline.getElementsByTagName("label")[2].classList.add("d-hide");
        return;
      }
      if (input.value > 0) {
        input.value = 0;
        input.dispatchEvent(new Event("change"));
      }
      block.classList.add("d-none");
    });
  }

  switch(target, show, hide) {
    target.classList.remove(hide);
    target.classList.add(show);
  }

  clean(event) {
    this.disciplineValueTargets.forEach(element => {
      if (element.value == 0) {
        element.setAttribute('name', '');
      }
    });
    document.forms['embrace'].submit();
  }
}
