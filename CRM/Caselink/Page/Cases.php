<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Caselink_Page_Cases extends CRM_Core_Page {

  protected $caseId;

  public function __construct($caseId) {
    parent::__construct();
    $this->caseId = $caseId;
  }

  public function run() {
    $this->preProcess();

    //get template file name
    $pageTemplateFile = $this->getHookedTemplateFileName();
    $config = CRM_Caselink_Config_CaseLinkCase::singleton();

    $case_status = array();
    $params =array('name' => 'case_status');
    CRM_Core_BAO_OptionGroup::retrieve($params, $case_status);
    $sql = "SELECT civicrm_case.*, civicrm_case_contact.contact_id as client_id, civicrm_contact.display_name, ov.label as case_status_label, casetype.title as case_type_label "
      . "FROM `".$config->getCustomGroup('table_name')."` AS `case_link`
        INNER JOIN `civicrm_case` ON `case_link`.`entity_id` = `civicrm_case`.`id` "
      . "INNER JOIN `civicrm_case_contact` ON `civicrm_case`.`id` = `civicrm_case_contact`.`case_id` "
      . "INNER JOIN `civicrm_contact` ON `civicrm_case_contact`.`contact_id`  = `civicrm_contact`.`id` "
      . "LEFT JOIN  civicrm_option_value ov ON ( civicrm_case.status_id=ov.value AND ov.option_group_id='".$case_status['id']."') "
      . "LEFT JOIN  civicrm_case_type casetype ON civicrm_case.case_type_id=casetype.id "
      . "WHERE `case_link`.`".$config->getCaseIdField('column_name')."` = '".$this->caseId."'";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $cases = array();
    while($dao->fetch()) {
      $cases[] = array(
        'client_id' => $dao->client_id,
        'case_id' => $dao->id,
        'display_name' => $dao->display_name,
        'status' => $dao->case_status_label,
        'case_type' => $dao->case_type_label,
        'subject' => $dao->subject,
      );
    }

    $this->assign('caseId', $this->caseId);
    $this->assign('cases', $cases);
    $this->assign('permission', 'edit');
    $this->assign('customGroupName', $config->getCustomGroup('name'));


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