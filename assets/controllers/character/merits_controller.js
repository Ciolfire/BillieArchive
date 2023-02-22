import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "merit",
    "prerequisite",
    "filter"
  ];

  connect() {
    this.checkPrerequisite({ detail: {type: 'race', target: null } });

    this.meritTargets.forEach(merit => {
      let card = merit.closest(".block");
      if (-1 == merit.name.indexOf('meritsUp') && false == card.dataset.unique) {
        if (card.getElementsByClassName("merit-value")[0].value > 0) {
          let merits = document.getElementsByName(card.attributes.name.value);
          if (this.checkNeedGeneration(merits)) {
            this.meritGeneration(card, merits.length);
          }
        }
      }
    });
  }

  add(event) {
    let card = event.target.closest(".block");
    
    if (false == card.dataset.unique) {
      let merits = document.getElementsByName(card.attributes.name.value);
      if (card.getElementsByClassName("merit-value")[0].value > 0) {
        if (true === this.checkNeedGeneration(merits)) {
          this.meritGeneration(card, merits.length);
        }
      } else if (merits.length > 1) {
        card.parentNode.remove();
      }
    }
    this.checkPrerequisite({ detail: { type: 'merit', target: event.target.getAttribute("for").split('-')[0] } })
  }

  checkNeedGeneration(merits) {
    var needNew = true;
      merits.forEach(merit => {
        if (merit.getElementsByClassName("merit-value")[0].value == 0) {
          needNew = false;
          return;
        }
      });
      
      return needNew;
  }

  meritGeneration(card, length) {
    
    let newCard = card.parentNode.cloneNode(true);
    let header = newCard.getElementsByClassName('col')[0]
    let footer = newCard.getElementsByClassName("card-footer")[1];
    
    let valueInput = newCard.getElementsByClassName("merit-value")[0];
    let detailsInput = newCard.getElementsByClassName("merit-detail")[0];
    let rand = valueInput.dataset.id + "-" +  Math.random().toString(36).substring(2, 6);
    
    // Update all classes
    let collapsableElements = newCard.getElementsByClassName(`${valueInput.id}-text`);
    // // When the class name is removed, it is removed from the collapsableElements collection... Magic of js I guess.
    while (collapsableElements.length > 0) {
      let element = collapsableElements[0];
      element.classList.remove(`${valueInput.id}-text`);
      element.classList.add(`merit-${rand}-text`);
      element.dataset.bsTarget = `.merit-${rand}-text`;
    }
    header.dataset.bsTarget = `.merit-${rand}-text`;
    footer.dataset.bsTarget = `.merit-${rand}-text`;
    valueInput.id = `merit-${rand}`;
    valueInput.name = `character[merits][${rand}][level]`;
    detailsInput.name = `character[merits][${rand}][details]`;
    // reset the values for the new form
    valueInput.value = 0;
    detailsInput.value = "";
    card.parentNode.after(newCard);
  }

  checkPrerequisite({ detail: {type, target} }) {
    this.prerequisiteTargets.forEach(prerequisite => {
      let data = prerequisite.dataset;
      switch (type) {
        case 'merit':
          if ((target != null && data.name == target) || data.type == type) {
            if (typeof document.getElementsByName(`character[merits][${data.name}][level]`)[0] === 'undefined' || document.getElementsByName(`character[merits][${data.name}][level]`)[0].value >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
        case 'attribute':
          if ((target !== null && data.name == target) || data.type == type) {
            if (this.attr("attributes_" + data.name) >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
        case 'skill':
          if ((target !== null && data.name == target) || data.type == type) {
            if (this.attr("skills_" + data.name) >= data.value) {
              this.switch(prerequisite, "ok", "ko");
            } else {
              this.switch(prerequisite, "ko", "ok");
            }
          }
          break;
        default:
          if ((target !== null && data.name == target) || data.type == type) {
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

  filter(event) {
    let category = event.params.category;
    let fighting = event.params.fighting;
    let creation = event.params.creation;

    /** set the active button */
    this.filterTargets.forEach(element => {
      element.classList.remove("active");
    });
    event.currentTarget.classList.add("active");

    if (category !== undefined ) {
      this.checkCategory(category);
    } else if (fighting !== undefined) {
      this.checkFighting(fighting);
    } else if (creation !== undefined) {
      this.checkCreation(creation);
    }
  }

  checkCategory(category) {
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

  checkFighting(fighting) {
    this.meritTargets.forEach(merit => {
      let card = merit.closest('.card').parentElement;
      if (merit.dataset.fighting) {
        if (card.classList.contains('d-none')) {
          card.classList.remove('d-none')
        }
      } else {
        card.classList.add('d-none')
      }
    });
  }

  checkCreation(creation) {
    this.meritTargets.forEach(merit => {
      let card = merit.closest('.card').parentElement;
      if (merit.dataset.creation) {
        if (card.classList.contains('d-none')) {
          card.classList.remove('d-none')
        }
      } else {
        card.classList.add('d-none')
      }
    });
  }
}
