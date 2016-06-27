<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_Caselink_Config_CaseLinkEvent {

  private static $singleton;

  private $custom_group;

  private $case_id;

  protected function __construct() {
    $this->custom_group = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'caselink_event'));
    $this->case_id = civicrm_api3('CustomField', 'getsingle', array('name' => 'case_id', 'custom_group_id' => $this->custom_group['id']));
  }

  /**
   * @return CRM_Caselink_Config_CaseLinkEvent
   */
  public static function singleton() {
    if (!self::$singleton) {
      self::$singleton = new CRM_Caselink_Config_CaseLinkEvent();
    }
    return self::$singleton;
  }

  public function getCaseIdField($key='id') {
    return $this->case_id[$key];
  }

  public function getCustomGroup($key='id') {
    return $this->custom_group[$key];
  }

}