import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller
{
  static targets = [
    "dot",
    "using",
    "usingInfo",
    "specialtyInput",
    "spend",
    "xpLogs",
    "ritualInput",
    "input",
  ];
  static values = {
    type: String,
    total: Number,
    used: Number,
    spend: Number,
    // Vampire
    ritualCurrent: {},
    coilsCurrent: {},
    coils: Number,
    // Mage
    //
    emptyInfo: String,
    costs: {
      'attribute': 5,
      'skill': 3,
      'specialty': 3,
      'merit': 2,
      'morality': 3,
      // surnatural
      'willpower': 8,
      // vampire
      'discipline': 7,
      'favoredDiscipline': 5,
      'potency': 8,
      // ghoul
      'ghoulDiscipline': 14,
      'ghoulFavoredDiscipline': 10,
      // mage
      'gnosis': 8,
      'arcanum-ruling': 6,
      'arcanum': 7,
      'arcanum-inferior': 8,
      //'rote': 2,
      // possessed
      'vestment': 10,
      'possessedVice': 10
    },
    vestments: Object,
    spendInfo: []
  }

  connect()
  {
    if (this.typeValue == "ghoul") {
      this.costsValue['discipline'] = this.costsValue['ghoulDiscipline'];
      this.costsValue['favoredDiscipline'] = this.costsValue['ghoulFavoredDiscipline'];
    }
    // Set the string for "no change" depending on language
    this.emptyInfoValue = this.usingInfoTarget.innerText;
    // For each element with dots, if it's higher than base level,
    // we spend the experience for it if it was selected before refresh
    this.dotTargets.forEach(target => {
      let data = target.parentElement.dataset;
      if (target.value > target.parentElement.dataset.dotBaseValue) {
        let event = {};

        event.params = {};
        event.params.id = data.id;
        event.params.type = data.type;
        event.params.name = data.name;
        event.params.value = +target.value;
        event.params.base = +data.dotMinValue;
        event.target = target;

        // We want to handle the coil differently
        if (data.coils == 1) {
          data.type = 'coil';
        }
        switch (data.type) {
          case 'willpower':
            this.payWillpower(event);
            break;
          case 'merit':
            this.payMerit(event);
            break;
          case 'coil':
            this.payCoil(event);
            break;
          default:
            this.pay(event);
        }
      }
    });
    // Update if the item is checked (for example, after refresh)
    this.devotionInputTargets.forEach(target => {
      let data = target.dataset;
      if (target.value == 1) {
        let event = {};

        event.params = {};
        event.params.id = target.id.replace('devotion-','');;
        event.params.type = "devotion";
        event.params.name = data.name;
        event.params.value = +data.value;
        event.target = target.parentElement;
        this.payDevotion(event, true);
      }
    });
    this.ritualInputTargets.forEach(target => {
      let data = target.dataset;
      if (target.value == 1) {
        let event = {};

        event.params = {};
        event.params.id = target.id.replace('ritual-','');
        event.params.sorceryId = data.sorceryId;
        event.params.type = "ritual";
        event.params.name = data.name;
        event.params.value = +data.value;
        event.target = target.parentElement;
        this.payRitual(event, true);
      }
    });
    this.inputTargets.forEach(target => {
      let data = target.dataset;
      if (target.value == 1) {
        console.debug("checked", target);
        let event = {};

        event.params = {};
        event.params.id = data.id;
        event.params.type = data.type;
        event.params.name = data.name;
        event.params.value = +data.value;
        event.target = target.parentElement;
        this.paySimple(event, true);
      }
    });
    // Used for prerequisite, secondary
    this.dispatch("change", { detail: { type: 'skill', target: null } });
    this.dispatch("change", { detail: { type: 'attribute', target: null } });
    this.dispatch("change", { detail: { type: 'merit', target: null } });
    if (this.hasDevotionInputTarget) {
      // TODO Check the prerequisite for devotions, to filter based on prerequisite
      // this.dispatch("change", { detail: { type: 'devotion', target: null } });
    }
  }

  checkUpdate(type, id)
  {
    switch (type) {
      case 'arcanum':
      case 'arcanum-ruling':
        console.log("dispatch arcana event");
        this.dispatch("arcana", { detail: { type: type, target: id } });
        break;
    
      default:
        this.dispatch("change", { detail: { type: type, target: id } });
        break;
    }
  }

  pay(event)
  {
    let params = event.params;
    // Get the cost for this specific dot
    let cost = this.calculateClassicCost(this.costsValue[params.type], params.base, params.value);
    this.allocate(cost, `${params.type}-${params.id}`, params);
    // character--edit:change->*** | used for prerequisites update, and others hook for change
    this.checkUpdate(params.type, params.id);
  }
  
  calculateClassicCost(cost, base, target, offset = 0)
  {
    base += offset;
    target += offset;

    return ((target * (target + 1)) - (base * (base + 1))) * cost / 2;
  }

  payMerit(event)
  {
    let params = event.params;
    // Get the cost for this specific dot
    let cost = this.calculateClassicCost(this.costsValue[params.type], params.base, params.value);
    this.allocate(cost, event.target.parentElement.parentElement.firstElementChild.id, params);
    // Prerequisites update
    this.dispatch("change", { detail: { type: params.type, target: params.id } });
  }

  payWillpower(event)
  {
    let params = event.params;
    // Get the cost for this specific dot
    let cost = this.costsValue[params.type] * (params.value - params.base);
    this.allocate(cost, event.target.parentElement.parentElement.firstElementChild.id, params);
    // Prerequisites update
    this.dispatch("change", { detail: { type: params.type, target: params.id } });
  }

  // SURNATURAL ON  //

  // SURNATURAL OFF //

  // VAMPIRE ON  //

  payCoil(event)
  {
    let params = event.params;
    let key = `coil-${params.id}`;
    let base = this.coilsValue;

    // First, we remove the coil if it exist with the same value
    if (this.coilsCurrentValue[key] != undefined && this.coilsCurrentValue[key].value == params.value) {
      // We remove this coil from both list, so we unset
      delete this.coilsCurrentValue[key];
      delete this.spendInfoValue[key];
    } else {
      // We add it to the specific coils list
      this.coilsCurrentValue[key] = {
        name: params.name,
        id: params.id,
        value: params.value,
        base: params.base,
        type: params.type
      };
    }


    // NOW => We need to check every coils to update the price
    for (const key in this.coilsCurrentValue) {
      let coil = this.coilsCurrentValue[key];
      let cost = this.costsValue[coil.type];

      this.spendInfoValue[key] = {
        type: coil.type,
        info: {
          name: coil.name,
          id: coil.id,
          cost: this.calculateClassicCost(cost, coil.base, coil.value, base - coil.base),
          value: coil.value,
          base: coil.base
        }
      };
      base += coil.value - coil.base;
    }

    console.log(this.coilsCurrentValue);
    this.updateSpend();
    // Prerequisites update
    // this.dispatch("change", { detail: { type: params.type, target: params.id } });
  }

  paySimple(event, refresh=false)
  {
    let params = event.params;
    let input = document.getElementById(`${params.type}-${params.id}`);

    console.debug(event.params, event.params.type, params.id);
    if (input.value == 0 || refresh == true) {
      event.target.classList.add('active');
      console.debug(input.value, event.target.classList);
      input.value = 1;
      this.spendInfoValue[`${params.type}-${params.id}`] = {
        type: params.type,
        info: {
          name: params.name,
          id: params.id,
          cost: params.value,
        }
      };
    } else {
      event.target.classList.remove('active');
      input.value = 0;
      // We cancel the change, so we unset
      this.spendInfoValue[`${params.type}-${params.id}`] = undefined;
      delete this.spendInfoValue[`${params.type}-${params.id}`];
    }
    // Get the cost for this specific dot
    this.updateSpend();
    // Prerequisites update ?
    // this.dispatch("change", { detail: { type: params.type, target: params.id } });
  }

  // Handle the case of the first free ritual of each level
  ritualRealCost(params) {
    let value = params.value;
    let level = params.value / 2;
    let id = params.sorceryId;
    let key = `ritual-${id}-${level}`;
    // Only if no free ritual already taken
    if (document.getElementById(key).dataset.firstFree == 1) {
      // Hard case :'(
      if (this.ritualCurrentValue[id] == undefined) {
        // Initialize Sorcery array
        this.ritualCurrentValue[id] = {};
      }
      if (this.ritualCurrentValue[id][level] == undefined) {
        // Initialize ritual level array
        this.ritualCurrentValue[id][level] = {};
      }
      if (Object.values(this.ritualCurrentValue[id][level]).every((v) => v === false)) {
        // Set the free ritual, since it's not found
        this.ritualCurrentValue[id][level][params.id] = true;
        value = 0;
      } else if (this.ritualCurrentValue[id][level][params.id] == undefined) {
        // Free ritual found, we set it as false, to allow a futur change of cost
        this.ritualCurrentValue[id][level][params.id] = false;
      }
    }

    return value;
  }

  payRitual(event, refresh = false)
  {
    let params = event.params;
    let key = `ritual-${params.sorceryId}-${params.id}`;
    let cost = this.ritualRealCost(params);
    let input = document.getElementById("ritual-" + params.id);

    if (input.value == 0 || refresh == true) {
      event.target.classList.add('active');
      input.value = 1;
      this.spendInfoValue[key] = {
        type: params.type,
        info: {
          name: params.name,
          id: params.id,
          cost: cost,
        }
      };
    } else {
      // We cancel the change, so we unset
      event.target.classList.remove('active');
      input.value = 0;
      // First we check if we need to apply the free cost to another ritual
      let level = params.value / 2;
      let needRefresh = false;
      if (this.ritualCurrentValue[params.sorceryId] && this.ritualCurrentValue[params.sorceryId][level]) {
        needRefresh = this.ritualCurrentValue[params.sorceryId][level][params.id];
      }

      this.ritualCurrentValue[params.sorceryId][level][params.id] = undefined;
      delete this.ritualCurrentValue[params.sorceryId][level][params.id];
      if (needRefresh && this.ritualCurrentValue[params.sorceryId][level] != undefined) {
        let keyR = Object.keys(this.ritualCurrentValue[params.sorceryId][level])[0];
        if (keyR != undefined) {
          this.ritualCurrentValue[params.sorceryId][level][keyR] = true;
          let replacement = `ritual-${params.sorceryId}-${keyR}`;
          this.spendInfoValue[replacement].info.cost = 0;
        }
      }
      this.spendInfoValue[key] = undefined;
      delete this.spendInfoValue[key];
    }
    // Get the cost for this specific dot
    this.updateSpend();
    // Prerequisites update, no need, nothing depend on rituals ?
    // this.dispatch("change", { detail: { type: params.type, target: params.id } });
  }

  // VAMPIRE OFF //

  // POSSESSED ON //
  payVestment(event, refresh = false)
  {
    let params = event.params;
    let key = `vestment-${params.id}`;
    let input = document.getElementById("vestment-" + params.id);
    let vestments = this.vestmentsValue;

    if (input.checked) {
      let cost = this.costsValue['vestment'];
      // We check if it's a normal vestment or if it's the first one (which is free)
      if (vestments[params.vice][params.level] > 0) {
        vestments[params.vice][params.level]++;
      } else if (vestments[params.vice][params.level] == undefined || vestments[params.vice][params.level] == 0) {
        cost = 0;
        vestments[params.vice]
        vestments[params.vice][params.level] = 1;
      }
      this.spendInfoValue[key] = {
        type: params.type,
        info: {
          name: params.name,
          id: params.id,
          cost: cost,
        }
      };
    } else {
      // We cancel the change, so we unset
      this.spendInfoValue[key] = undefined;
      vestments[params.vice][params.level]--;
      delete this.spendInfoValue[key];
    }
    this.vestmentsValue = vestments;
    // Get the cost for this specific dot
    this.updateSpend();
  }
  // POSSESSED OFF //

  allocate(cost, key, params)
  {
    // If the entry already exist and it's the same value
    if (
      (this.spendInfoValue[key] != null && this.spendInfoValue[key]['info']['cost'] == cost) ||
      params.value <= params.base
    ) {
      // We cancel the change, so we unset
      delete this.spendInfoValue[key];
    } else {
      // We save this edit in the list, to show to the user
      this.spendInfoValue[key] = {
        type: params.type,
        info: {
          name: params.name,
          id: params.id,
          cost: cost,
          value: params.value,
          base: params.base
        }
      };
    }
    this.updateSpend();
  }

  updateSpend()
  {
    let total = 0;
    let text = "";

    for (var key in this.spendInfoValue) {
      let current = this.spendInfoValue[key];
      let info = null;
      if (current == null) {
        continue;
      }
      switch (current.type) {
        case 'specialty':
          info = current.info;
          total += info.cost;
          text += `${info['skill']} ➔ ${info['name']} (${info['cost']})</br>`;
          break;

        case 'devotion':
        case 'ritual':
        case 'vestment':
        case 'rote':
          info = current.info;
          total += info.cost;
          text += `${info['name']} (${info['cost']})</br>`;
          break;
        default:
          info = current.info;
          total += info.cost;
          text += `${info['name']} ${info['base']}➔${info['value']} (${info['cost']})</br>`;
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

  showRituals(event)
  {
    let params = event.params;
    let key = params.type + '-' + params.id;
    let level = params.value;
    // The pay function goes first, if the entry is deleted that means we unselected it, genius :) !
    if (this.spendInfoValue[key] == null) {
      level = params.base;
    }
    let rituals = document.getElementsByClassName('ritual-element-' + params.id);
    // For each ritual, we only show it if it's available :)
    // If it's selected, it stays selected :(
    for (const ritual of rituals) {
      if (ritual.dataset.level <= level) {
        ritual.classList.add('show');
        ritual.classList.remove('collapse');
      } else {
        ritual.classList.add('collapse');
        ritual.classList.remove('show');
      }
    }
  }

  newSpecialty(event)
  {
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
    newSpecialty.getElementsByTagName('input')[0].setAttribute("name", `character_form[specialties][${event.params.skill}][${rand}]`);
    event.target.closest('.row').after(newSpecialty);
    this.updateSpend();
  }

  removeSpecialty(event)
  {
    let element = event.target.closest('.new-specialty');

    this.spendInfoValue[element.id] = null;
    element.parentNode.removeChild(element);
    this.updateSpend();
  }

  removeElements(type)
  {
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

  // We remove all unused specialties, both from form and logs
  cleanSpecialty(id, entry)
  {
    if (entry.type == "specialty") {
      let specialty = document.getElementById(id).getElementsByTagName("input")[0];
      if (specialty.value != "") {
        entry.info.name = specialty.value;
      } else {
        delete this.spendInfoValue[id];
        specialty.parentNode.removeChild(specialty);
      }

      return true;
    }
    return false;
  }

  // For merits with expanded details, we save them in the logs before commiting
  getMeritDetails(id, entry)
  {
    if (entry.type == "merit") {
      let details = undefined;
      let key = id.replace('merit-','');

      if (entry.info.base === 0) {
        details = document.getElementsByName(`${this.typeValue}[merits][${key}][details]`)[0];
      } else {
        details = document.getElementsByName(`${this.typeValue}[meritsUp][${+key}][details]`)[0];
      }
      if (typeof details !== "undefined") {
        // details found for this merit
        entry.info.details = details.value;
      }

      return true;
    }

    return false;
  }

  clean()
  {
    for (const id in this.spendInfoValue) {
      let entry = this.spendInfoValue[id];
      
      if (entry != null) {
        if (this.cleanSpecialty(id, entry)) {
          continue;
        }
        if (this.getMeritDetails(id, entry)) {
          continue;
        }
      } else {
        // Entry not cleaned properly, we remove it
        delete this.spendInfoValue[id];
      }
    }
    // remove all elements with no point spent
    this.removeElements('merit');
    // Yes, they don't exist for non-vampire, yes I don't care :)
    this.removeElements('discipline');
    this.removeElements('devotion');
    this.removeElements('ritual');
    // Yes, they don't exist for non-mage, yes I don't care :)
    this.removeElements('arcanum');
    this.removeElements('rote');
    this.xpLogsTarget.value =  JSON.stringify(Object.assign({}, this.spendInfoValue));
    // console.log(document.forms);
    document.forms['character_form'].submit();
  }
}
