import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "dot",
    "using",
    "usingInfo"
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
        console.log(data.name, data.dotMinValue, target.value, data.type);
        this.payDot(data.name, data.dotMinValue, target.value, data.type);
      }
    });
  }

  pay(event) {
    this.payDot(event.params.name, event.params.min, event.params.value, event.params.type);
  }

  payDot(name, min, value, type) {
    let cost = this.calculateCost(this.costsValue[type], +min + 1, value);
    if (this.spendInfoValue[name] != null && this.spendInfoValue[name]['cost'] == cost) {
      console.log("erase");
      this.spendInfoValue[name] = null;
    } else {
      this.spendInfoValue[name] = {'cost': cost, 'value': value, 'min': min};
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
      let info = this.spendInfoValue[key];
      if (info != null) {
        total += info['cost'];
        text += `${key} ${info['min']}➔${info['value']} (${info['cost']})</br>`;
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
}
