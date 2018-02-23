# Paragraphs Previewer Popup

Provides a rendered preview of a paragraphs item while on an entity form. Forked from drupal/paragraphs_previewer, this module takes a different approach to how it is associated with the Paragraphs widgets.

Enable the module and check "Enable preview" on a Paragraphs field widget (under Manage Form Display)

# Features

* Preview the rendered paragraph before saving the entity.
* Previewer can be enabled per field instance.
* Full width window to preview the design.
* Resizable window to preview responsive designs.

# Roadmap / Planned features

* Dynamic field labeling on the preview. To which elements of the design do the
  paragraph fields correspond?
* Dynamic fake data on the preview. I'm not sure what this component looks like--
  show me an example.

# Caveats

* Assumes modular component styling; preview will not include contextual styling
* You may need to add a "page--paragraphs-previewer-popup.html.twig" template to
  render your paragraphs without page wrappers.
