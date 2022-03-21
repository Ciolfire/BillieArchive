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
  ];

  connect() {
    this.attributeUpdate();
    this.meritUpdate();
    this.skillUpdate();
    this.specialtyUpdate();
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
    this.dispatch("change", { detail: { type: 'attribute', target: changed } });
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
    this.dispatch("change", { detail: { type: 'skill', target: changed } });
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

  meritUpdate(event) {
    let total = this.cost(this.meritTargets, 'merit');

    this.creationMeritTarget.innerText = total;
    if (total == 7) {
      this.switch(this.creationMeritTarget, "ok", "ko");
    } else {
      this.switch(this.creationMeritTarget, "ko", "ok");
    }
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
}
