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
  ];
  static values = {
  }

  connect() {
    this.attributeUpdate();
  }

  attributeUpdate() {
    let categories = [this.cost(this.mentalAttrTargets, 'attr'), this.cost(this.physicalAttrTargets, 'attr'), this.cost(this.socialAttrTargets, 'attr')].sort((a, b) => a - b);

    if (categories[0] == 3 && categories[1] == 4 && categories[2] == 5) {
      this.switch(this.creationAttrTarget, "ok", "ko");
    } else {
      this.switch(this.creationAttrTarget, "ko", "ok");
    }
    this.advantagesUpdate();
  }

  skillUpdate() {
    let categories = [this.cost(this.mentalSkillTargets, 'skill'), this.cost(this.physicalSkillTargets, 'skill'), this.cost(this.socialSkillTargets, 'skill')].sort((a, b) => a - b);
    if (categories[0] == 4 && categories[1] == 7 && categories[2] == 11) {
      this.switch(this.creationSkillTarget, "ok", "ko");
    } else {
      this.switch(this.creationSkillTarget, "ko", "ok");
    }
  }

  specialtyUpdate() {
    let missing = this.specialtiesTargets.some(specialty => {
      if (specialty.value === "") {
        console.log(specialty.value);
        this.switch(this.creationSpecTarget, "ko", "ok");
        return true;
      } else {
        return false;
      }
    });
    console.log(missing);
    if (!missing) {
      this.switch(this.creationSpecTarget, "ok", "ko");
    }
  }

  attr(name) {
    return +document.getElementById(`character_${name}`).value;
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
