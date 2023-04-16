import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
    "form",
    "avatar",
  ];
  static values = {
    id: 0,
    src: "",
  }

  connect() {
    this.srcValue = this.avatarTarget.src;
  }

  update(event) {
    event.preventDefault();
    let xhttp = new XMLHttpRequest();
  
    const avatar = this.avatarTarget;
    avatar.src = this.srcValue;
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == XMLHttpRequest.DONE) {
        if (xhttp.status == 200) {
          //ok
          var timestamp = new Date().getTime();
          avatar.src = `${avatar.src}?t=${timestamp}`;
          console.log(avatar.src);
        } else if (xhttp.status == 204) {
          console.log('avatar not saved');
        }
      }
    };
    xhttp.open("POST", `/en/character/${this.idValue}/avatar/update`, true);
    xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest')
    xhttp.setRequestHeader("Cache-Control", "no-cache, no-store, max-age=0");
    xhttp.send(new FormData(this.formTarget));
  }
}