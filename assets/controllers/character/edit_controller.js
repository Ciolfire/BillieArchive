import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "dot",
    "using",
    "usingInfo",
    "specialtyInput"
  ];
  static values = {
    total: Number,
    used: Number,
    spend: Number,
    emptyInfo: String,
    costs: {
      'attribute': 5,
      'skill': 3,
      'specialty': 3,
      'merit': 2,
      'morality': 3,
    },
    spendInfo: []
  }

  connect() {
    this.emptyInfoValue = this.usingInfoTarget.innerText;
    this.dotTargets.forEach(target => {
      let data = target.parentElement.dataset;
      if (target.value > target.parentElement.dataset.dotBaseValue) {
        this.payDot(data.name, data.dotMinValue, target.value, data.type);
      }
    });
    this.dispatch("change", { detail: { type: 'skill', target: null } });
    this.dispatch("change", { detail: { type: 'attribute', target: null } });
  }

  pay(event) {
    this.payDot(event.params.name, event.params.min, event.params.value, event.params.type);

    let changed = event.target.getAttribute("for").split('-')[0];
    this.dispatch("change", { detail: { type: event.params.type, target: changed } });
  }

  payDot(name, min, value, type) {
    let cost = this.calculateCost(this.costsValue[type], +min + 1, value);
    console.log(name, min, value, type);
    if ((this.spendInfoValue[name] != null && this.spendInfoValue[name]['info']['cost'] == cost) || value <= min) {
      this.spendInfoValue[name] = null;
    } else {
      this.spendInfoValue[name] = {
        type: type,
        info: {
          cost: cost,
          value: value,
          min: min
        }
      };
    }
    this.updateSpend();
  }
  
  calculateCost(cost, min, value) {
    let total = 0;
    
    for (let i = min; i <= value; i++) {
      total = total + i * cost;
    }

    return total;
  }

  updateSpend() {
    let total = 0;
    let text = "";

    for (var key in this.spendInfoValue) {
      let current = this.spendInfoValue[key];
      if (current != null && current.type != "specialty") {
        let info = current.info;
        total += info.cost;
        text += `${key} ${info['min']}➔${info['value']} (${info['cost']})</br>`;
      } else if (current != null && current.type == "specialty") {
        let info = current.info;
        total += info.cost;
        text += `${info['skill']} ➔ ${info['name']} (${info['cost']})</br>`;
      }
    }
    this.spendValue = total;
    this.usingTarget.innerText = this.spendValue;
    if (this.usedValue + this.spendValue > this.totalValue) {
      this.usingTarget.innerHTML = `<span class="ko">${this.usingTarget.innerText}</span>`;
    }
    if (text == "") {
      text = this.emptyInfoValue;
    }
    this.usingInfoTarget.innerHTML = text;
  }

  newSpecialty(event) {
    let newSpecialty = this.specialtyInputTarget.cloneNode(true);
    let r = Math.random().toString(36).substring(2, 6);
    this.spendInfoValue[r] = {
      type: 'specialty',
      info: {
        name: newSpecialty.dataset.trans,
        skill: event.params.trans,
        cost: this.costsValue['specialty']
      }
    };
    newSpecialty.id = r;
    newSpecialty.getElementsByTagName('input')[0].setAttribute("name", `character[specialties][${event.params.skill}][${r}]`);
    event.target.closest('.row').after(newSpecialty);
    this.updateSpend();
  }

  removeSpecialty(event) {

    let element = event.target.closest('.new-specialty');
    this.spendInfoValue[element.id] = null;
    element.parentNode.removeChild(element);
    this.updateSpend();
  }
}
