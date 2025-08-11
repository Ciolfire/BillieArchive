import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    'path',
    'pathDescription',
    'orderDescription',
    'order',
    'arcana',
    'arcanaTitle',
    'arcanaRuling',
    'arcanaOthers',
    'arcanaTotal',
    'arcanumValue',
    'submit',
  ];
  static values = {
    path: Number,
    rulingArcana: Array,
    inferiorArcanum: Number,
  }


  connect() {
    let orderNull = document.getElementsByClassName("awakening_form[order]-null")[0];
    orderNull.checked = true;
    orderNull.dataset.action = "click->character--awakening#orderUnpicked";
    this.pathTargets.forEach(path => {
      if (path.previousElementSibling.checked) {
        path.dispatchEvent(new Event("click"));
      }
    });
  }

  arcanaUpdate() {
    let ruling = [];
    let other = [];
    let rulingValid = false;
    let othersValid = false;
    let totalValid = false;
    
    this.arcanumValueTargets.forEach(arcanum => {
      if (this.rulingArcana.includes(arcanum.parentElement.dataset.id)) {
        if (Number(arcanum.value) != 0) {
          ruling.push(Number(arcanum.value));
        }
      } else {
        if (Number(arcanum.value) != 0) {
          other.push(Number(arcanum.value));
        }
      }
    });
    // Check if the ruling are ok
    let rSum = 0;
    if (ruling.length) {
      rSum = ruling.reduce((a,b)=>a+b);
    }
    this.arcanaRulingTarget.classList.add("ko");
    this.arcanaRulingTarget.classList.remove("ok");
    this.arcanaRulingTarget.innerText = ruling.length;
    if (ruling.length == 2)  {
      this.arcanaRulingTarget.innerText = 2;
      if (rSum >= 3 && rSum <= 5) {
        this.arcanaRulingTarget.classList.add("ok");
        this.arcanaRulingTarget.classList.remove("ko");
        rulingValid = true;
      }
    }
    // Check if other respect the format (max 2 differents)
    let oSum = 0;
    if (other.length) {
      oSum = other.reduce((a,b)=>a+b);
    }
    this.arcanaOthersTarget.innerText = oSum;
    this.arcanaOthersTarget.classList.add("ko");
    if ((ruling.some((arcanum) => arcanum == 3) && other.length == 1 || !ruling.some((arcanum) => arcanum == 3) && other.length >= 1 && other.length <= 2) && (oSum >= 1 && oSum <= 3)) {
      this.arcanaOthersTarget.classList.remove("ko");
      othersValid = true;
    }

    // Check if the total is ok (6)
    this.arcanaTotalTarget.classList.add("ko");
    this.arcanaTotalTarget.classList.remove("ok");
    this.arcanaTotalTarget.innerText = rSum + oSum;
    if (rSum + oSum == 6) {
      this.arcanaTotalTarget.classList.add("ok");
      this.arcanaTotalTarget.classList.remove("ko");
      totalValid = true;
    }

    // If all flag are valid, we allow submit
    this.submitTarget.classList.add("disabled");
    if (rulingValid && othersValid && totalValid) {
      this.submitTarget.classList.remove("disabled");
    }
  }

  pathPicked(event) {
    this.path = event.target.dataset.path;
    this.rulingArcana = event.target.dataset.rulingArcana.split(' ');
    this.inferiorArcanum = event.target.dataset.inferiorArcanum;
    
    this.toggle(this.pathDescriptionTargets, this.path);
    this.toggleArcana();
    this.arcanaUpdate();
    this.arcanaTitleTargets.forEach(element => {
      element.classList.remove('d-none');
    });
  }

  orderPicked(event) {
    this.toggle(this.orderDescriptionTargets, event.target.dataset.order);
  }

  orderUnpicked(event) {
    document.getElementsByClassName("awakening_form[order]-null")[0].checked = true;
    this.orderDescriptionTargets.forEach(element => {
      element.classList.add('d-none');
    });
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
        element.classList.add('d-none');
      } else {
        element.classList.remove('d-none');
      }
    });
  }

  toggle(elements, key) {
    elements.forEach(element => {
      if (key != undefined && element.classList.contains(key)) {
        element.classList.remove('d-none');
        return;
      }
      element.classList.add('d-none');
    });
  }

  toggleArcana() {
    this.arcanaTargets.forEach(arcanum => {
      // We get the wrapper and the input for this specific arcanum
      let block = arcanum.closest('.arcanum-block');
      let input = arcanum.getElementsByTagName('input')[0];
      block.classList.remove('d-none');
      block.classList.remove("order-last");
      // We want to display the ruling arcana first
      for (const key of this.rulingArcana) {
        if (arcanum.dataset.id == key) {
          block.classList.remove("order-2");
          arcanum.getElementsByTagName("label")[1].classList.remove("d-hide");
          arcanum.getElementsByTagName("label")[2].classList.remove("d-hide");
          return;
        }
      }
      // Not a ruling arcana, inferior or normal
      if (arcanum.dataset.id == this.inferiorArcanum) {
        block.classList.add("order-last");
      } else {
        block.classList.add("order-2");
      }
      if (input.value > 0) {
        input.value = 0;
        input.dispatchEvent(new Event("change"));
      }
    });
  }

  switch(target, show, hide) {
    target.classList.remove(hide);
    target.classList.add(show);
  }

  clean(event) {
    this.arcanumValueTargets.forEach(element => {
      if (element.value == 0) {
        element.setAttribute('name', '');
      }
    });
    console.debug("cleaned");
  }
}
