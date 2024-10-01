<?php
// src/controllers/TargetListController.php
class TargetListController {
    private $targetListService;

    public function __construct(TargetListService $targetListService) {
        $this->targetListService = $targetListService;
    }

    // Display the target list view
    public function showTargetList($targetListId) {
        try {
            $targetListData = $this->targetListService->getTargetListWithDetails($targetListId);
            require __DIR__ . '/../views/targetlist.view.php';
        } catch (Exception $e) {
            die($e->getMessage()); // Handle error (can be improved)
        }
    }
}
