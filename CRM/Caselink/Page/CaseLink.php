<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class CRM_Caselink_Page_CaseLink extends CRM_Core_Page {

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
    $case_type = array();
    $params =array('name' => 'case_type');
    CRM_Core_BAO_OptionGroup::retrieve($params, $case_type);
    $sql = "SELECT civicrm_case.*, civicrm_case_contact.contact_id as client_id, civicrm_contact.display_name, ov.label as case_status_label, ov2.label as case_type_label "
      . "FROM `".$config->getCustomGroup('table_name')."` AS `case_link`
        INNER JOIN `civicrm_case` ON `case_link`.`".$config->getCaseIdField('column_name')."` = `civicrm_case`.`id`"
      . "INNER JOIN `civicrm_case_contact` ON `civicrm_case`.`id` = `civicrm_case_contact`.`case_id`"
      . "INNER JOIN `civicrm_contact` ON `civicrm_case_contact`.`contact_id`  = `civicrm_contact`.`id`"
      . "LEFT JOIN  civicrm_option_value ov ON ( civicrm_case.status_id=ov.value AND ov.option_group_id='".$case_status['id']."')"
      . "LEFT JOIN  civicrm_option_value ov2 ON ( civicrm_case.case_type_id=ov2.value AND ov2.option_group_id='".$case_type['id']."')"
      . "WHERE `case_link`.`entity_id` = '".$this->caseId."' LIMIT 1";
    $dao = CRM_Core_DAO::executeQuery($sql);
    $case = false;
    if ($dao->fetch()) {
      $query['reset'] = 1;
      $query['action'] = 'view';
      $query['id'] = $dao->id;
      $query['cid'] = $dao->client_id;
      $query['context'] = 'case';
      $url = CRM_Utils_System::url("civicrm/contact/view/case", $query);
      $label = $dao->display_name.'::'.$dao->subject;
      $case = array(
        'url' => $url,
        'label' => htmlentities($label, ENT_QUOTES),
      );
    }

    $this->assign('caseId', $this->caseId);
    $this->assign('linked_to_case', $case);
    $this->assign('block_id', $config->getCustomGroup('name'));

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