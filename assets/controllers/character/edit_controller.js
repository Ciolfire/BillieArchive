import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "dot",
    "using",
    "usingInfo",
    "specialtyInput",
    "spend",
    "xpLogs",
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
      'willpower': 8,
      'discipline': 7,
      'favoredDiscipline': 5,
      'potency': 8,
    },
    spendInfo: []
  }

  connect() {
    this.emptyInfoValue = this.usingInfoTarget.innerText;
    this.dotTargets.forEach(target => {
      let data = target.parentElement.dataset;
      if (target.value > target.parentElement.dataset.dotBaseValue) {
        console.debug(target);
        console.debug(target.parentElement);
        console.debug(data);
        let key = data.id;

        if (data.type == "merit") {
          key = target.id;
        }
        this.payDot(key, data.name, data.dotMinValue, target.value, data.type);
      }
    });
    this.dispatch("change", { detail: { type: 'skill', target: null } });
    this.dispatch("change", { detail: { type: 'attribute', target: null } });
  }

  pay(event) {
    let key = event.params.id;

    if (event.params.type == "merit") {
      key = event.target.parentElement.parentElement.firstElementChild.id;
    }
    this.payDot(key, event.params.name, event.params.min, event.params.value, event.params.type);

    let changed = event.target.getAttribute("for").split('-')[0];
    this.dispatch("change", { detail: { type: event.params.type, target: changed } });
  }

  payDot(id, name, min, value, type) {
    let cost = this.calculateCost(this.costsValue[type], +min, value, type);

    if ((this.spendInfoValue[id] != null && this.spendInfoValue[id]['info']['cost'] == cost) || value <= min) {
      this.spendInfoValue[id] = null;
    } else {
      this.spendInfoValue[id] = {
        type: type,
        info: {
          name: name,
          id: id,
          cost: cost,
          value: value,
          min: min
        }
      };
    }
    this.updateSpend();
  }
  
  calculateCost(cost, min, value, type) {
    let total = 0;
    
    if (type != 'willpower') {
      for (let i = min+1; i <= value; i++) {
        total += i * cost;
      }
    } else {
      total = cost * (value - min);
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
        text += `${info['name']} ${info['min']}➔${info['value']} (${info['cost']})</br>`;
      } else if (current != null && current.type == "specialty") {
        let info = current.info;
        total += info.cost;
        text += `${info['skill']} ➔ ${info['name']} (${info['cost']})</br>`;
      }
    }
    this.spendValue = total;
    this.spendTarget.value = total;
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
    let rand = Math.random().toString(36).substring(2, 6);

    this.spendInfoValue[rand] = {
      type: 'specialty',
      info: {
        id: event.params.skill,
        name: newSpecialty.dataset.trans,
        skill: event.params.trans,
        cost: this.costsValue['specialty']
      }
    };
    newSpecialty.id = rand;
    newSpecialty.getElementsByTagName('input')[0].setAttribute("name", `character[specialties][${event.params.skill}][${rand}]`);
    event.target.closest('.row').after(newSpecialty);
    this.updateSpend();
    console.debug(this.spendInfoValue);
  }

  removeSpecialty(event) {
    let element = event.target.closest('.new-specialty');

    this.spendInfoValue[element.id] = null;
    element.parentNode.removeChild(element);
    this.updateSpend();
  }

  removeElements(type) {
    let elements = document.getElementsByClassName(`${type}-value`);
    for (const element of elements) {
      if (element.value == 0) {
        let name = element.getAttribute('name');
        element.setAttribute('name', '');
        let detail = document.getElementsByName(name.replace('level', 'details'))[0];
        if (detail) {
          detail.setAttribute('name', '');
        }
      }
    }
  }

  clean() {
    
    for (const id in this.spendInfoValue) {
      let entry = this.spendInfoValue[id];
      
      if (entry.type == "specialty") {
        entry.info.name = document.getElementById(id).getElementsByTagName("input")[0].value;
      } else if (entry.type == "merit") {
        entry.info.id = entry.info.id.replace('merit-','');
        let details = document.getElementsByName(`character[merits][${entry.info.id}][details]`)[0];
        if (typeof details !== "undefined") {
          entry.info.details = details.value;
        }
      }
    }
    this.removeElements('merit');
    this.removeElements('discipline');
    this.xpLogsTarget.value =  JSON.stringify(Object.assign({}, this.spendInfoValue));
    document.forms['character'].submit();
  }
}
