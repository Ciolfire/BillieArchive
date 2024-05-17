import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "btn",
    "right",
  ];
  static values = {
  }

  update(event) {
    let rights = [];
    console.log(event.target);
    console.log(this.btnTargets);
    console.log(this.formTarget);
    console.log(this.rightTargets);

    this.btnTargets.forEach(btn => {
      btn.classList.remove("active");
    })
    event.target.classList.add("active");

    switch (event.target.name) {
      case 'heard':
        rights = [
          'firstname',
          'lastname',
          'description',
        ]
        break;
      case 'seen':
        rights = [
          'avatar',

          'potency',
        ]
        break;
      case 'acquaintance':
        rights = [
          'firstname',
          'lastname',
          'nickname',
          'avatar',
          'description',

          'clan',
          'potency',
        ]
        break;
      case 'know':
        rights = [
          'type',
          'firstname',
          'lastname',
          'nickname',
          'avatar',
          'age',
          'virtue',
          'vice',
          'morality',
          'description',
          'faction',
          'group',

          'clan',
          'sire',
          'embrace',
          'potency',
        ]
        break;
    }

    this.rightTargets.forEach(element => {
      if (rights.includes(element.value) || rights.length == 0) {
        if (!element.checked) {
          element.click();
        }
      } else {
        if (element.checked) {
          element.click();
        }
      }
    })

    const form = this.formTarget;
    //this.itemTargets.forEach
  }
}