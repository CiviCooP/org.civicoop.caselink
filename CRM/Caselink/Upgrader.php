<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Caselink_Upgrader extends CRM_Caselink_Upgrader_Base {

  public function install() {
    $this->executeCustomDataFile('xml/caselink_case.xml');
    $this->executeCustomDataFile('xml/caselink_event.xml');
  }
  
  public function uninstall() {
    $this->removeCustomGroup('caselink_case');
    $this->removeCustomGroup('caselink_event');
  }

  public function enable() {
    CRM_Core_DAO::executeQuery("UPDATE civicrm_custom_group SET is_active = 1 WHERE name = 'caselink_case'");
    CRM_Core_DAO::executeQuery("UPDATE civicrm_custom_group SET is_active = 1 WHERE name = 'caselink_event'");
  }
  
  public function disable() {
    CRM_Core_DAO::executeQuery("UPDATE civicrm_custom_group SET is_active = 0 WHERE name = 'caselink_case'");
    CRM_Core_DAO::executeQuery("UPDATE civicrm_custom_group SET is_active = 0 WHERE name = 'caselink_event'");
  }

  protected function removeCustomGroup($name) {
    try {
      $custom_group_id = civicrm_api3('CustomGroup', 'getvalue', array('name' => $name, 'return' => 'id'));
      $custom_fields = civicrm_api3('CustomField', 'get', array('custom_group_id' => $custom_group_id));
      foreach($custom_fields['values'] as $field) {
        if (!empty($field['option_group_id'])) {
          civicrm_api3('OptionGroup', 'delete', array('id' => $field['option_group_id']));
        }
        civicrm_api3('CustomField', 'delete', array('id' => $field['id']));
      }
      civicrm_api3('CustomGroup', 'delete', array('id' => $custom_group_id));
    } catch (Exception $e) {
      //do nothing
    }
  }

}
