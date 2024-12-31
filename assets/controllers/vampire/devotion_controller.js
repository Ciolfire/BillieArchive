import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal"
  ];

  static value = {
    "id": 0
  }

  connect()
  {
  }

  show(event)
  {
    this.idValue = event.params.id;
    this.modalTarget.querySelector("#devotionShowModalTitle").innerHTML = event.params.name;
    this.modalTarget.querySelector("#devotionShowModalDescription").innerHTML = event.params.effect;
  }

  openWiki(event)
  {
    let link = event.params.link.replace('0', this.idValue);
    window.open(link, '_blank').focus();
  }
}
