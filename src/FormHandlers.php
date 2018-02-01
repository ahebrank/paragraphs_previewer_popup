<?php

namespace Drupal\paragraphs_previewer;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Url;

class FormHandlers {
    public static function ajaxSubmitPreviewerItem(array $form, FormStateInterface $form_state) {
        $preview_url = NULL;
        $dialog_options = array(
            'dialogClass' => 'paragraphs-previewer-ui-dialog',
            'minWidth' => 320,
            'width' => '98%',
            'minHeight' => 100,
            'autoOpen' => TRUE,
            'modal' => TRUE,
            'draggable' => TRUE,
            'autoResize' => FALSE,
            'resizable' => TRUE,
            'closeOnEscape' => TRUE,
            'closeText' => '',
        );
        
        $previewer_element = $form_state->getTriggeringElement();
        $dialog_title = $previewer_element['#previewer_dialog_title'];
        
        // Build previewer callback url.
        if (!empty($previewer_element['#field_item_parents']) && !empty($form['#build_id'])) {
            $route_name = 'paragraphs_previewer.form_preview';
            $route_parameters = [
                'form_build_id' => $form['#build_id'],
                'element_parents' => implode(':', $previewer_element['#field_item_parents']),
            ];
            $preview_url = Url::fromRoute($route_name, $route_parameters);
        }
        
        // Build modal content.
        $dialog_content = [
            '#theme' => 'paragraphs_previewer_modal_content',
            '#preview_url' => $preview_url,
            '#attached' => [
                'library' => ['paragraphs_previewer/dialog'],
            ],
        ];
        
        // Build response.
        $response = new AjaxResponse();
        
        // Attach the library necessary for using the OpenModalDialogCommand and
        // set the attachments for this Ajax response.
        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
        $response->setAttachments($form['#attached']);
        
        // Add modal dialog.
        $response->addCommand(new OpenModalDialogCommand($dialog_title, $dialog_content, $dialog_options));
        
        return $response;
    }
      
    public static function submitPreviewerItem(array $form, FormStateInterface $form_state) {
        if (!$form_state->isCached()) {
            $form_state->setRebuild();
        }
    }
}