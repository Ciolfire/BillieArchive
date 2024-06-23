import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "form",
    "avatar",
    "updatable",
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
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        if (xhttp.status == 200) {
          //ok
          // let newSrc = JSON.parse(xhttp.response);
          let timestamp = new Date().getTime();
          let newSrc = `/images/characters/${JSON.parse(xhttp.response)}?t=${timestamp}`;
          avatar.src = newSrc;
          updatables.forEach(element => {
            element.src = newSrc;
          });
        } else {
          console.log('avatar not saved');
          avatar.src = oldSrc;
        }
      }
    };
    // temporary update of the image while it's loading :)
    avatar.src = "/images/loading.gif";
    xhttp.open("POST", `/en/character/${this.idValue}/avatar/update`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, max-age=0");
    xhttp.send(new FormData(this.formTarget));
  }
}