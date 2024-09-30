<?php
class SpeedDialerService {
    private $accountModel;
    private $contactModel;
    private $targetListModel;

    public function __construct($accountModel, $contactModel, $targetListModel) {
        $this->accountModel = $accountModel;
        $this->contactModel = $contactModel;
        $this->targetListModel = $targetListModel;
    }

    // Get the accounts and their contacts for a given target list
    public function getAccountsAndContactsForTargetList($targetListId) {
        // Fetch the target list
        $targetList = $this->targetListModel->getTargetListById($targetListId);

        // Fetch accounts associated with this target list
        $accounts = $this->accountModel->getAccountsByTargetList($targetListId);

        // For each account, fetch the related contacts
        foreach ($accounts as &$account) {
            $contacts = $this->contactModel->getContactsByAccountId($account['id']);
            $account['contacts'] = $contacts;
        }

        // Return the target list, with accounts and their contacts
        $targetList['accounts'] = $accounts;
        return $targetList;
    }

    // You can add more methods, like logging call outcomes, tracking progress, etc.
}
