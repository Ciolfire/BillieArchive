// does not work for now
// document.onreadystatechange = function()
// {
//   if(document.readyState === 'complete')
//   {
//     watchSource();
//   }
// }

// function watchSource() {
//   let forms = Array.from(document.getElementsByTagName('form'));

//   forms.forEach(form => {
//     console.debug(`form found: ${form}`);
//     const book = form.querySelector('[id$="_book"]');
//     if (book) {
//       // We get the elements
//       let page = form.querySelector('[id$="_page"]');
//       let pageBlock = page.parentElement.parentElement;
//       let homebrew = form.querySelector('[id$="_homebrewFor"]');
//       // Add listener on book
//       book.addEventListener("change", (event) => {
//         let target = event.target;
//         if (target.value) {
//           pageBlock.style.display = 'flex';
//           // We choose a book, remove the  if needed
//           if (homebrew != undefined) {
//             homebrew.value = "";
//           }
//         } else {
//           pageBlock.style.display = 'none';
//         }
//       });
//       // Add listener on homebrew
//       if (homebrew != undefined) {
//         homebrew.addEventListener("change", (event) => {
//           let target = event.target;
//           // We choose a homebrew, remove the book
//           if (target.value) {
//             book.value = "";
//             page.value = "";
//             book.dispatchEvent( new Event('change'));
//           }
//         });
//         homebrew.dispatchEvent( new Event('change'));
//       }
//       book.dispatchEvent( new Event('change'));
//     }
//   });
// }

let forms = Array.from(document.getElementsByTagName('form'));

forms.forEach(form => {
  console.debug(`form found: ${form}`);
  const book = form.querySelector('[id$="_book"]');
  if (book) {
    // We get the elements
    let page = form.querySelector('[id$="_page"]');
    let pageBlock = page.parentElement.parentElement;
    let homebrew = form.querySelector('[id$="_homebrewFor"]');
    // Add listener on book
    book.addEventListener("change", (event) => {
      let target = event.target;
      if (target.value) {
        pageBlock.style.display = 'flex';
        // We choose a book, remove the  if needed
        if (homebrew != undefined) {
          homebrew.value = "";
        }
      } else {
        pageBlock.style.display = 'none';
      }
    });
    // Add listener on homebrew
    if (homebrew != undefined) {
      homebrew.addEventListener("change", (event) => {
        let target = event.target;
        // We choose a homebrew, remove the book
        if (target.value) {
          book.value = "";
          page.value = "";
          book.dispatchEvent( new Event('change'));
        }
      });
      homebrew.dispatchEvent( new Event('change'));
    }
    book.dispatchEvent( new Event('change'));
  }
});