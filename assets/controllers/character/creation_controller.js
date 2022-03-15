import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "creationAttr",
    "mentalAttr",
    "physicalAttr",
    "socialAttr",
    "creationSkill",
    "mentalSkill",
    "physicalSkill",
    "socialSkill",
    "creationSpec",
    "specialties",
    "advantage",
    "creationMerit",
    "merit",
    "prerequisite",
  ];
  static values = {
  }

  connect() {
    this.checkPrerequisite('race');
    this.attributeUpdate();
    this.skillUpdate();
    this.specialtyUpdate();
    this.meritUpdate(null);
  }

  attributeUpdate(event) {
    let changed = null;
    let categories = [this.cost(this.mentalAttrTargets, 'attr'), this.cost(this.physicalAttrTargets, 'attr'), this.cost(this.socialAttrTargets, 'attr')].sort((a, b) => b - a);
    let goal = [5, 4, 3];

    categories.forEach((category, index) => {
      this.creationAttrTargets[index].innerText = category;
      if (category == goal[index]) {
        this.switch(this.creationAttrTargets[index], "ok", "ko");
      } else {
        this.switch(this.creationAttrTargets[index], "ko", "ok");
      }
    });
    this.advantagesUpdate();
    if (event) {
      changed = event.target.getAttribute("for").split('-')[0];
    }
    this.checkPrerequisite('attribute', changed);
  }

  skillUpdate(event) {
    let changed = null;
    let categories = [this.cost(this.mentalSkillTargets, 'skill'), this.cost(this.physicalSkillTargets, 'skill'), this.cost(this.socialSkillTargets, 'skill')].sort((a, b) => b - a);
    let goal = [11, 7, 4];

    categories.forEach((categorie, index) => {
      this.creationSkillTargets[index].innerText = categorie;
      if (categorie == goal[index]) {
        this.switch(this.creationSkillTargets[index], "ok", "ko");
      } else {
        this.switch(this.creationSkillTargets[index], "ko", "ok");
      }
    });
    if (event) {
      changed = event.target.getAttribute("for").split('-')[0];
    }
    this.checkPrerequisite('skill', changed);
  }

  specialtyUpdate() {
    let missing = this.specialtiesTargets.some(specialty => {
      if (specialty.value === "") {
        this.switch(this.creationSpecTarget, "ko", "ok");
        return true;
      } else {
        return false;
      }
    });

    if (!missing) {
      this.switch(this.creationSpecTarget, "ok", "ko");
    }
  }

  meritClick(event) {
    let card = event.target.closest(".block");
    let merits = document.getElementsByName(card.attributes.name.value);

    if (false == card.dataset.unique) {
      
      if (card.getElementsByClassName("merit-value")[0].value > 0) {
        let needNew = true;
        merits.forEach(merit => {
          if (merit.getElementsByClassName("merit-value")[0].value == 0) {
              needNew = false;
            }
        });
        if (needNew) {
          this.meritGeneration(card, merits.length);
        }
      } else if (merits.length > 1) {
        card.parentNode.remove();
      }
    }
    this.meritUpdate(event);
  }

  meritUpdate(event) {
    let changed = null;
    let total = this.cost(this.meritTargets, 'merit');

    this.creationMeritTarget.innerText = total;
    if (total == 7) {
      this.switch(this.creationMeritTarget, "ok", "ko");
    } else {
      this.switch(this.creationMeritTarget, "ko", "ok");
    }
    
    if (event) {
      changed = event.target.getAttribute("for").split('-')[0];
    }
    this.checkPrerequisite('merit', changed)
  }

  meritGeneration(card, length) {
    
    let newCard = card.parentNode.cloneNode(true);
    let valueInput = newCard.getElementsByClassName("merit-value")[0];
    let detailsInput = newCard.getElementsByClassName("merit-detail")[0];
    let id = valueInput.dataset.id;
    let newId = `${id}-${length}`;
    // Update all classes
    let collapsableElements = newCard.getElementsByClassName(`merit-text-${id}`);
    while (collapsableElements.length > 0) {
      let element = collapsableElements[0];
      element.classList.remove(`merit-text-${id}`);
      element.classList.add(`merit-text-${newId}`);
    }
    valueInput.name = `character[merits][${newId}][level]`;
    detailsInput.name = `character[merits][${newId}][details]`;
    // reset the values for the new form
    valueInput.value = 0;
    detailsInput.value = "";
    card.parentNode.after(newCard);
  }

  checkPrerequisite(type, changed=null) {
    this.prerequisiteTargets.forEach(prerequisite => {
      let data = prerequisite.dataset;
      switch (type) {
        case 'race':
          if (document.getElementById('character_race').value == data.name) {
            this.switch(prerequisite, "ok", "ko");
          } else {
            this.switch(prerequisite, "ko", "ok");
          }
          break;
        case 'merit':
          if ((changed != null && data.name == changed) || data.type == type) {
            if (document.getElementsByName(`character[merits][${data.name}][level]`)[0].value >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
        case 'skill':
          if ((changed !== null && data.name == changed) || data.type == type) {
            if (this.attr("skills_" + data.name) >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
        default:
          if ((changed !== null && data.name == changed) || data.type == type) {
            if (this.attr(data.name) >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
      }
    });
  }

  attr(name) {
    let attr = document.getElementById(`character_${name}`);
    if (attr) {
      return +attr.value;
    } else {
      console.log("attribute not found: " + name);
    }
  }

  cost(targets, type) {
    let total = 0;
    let mod = 0;
    if (type == 'attr') {
      mod = -1;
    }

    targets.forEach(target => {
      let value = +target.value + mod;
      if (target.value == 5) {
        total += value + 1;
      } else {
        total += value;
      }
    });
    return total;
  }

  switch(target, show, hide) {
    target.classList.remove(hide);
    target.classList.add(show);
  }

  advantagesUpdate() {
    let health = 5;
    let healthElem = null;

    this.advantageTargets.forEach(advantage => {
      switch (advantage.id) {
        case "defense":
          advantage.innerText = Math.min(this.attr('wits'), this.attr('dexterity'));
          break;
        case "initiative":
          advantage.innerText = this.attr('dexterity') + this.attr('composure');
          break;
        case "size":
          health += this.attr('stamina');
          break;
        case "health":
          healthElem = advantage;
          break;
        case "speed":
          advantage.innerText = this.attr('dexterity') + this.attr('strength') + 5;
          break;
        case "willpower":
          advantage.innerText = this.attr('resolve') + this.attr('composure');
          break;
        default:
          break;
      }
    });
    healthElem.innerText = health;
  }

  meritFilter(event) {
    let category = event.params.category;

    this.meritTargets.forEach(merit => {
      let card = merit.closest('.card').parentElement;
      if (merit.dataset.category == category || category == "") {
        if (card.classList.contains('d-none')) {
          card.classList.remove('d-none')
        }
      } else {
        card.classList.add('d-none')
      }
    });
  }
}
