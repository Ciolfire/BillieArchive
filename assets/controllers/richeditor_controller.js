import { Controller } from "@hotwired/stimulus";


import { Editor } from '@toast-ui/editor';

import '@toast-ui/editor/dist/toastui-editor.css'; // Editor's Style
import '@toast-ui/editor/dist/theme/toastui-editor-dark.css'; // Editor's Style
// import '@toast-ui/editor/dist/i18n/fr-FR.js'; // Translation

// Color syntax Plugin
import 'tui-color-picker/dist/tui-color-picker.css';
import '@toast-ui/editor-plugin-color-syntax/dist/toastui-editor-plugin-color-syntax.css';


import colorSyntax from '@toast-ui/editor-plugin-color-syntax';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static editor;
  static field;

  connect() {
    let previewStyle = 'vertical';
    if (this.element.dataset.richPreviewStyle != undefined) {
      previewStyle = this.element.dataset.richPreviewStyle;
    }
    this.field = this.element;
    let wrapper = document.createElement("div");
    this.element.after(wrapper);
    this.editor = new Editor({
      el: wrapper,
      height: '30em',
      autofocus: false,
      // language: "fr-FR",
      initialEditType: 'wysiwyg',
      initialValue: this.element.value,
      previewStyle: previewStyle,
      theme: 'dark',
      plugins: [colorSyntax]
    });

    this.editor.addHook("change", () => {
      this.field.value = this.editor.getMarkdown();
    })
  }
}