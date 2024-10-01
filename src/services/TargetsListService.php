<?php
// /src/services/TargetListService.php

class TargetListService {
    private $targetListModel;

    public function __construct(TargetListModel $targetListModel) {
        $this->targetListModel = $targetListModel;
    }

    // Fetch target list with all associated accounts and contacts
    public function getTargetListWithDetails($targetListId) {
        $targetList = $this->targetListModel->getTargetListById($targetListId);

        if (!$targetList) {
            throw new Exception("Target List not found");
        }

        $accounts = $this->targetListModel->getAccountsForTargetList($targetListId);

        // Attach contacts to each account
        foreach ($accounts as &$account) {
            $account['contacts'] = $this->targetListModel->getContactsForAccount($account['id']);
        }

        $targetList['accounts'] = $accounts;
        return $targetList;
    }
}
