import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "merit",
    "prerequisite",
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
        // Should remove from the spendXp, probably with a dispatch event
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

  checkPrerequisite({ detail: {type, target} }) {
    this.prerequisiteTargets.forEach(prerequisite => {
      let data = prerequisite.dataset;
      switch (type) {
        case 'merit':
          if ((target != null && data.name == target) || data.type == type) {
            if (document.getElementsByName(`character[merits][${data.name}][level]`)[0].value >= data.value) {
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
