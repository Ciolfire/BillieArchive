<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'vampire' => [
        'path' => './assets/vampire.js',
        'entrypoint' => true,
    ],
    'mage' => [
        'path' => './assets/mage.js',
        'entrypoint' => true,
    ],
    'werewolf' => [
        'path' => './assets/werewolf.js',
        'entrypoint' => true,
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@hotwired/turbo' => [
        'version' => '8.0.20',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    '@toast-ui/editor' => [
        'version' => '3.2.2',
    ],
    'prosemirror-transform' => [
        'version' => '1.10.5',
    ],
    'prosemirror-state' => [
        'version' => '1.4.4',
    ],
    'prosemirror-keymap' => [
        'version' => '1.2.3',
    ],
    'prosemirror-commands' => [
        'version' => '1.7.1',
    ],
    'prosemirror-inputrules' => [
        'version' => '1.5.1',
    ],
    'prosemirror-history' => [
        'version' => '1.5.0',
    ],
    'orderedmap' => [
        'version' => '2.1.1',
    ],
    'w3c-keyname' => [
        'version' => '2.2.8',
    ],
    'rope-sequence' => [
        'version' => '1.3.4',
    ],
    'prosemirror-view/style/prosemirror.min.css' => [
        'version' => '1.41.4',
        'type' => 'css',
    ],
    '@toast-ui/editor-plugin-color-syntax' => [
        'version' => '3.1.0',
    ],
    'tui-color-picker' => [
        'version' => '2.2.8',
    ],
    '@toast-ui/editor/dist/toastui-editor.css' => [
        'version' => '3.2.2',
        'type' => 'css',
    ],
    '@toast-ui/editor/dist/theme/toastui-editor-dark.css' => [
        'version' => '3.2.2',
        'type' => 'css',
    ],
    'tui-color-picker/dist/tui-color-picker.css' => [
        'version' => '2.2.8',
        'type' => 'css',
    ],
    '@toast-ui/editor-plugin-color-syntax/dist/toastui-editor-plugin-color-syntax.css' => [
        'version' => '3.1.0',
        'type' => 'css',
    ],
    '@cropper/utils' => [
        'version' => '2.1.0',
    ],
    '@cropper/elements' => [
        'version' => '2.1.0',
    ],
    '@cropper/element' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-canvas' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-image' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-shade' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-handle' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-selection' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-grid' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-crosshair' => [
        'version' => '2.1.0',
    ],
    '@cropper/element-viewer' => [
        'version' => '2.1.0',
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    'prosemirror-model' => [
        'version' => '1.25.4',
    ],
    'prosemirror-view' => [
        'version' => '1.41.4',
    ],
    'cropperjs' => [
        'version' => '1.6.2',
    ],
    'cropperjs/dist/cropper.min.css' => [
        'version' => '1.6.2',
        'type' => 'css',
    ],
    '@toast-ui/editor/dist/i18n/fr-FR.js' => [
        'version' => '3.2.2',
    ],
];
