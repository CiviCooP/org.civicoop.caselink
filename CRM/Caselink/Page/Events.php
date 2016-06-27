<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Caselink_Page_Events extends CRM_Core_Page {

  protected $caseId;

  public function __construct($caseId) {
    parent::__construct();
    $this->caseId = $caseId;
  }

  public function run() {
    $this->preProcess();

    //get template file name
    $pageTemplateFile = $this->getHookedTemplateFileName();
    $config = CRM_Caselink_Config_CaseLinkEvent::singleton();
    $case_config = CRM_Caselink_Config_CaseLinkCase::singleton();


    $sql = "SELECT civicrm_event.*, eventtype.label as event_type"
      . " FROM `".$config->getCustomGroup('table_name')."` AS `case_link`"
      . " INNER JOIN `civicrm_event` ON `case_link`.`entity_id` = `civicrm_event`.`id` "
      . " LEFT JOIN civicrm_option_group eventtypes on eventtypes.name = 'event_type'"
      . " LEFT JOIN civicrm_option_value eventtype on eventtype.option_group_id = eventtypes.id and eventtype.value = civicrm_event.event_type_id "
      . " WHERE `case_link`.`".$config->getCaseIdField('column_name')."` = '".$this->caseId."'"
      . " ORDER BY civicrm_event.start_date";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $events = array();
    while($dao->fetch()) {
      $start_date = $dao->start_date;
      $events[] = array(
        'event_id' => $dao->id,
        'title' => $dao->title,
        'event_type' => $dao->event_type,
        'start_date' => $start_date,
      );
    }

    $this->assign('caseId', $this->caseId);
    $this->assign('events', $events);
    $this->assign('permission', 'edit');
    $this->assign('customGroupName', $case_config->getCustomGroup('name'));


    //render the template
    $content = self::$_template->fetch($pageTemplateFile);

    CRM_Utils_System::appendTPLFile($pageTemplateFile, $content);
    //its time to call the hook.
    CRM_Utils_Hook::alterContent($content, 'page', $pageTemplateFile, $this);

    return $content;
  }

  protected function preProcess() {

  }
}