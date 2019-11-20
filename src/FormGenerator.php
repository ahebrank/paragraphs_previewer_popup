<?php

namespace Drupal\paragraphs_previewer_popup;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\WidgetBase;

/**
 * Form creation/modification.
 */
class FormGenerator {

  /**
   * Inject the Preview button.
   */
  public static function modifyParagraphsTop(&$element, $form_state, $context) {
    $element['top']['#attributes']['class'][] = 'paragraphs-previewer--button-added';
    $container = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'paragraphs-previewer-popup__link-container'
        ],
      ],
      '#attached' => [
        'library' => [
          'paragraphs_previewer_popup/admin',
        ],
      ],
      'previewer_button' => self::getPreviewButton($context, $element, $form_state),
    ];

    // Compatibility with "experimental" widget.
    if (isset($element['top']['actions']['actions'])) {
      $container['#weight'] = 2;
      $container['#attributes']['class'][] = 'paragraphs-previewer-popup__link-container--experimental';
      $element['top']['actions']['actions']['previewer_container'] = $container;
    }
    else {
      $element['top']['previewer_container'] = $container;
    }
  }

  /**
   * Generate the button.
   */
  public static function getPreviewButton($context, $element, FormStateInterface $form_state) {
    $field_name = $context['items']->getName();
    $parents = $element['#field_parents'];
    $delta = $element['#delta'];

    if (!$parents) {
      return [];
    }

    $widget_state = WidgetBase::getWidgetState($parents, $field_name, $form_state);
    if (!isset($widget_state['paragraphs'][$delta]['mode']) ||
        !isset($widget_state['paragraphs'][$delta]['entity'])) {
      return $element;
    }

    $paragraphs_entity = $widget_state['paragraphs'][$delta]['entity'];
    $element_parents = array_merge($parents, [$field_name, $delta]);
    $id_prefix = implode('-', $element_parents);

    $previewer_element = [
      '#type' => 'submit',
      '#value' => t('Preview'),
      '#name' => strtr($id_prefix, '-', '_') . '_previewer_popup',
      '#weight' => 1,
      '#submit' => [['\Drupal\paragraphs_previewer_popup\FormHandlers', 'submitPreviewerItem']],
      '#field_item_parents' => $element_parents,
      '#limit_validation_errors' => [
        array_merge($parents, [$field_name, 'add_more']),
      ],
      '#delta' => $delta,
      '#ajax' => [
        'callback' => ['\Drupal\paragraphs_previewer_popup\FormHandlers', 'ajaxSubmitPreviewerItem'],
        'wrapper' => $widget_state['ajax_wrapper_id'],
        'effect' => 'fade',
      ],
      '#access' => $paragraphs_entity->access('view'),
      '#attributes' => [
        'class' => ['paragraphs-previewer-popup'],
      ],
      '#previewer_dialog_title' => isset($element['top']['paragraph_type_title']['info']['#markup']) ? strip_tags($element['top']['paragraph_type_title']['info']['#markup']) : t('Preview'),
    ];

    return $previewer_element;
  }

}
