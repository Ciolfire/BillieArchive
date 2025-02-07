import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "form",
    "avatar",
    "updatable",
    "resize",
  ];
  static values = {
    id: 0,
  }

  update(event) {
    event.preventDefault();
    let xhttp = new XMLHttpRequest();
  
    const avatar = this.avatarTarget;
    const updatables = this.updatableTargets;
    let oldSrc = avatar.src;
    // temporary update of the image while it's loading :)
    avatar.src = "/images/loading.gif";
    
    window
    .fetch(`/en/character/${this.idValue}/avatar/update`, {
      headers: {
        "Cache-Control": "no-cache, no-store, max-age=0",
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new FormData(this.formTarget),
      method: "POST"
    })
    .then((response) => {
      if (!response.ok) {
        console.log('avatar not saved');
        avatar.src = oldSrc;
      }

      return response.json();
    })
    .then((response) => {
      //ok
      console.log(response);
      let timestamp = new Date().getTime();
      let newSrc = `/images/characters/${response}?t=${timestamp}`;
      avatar.src = newSrc;
      updatables.forEach(element => {
        element.src = newSrc;
      });
      this.resizeTarget.classList.remove("d-none");
    });
  }
}