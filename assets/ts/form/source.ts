let form = document.querySelector('form');
if (form) {
  let book = form.querySelector('[id$="_book"]') as HTMLInputElement;
  if (book) {
    // We get the elements
    let page = form.querySelector('[id$="_page"]') as HTMLInputElement;
    let pageBlock = page.parentElement.parentElement as HTMLElement;
    let homebrew = form.querySelector('[id$="_homebrewFor"]') as HTMLInputElement;
    // Add listener on book
    book.addEventListener("change", (event) => {
      let target = event.target as HTMLInputElement;
      if (target.value) {
        pageBlock.style.display = 'flex';
        // We choose a book, remove the homebrew
        homebrew.value = "";
      } else {
        pageBlock.style.display = 'none';
      }
    });
    // Add listener on homebrew
    homebrew.addEventListener("change", (event) => {
      let target = event.target as HTMLInputElement;
      // We choose a homebrew, remove the book
      if (target.value) {
        book.value = "";
        page.value = "";
        book.dispatchEvent( new Event('change'));
      }
    });
    book.dispatchEvent( new Event('change'));
    homebrew.dispatchEvent( new Event('change'));
  }
}
