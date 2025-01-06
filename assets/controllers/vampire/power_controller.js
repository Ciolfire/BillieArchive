import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "modal"
  ];

  static value = {
    link: String,
  }

  connect()
  {
  }

  show(event)
  {
    this.linkValue = event.params.link;
    this.modalTarget.querySelector("#powerShowModalTitle").innerHTML = event.params.name;
    if (event.params.description != undefined) {
      this.modalTarget.querySelector("#powerShowModalDescription").innerHTML = event.params.description;
      this.modalTarget.querySelector("#powerShowModalDescription").classList.remove("d-none");
    } else {
      this.modalTarget.querySelector("#powerShowModalDescription").classList.add("d-none");
    }
    this.modalTarget.querySelector("#powerShowModalEffect").innerHTML = event.params.effect;
  }

  openWiki()
  {
    window.open(this.linkValue, '_blank').focus();
  }

  none()
  {
  }
}
