<?php

/** Load CiviX ExtensionUtil class and bundled autoload resolver. **/
use CRM_Civiquickbooks_ExtensionUtil as E;

require E::path('vendor/autoload.php');

class CRM_Civiquickbooks_Journal {
  private $plugin = 'quickbooks';

  public function line($type, $account, $amount, $class, $memo) {
    $account_num = CRM_Civiquickbooks_Invoice::getAccount($account);
    $class_num = CRM_Civiquickbooks_Invoice::getQBOClass($class);

    return array(
      "DetailType" => "JournalEntryLineDetail",
      "JournalEntryLineDetail" => array(
        "PostingType" => $type,
        "AccountRef" => array("value" => $account_num),
        "ClassRef" => array("Value" => $class_num)
      ),
      "Amount" => $amount,
      "Description" => $memo
    );
  }

  /**
   * Creates and posts a QBO Journal Entry
   */
  public function create($date, $doc_number, $lines, $note=null) {
    $journal_data = array(
      "TxnDate" => $date,
      "DocNumber" => $doc_number,
      "Line" => $lines
    );

    if ($note != null) {
      $journal_data["PrivateNote"] = $note;
    }

    $dataService = CRM_Quickbooks_APIHelper::getAccountingDataServiceObject();
    $journal_entry = \QuickBooksOnline\API\Facades\JournalEntry::create($journal_data);
    $result = $dataService->Add($journal_entry);
    return $result;
  }
}
