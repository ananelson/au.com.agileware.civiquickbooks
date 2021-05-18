<?php

function _civicrm_api3_civiquickbooks_batsclassjournal(&$spec) {
  $spec['event_id'] = array(
    'type' => CRM_Utils_Type::T_INT,
    'name' => 'event_id',
    'title' => 'Event ID',
    'description' => 'event id to create entry for (used to make journal name)',
  );

}

/*
 * Generates a JournalEntry for a BATS Class to recognize income
 *
 */
function civicrm_api3_civiquickbooks_batsclassjournal($params) {
  \CRM_Core_Error::debug_var("in civicrm_api3_civiquickbooks_batsclassjournal", $params);
  $txn_date = $params["txn_date"];
  $doc_number = "Civi-" . $params['doc_key'] . "-J";
  $note = $params['title'];

  $qbJournal = new CRM_Civiquickbooks_Journal();
  $lines = array();

  $lines[] = $qbJournal->line(
    'Debit',
    $params['debit_account'],
    $params['debit'],
    '2 School',
    $params['desc']
  );

  $lines[] = $qbJournal->line(
    "Credit",
    $params['credit_account'],
    $params['credit'],
    "2 School",
    $params['desc']
  );

  $result = $qbJournal->create($txn_date, $doc_number, $lines, $note);
  \CRM_Core_Error::debug_var("result", $result);
  return civicrm_api3_create_success($result, $params, 'Civiquickbooks', 'BATSClassJournal');
}
