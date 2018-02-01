(function ($, Drupal, drupalSettings) {

  /**
   * Set the initial dialog settings based on client side information.
   *
   * @param Drupal.dialog dialog
   *   The dialog object
   * @param jQuery $element
   *   The element jQuery object.
   * @param object $settings
   *   Optional The combined dialog settings.
   */
  dialogInitialize = function(dialog, $element, settings) {
    var windowHeight = $(window).height();
    if (windowHeight > 0) {
      // Set maxHeight based on calculated pixels.
      // Setting a relative value (100%) server side did not allow scrolling
      // within the modal.
      settings.maxHeight = windowHeight;
    }
  };

  /**
   * Set the dialog settings based on the content.
   *
   * @param Drupal.dialog dialog
   *   The dialog object
   * @param jQuery $element
   *   The element jQuery object.
   * @param object $settings
   *   The combined dialog settings.
   */
  dialogUpdateForContent = function(dialog, $element, settings) {
    var $content = $('.paragraphs-previewer-iframe', $element).contents().find('body');

    if ($content.length) {
      // Fit content.
      var contentHeight = $content.outerHeight();
      var modalContentContainerHeight = $element.height();

      var fitHeight;
      if (contentHeight < modalContentContainerHeight) {
        var modalHeight = $element.parent().outerHeight();
        var modalNonContentHeight = modalHeight - modalContentContainerHeight;
        fitHeight = contentHeight + modalNonContentHeight;
      }
      else {
        fitHeight = 0.98 * settings.maxHeight;
      }

      // Set to the new height bounded by min and max.
      var newHeight = fitHeight;
      if (fitHeight < settings.minHeight) {
          newHeight = settings.minHeight;
      }
      else if (fitHeight > settings.maxHeight) {
        newHeight = settings.maxHeight;
      }
      settings.height = newHeight;
      $element.dialog('option', 'height', settings.height);
    }
  };

  // Dialog listeners.
  Drupal.behaviors.paragraphs_previewer_dialog = {
    attach: function() {
      $(window).on({
        'dialog:beforecreate': function (event, dialog, $element, settings) {
          dialogInitialize(dialog, $element, settings);
        },
        'dialog:aftercreate': function (event, dialog, $element, settings) {
          // Set body class to disable scrolling.
          $('body').addClass('paragraphs-previewer-dialog-active');
          // Adjust dialog after all content is loaded.
          $('.paragraphs-previewer-iframe').on('load', function() {
            $element.addClass('paragraphs-previewer-dialog-loaded');
            dialogUpdateForContent(dialog, $element, settings);
          });
        },
        'dialog:afterclose': function (event, dialog, $element) {
          // Remove body class to enable scrolling in the parent window.
          $('body').removeClass('paragraphs-previewer-dialog-active');
        }
      });
    }
  };

})(jQuery, Drupal, drupalSettings);
