# Paragraphs Previewer

Provides a rendered preview of a paragraphs item while on an entity form.

# Features

* Preview the rendered paragraph before saving the entity.
* Previewer can be enabled per field instance.
* Full width window to preview the design.
* Resizable window to preview responsive designs.

# Caveats

* Preview popup uses the front end theme to style the rendered markup.  This
  assumes that all styling is applied to that paragraph's markup and does not
  need any other page context / wrapping markup, example node markup.
* You may need to add a "page--paragraphs-previewer.html.twig" template to
  render your paragraphs without page wrappers.

# Installation

* Install "Paragraphs Previewer" module.
* Check "Enable preview" on a Paragraphs field widget (under Manage Form Display)

# Requirements

* Paragraphs module (https://www.drupal.org/project/paragraphs)
