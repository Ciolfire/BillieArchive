import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "merit",
    "prerequisite",
    "filter",
    "modal",
    "relation",
    "choice"
  ];

  static value = {
    "id": 0,
  }

  connect()
  {
    this.checkPrerequisite({ detail: {type: 'race', target: null } });
    // Generate the extra cards if needed
    this.meritTargets.forEach(merit => {
      // the merit is not owned by the character and can be bought more than once
      if (-1 == merit.name.indexOf('meritsUp') && false == merit.dataset.unique) {
        // the merit has a value of 1 or more
        if (merit.value > 0) {
          let card = merit.closest(".block");
          let merits = document.getElementsByName(card.attributes.name.value);
          if (this.checkNeedGeneration(merits)) {
            this.meritGeneration(card, merits.length);
          }
        }
      }
    });
  }

  show(event)
  {
    this.idValue = event.params.id;
    if (event.params.relation != undefined) {
      // edit link for relation
      this.relationTarget.href = this.relationTarget.dataset.link.replace('0', event.params.relation);
      this.relationTarget.classList.remove('d-none');
      // hide link for choice
      this.choiceTarget.classList.add('d-none');
      this.choiceTarget.href = null;
    } else if (event.params.chmid) {
      // edit link for choice
      this.choiceTarget.href = this.choiceTarget.dataset.link.replace('0', event.params.chmid);
      this.choiceTarget.classList.remove('d-none');
      // hide link for relation
      this.relationTarget.classList.add('d-none');
      this.relationTarget.href = null;
    } else {
      this.choiceTarget.classList.add('d-none');
      this.choiceTarget.href = null;
      this.relationTarget.classList.add('d-none');
      this.relationTarget.href = null;
    }
    if (event.params.choice != undefined && event.params.choice.length > 0) {
      this.modalTarget.querySelector("#meritShowModalTitle").innerHTML = `${event.params.name} (${event.params.choice})`;
    } else {
      this.modalTarget.querySelector("#meritShowModalTitle").innerHTML = event.params.name;
    }
    this.modalTarget.querySelector("#MeritShowModalDescription").innerHTML = event.params.effect;
  }

  openWiki(event)
  {
    if (this.idValue != 0) {
      let link = event.params.link.replace('0', this.idValue);
      window.open(link, '_blank').focus();
    }
  }

  add(event)
  {
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

  checkNeedGeneration(merits)
  {
    var needNew = true;
      merits.forEach(merit => {
        if (merit.getElementsByClassName("merit-value")[0].value == 0) {
          needNew = false;
          return;
        }
      });
      
      return needNew;
  }

  meritGeneration(card, length)
  {
    
    let newCard = card.parentNode.cloneNode(true);
    
    let valueInput = newCard.getElementsByClassName("merit-value")[0];
    let detailsInput = newCard.getElementsByClassName("merit-detail")[0];
    let rand = valueInput.dataset.id + "-" +  Math.random().toString(36).substring(2, 6);

    // This is to save the merit
    valueInput.id = `merit-${rand}`;
    valueInput.name = `character_form[merits][${rand}][level]`;
    detailsInput.name = `character_form[merits][${rand}][details]`;
    
    // reset the values for the new form
    valueInput.value = 0;
    detailsInput.value = "";
    
    // We add it after the same card
    card.parentNode.after(newCard);
  }

  checkPrerequisite({ detail: {type, target} })
  {
    this.prerequisiteTargets.forEach(prerequisite => {
      let data = prerequisite.dataset;
      switch (type) {
        case 'merit':
          if ((target != null && data.name == target) || data.type == type) {
            // We make sure that at least one of the element reach the required level
            if (Array.from(document.querySelectorAll(`.merit-value[data-real-id='${data.name}']`)).some(el => el.value >= data.value)) {
              this.switch(prerequisite.getElementsByTagName('a')[0], "accent", "ko");
            } else {
              this.switch(prerequisite.getElementsByTagName('a')[0], "ko", "accent");
            }
          }
          break;
        case 'attribute':
          if ((target !== null && data.name == target) || data.type == type) {
            if (document.getElementById(`character_form_attributes_${data.name}`).value >= data.value) {
            // if (this.attr("attributes_" + data.name) >= data.value) {
              this.switch(prerequisite, "accent", "ko");
            } else {
              this.switch(prerequisite, "ko", "accent");
            }
          }
          break;
        case 'skill':
          if ((target !== null && data.name == target) || data.type == type) {
            if (document.getElementById(`character_form_skills_${data.name}`).value >= data.value) {
            // if (this.attr("skills_" + data.name) >= data.value) {
              this.switch(prerequisite, "accent", "ko");
            } else {
              this.switch(prerequisite, "ko", "accent");
            }
          }
          break;
        default:
          if ((target !== null && data.name == target) || data.type == type) {
            if (this.attr(data.name) >= data.value) {
              this.switch(prerequisite, "accent", "ko");
            } else {
              this.switch(prerequisite, "ko", "accent");
            }
          }
          break;
      }
    });
  }

  attr(name)
  {
    let attr = document.getElementById(`character_${name}`);
    if (attr) {
      return +attr.value;
    } else {
      console.log("attribute not found: " + name);
    }
  }

  cost(targets, type)
  {
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

  switch(target, show, hide)
  {
    target.classList.remove(hide);
    target.classList.add(show);
  }

  filter(event)
  {
    let category = event.params.category;
    let fighting = event.params.fighting;
    let creation = event.params.creation;
    let race     = event.params.race;

    /** set the active button */
    this.filterTargets.forEach(element => {
      element.classList.remove("active");
    });
    event.currentTarget.classList.add("active");

    if (category !== undefined ) {
      this.checkCategory(category);
    } else if (fighting !== undefined) {
      this.checkFighting();
    } else if (creation !== undefined) {
      this.checkCreation();
    } else if (race !== undefined) {
      this.checkRace(race);
    }
  }

  checkCategory(category)
  {
    this.meritTargets.forEach(merit => {
      let card = merit.closest('.card').parentElement;
      if (merit.dataset.fighting != true && (merit.dataset.category == category) || category == "") {
        if (card.classList.contains('d-none')) {
          card.classList.remove('d-none')
        }
      } else {
        card.classList.add('d-none')
      }
    });
  }

  checkFighting()
  {
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

  checkCreation()
  {
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

  checkRace(race)
  {
    this.meritTargets.forEach(merit => {
      let card = merit.closest('.card').parentElement;
      if (merit.dataset.race == race) {
        if (card.classList.contains('d-none')) {
          card.classList.remove('d-none')
        }
      } else {
        card.classList.add('d-none')
      }
    });
  }
}
