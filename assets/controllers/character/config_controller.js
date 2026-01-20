import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = [
  ];
  static values = {
    character: 0,
  }

  connect() {
  }

  setSociety(event) {
    let method = "remove";
    if (event.currentTarget.checked) {
      method = "add";
    }
    window
    .fetch(`/en/society/${event.params.society}/${method}/character/${this.characterValue}`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    // No data treatment neeed for now, maybe add some error check later
  }

  setStatus(event) {
    window
    .fetch(`/en/character/${this.characterValue}/status/update`, {
      headers: {
        "Content-Type": "application/json",
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        'status': event.params.status
      }),
      method: "POST"
    });
    // No data treatment neeed for now, maybe add some error check later
  }
}