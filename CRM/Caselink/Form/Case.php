<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_Caselink_Form_Case {

  public static function setDefaultCaseLink($formName, &$form) {
    if ($formName != 'CRM_Case_Form_Case') {
      return;
    }

    $caselink_case_id = CRM_Utils_Request::retrieve('caselink_case_id', 'Positive', $form);
    if (!empty($caselink_case_id)) {
      $config = CRM_Caselink_Config_CaseLinkCase::singleton();
      $defaults['custom_'.$config->getCaseIdField('id').'_-1'] = $caselink_case_id;
      $form->setDefaults($defaults);
    }
  }

}