<?php

namespace Drupal\paragraphs_previewer;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\WidgetBase;

class FormGenerator {
    public static function modifyParagraphsTop(&$element, $form_state, $context) {
        $links = $element['top']['links'];
        unset($element['top']['links']);
        $element['top']['link_container'] = [
            '#type' => 'container',
            '#attributes' => [
            'class' => 'paragraphs-previewer__link-container',
            ],
            '#attached' => [
            'library' => [
                'paragraphs_previewer/admin',
            ],
            ],
            'previewer_button' => self::getPreviewButton($context, $element, $form_state),
            'links' => $links,
        ];
    }

    public static function getPreviewButton($context, $element, FormStateInterface $form_state) {
        $field_name = $context['items']->getName();
        $parents = $element['#field_parents'];
        $delta = $element['#delta'];

        $widget_state = WidgetBase::getWidgetState($parents, $field_name, $form_state);
        if (!isset($widget_state['paragraphs'][$delta]['mode']) ||
                !isset($widget_state['paragraphs'][$delta]['entity'])) {
            return $element;
        }

        $paragraphs_entity = $widget_state['paragraphs'][$delta]['entity'];
        $element_parents = array_merge($parents, [$field_name, $delta]);
        $id_prefix = implode('-', $element_parents);
        
        $paragraphs_entity = $widget_state['paragraphs'][$delta]['entity'];
        $element_parents = array_merge($parents, [$field_name, $delta]);
        $id_prefix = implode('-', $element_parents);

        $previewer_element = [
            '#type' => 'submit',
            '#value' => t('Preview'),
            '#name' => strtr($id_prefix, '-', '_') . '_previewer',
            '#weight' => 1,
            '#submit' => [['\Drupal\paragraphs_previewer\FormHandlers', 'submitPreviewerItem']],
            '#field_item_parents' => $element_parents,
            '#limit_validation_errors' => [
                array_merge($parents, [$field_name, 'add_more']),
            ],
            '#delta' => $delta,
            '#ajax' => [
                'callback' => ['\Drupal\paragraphs_previewer\FormHandlers', 'ajaxSubmitPreviewerItem'],
                'wrapper' => $widget_state['ajax_wrapper_id'],
                'effect' => 'fade',
            ],
            '#access' => $paragraphs_entity->access('view'),
            '#attributes' => [
                'class' => ['paragraphs-previewer'],
            ],
            '#previewer_dialog_title' => isset($element['top']['paragraph_type_title']['info']['#markup'])?
                strip_tags($element['top']['paragraph_type_title']['info']['#markup']) : t('Preview'),
        ];

        return $previewer_element;
    }
}