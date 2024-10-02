<?php

// src/controllers/TargetListController.php
////////////////// KAN SKAPA APIER HÄR FÖR FRONTEND FUNKIONALTITET(VUE) //////////////////
class TargetListController {
    private $targetListService;

    public function __construct($targetListService) {
        $this->targetListService = $targetListService;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////     DONE   //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Display all target lists with optional filtering
    public function listAllTargetLists($filters = []) {
        try {
            // Get all target lists from the service layer
            $targetLists = $this->targetListService->getAllTargetLists($filters);
    
            // Render the view or return a JSON response
            include __DIR__ . '/../views/targetlist.view.php'; // Render HTML view
            // Alternatively, you can return as JSON if this is an API:
            // echo json_encode(['targetLists' => $targetLists]);
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Display a single target list along with its accounts and contacts
    public function showTargetList($targetListId) {
        try {
            // Fetch the target list with accounts and contacts
            $targetList = $this->targetListService->getTargetListWithAccountsAndContacts($targetListId);
            // Render the view or return as JSON
            include __DIR__ . '/../views/targetlist_detail.view.php'; // Render HTML view for a detailed list
            // For API response:
            // echo json_encode(['targetList' => $targetList]);
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Create a new target list
    public function createTargetList($postData) {
        try {
            // Extract data from POST request
            $name = $postData['name'];
            $description = $postData['description'] ?? null;
            $campaignId = $postData['campaign_id'] ?? null;
            $assignedTo = $postData['assigned_to'] ?? null;

            // Call the service to create the new target list
            $this->targetListService->createTargetList($name, $description, $campaignId, $assignedTo);

            // Redirect or return a success response
            header('Location: ?route=target-lists');  // Redirect after creation // ///////////////////////////// MTTODO - CHECK ROUTES
            // echo json_encode(['success' => true]); // For API
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Update the status of a target list
    public function updateTargetListStatus($targetListId, $status) {
        try {
            // Call the service to update the target list status
            $this->targetListService->updateTargetListStatus($targetListId, $status);

            // Redirect or return a success response
            header('Location: /targetlist.php?targetListId=' . $targetListId); // Redirect back to target list
            // echo json_encode(['success' => true]); // For API
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Assign a user to a target list
    public function assignUserToTargetList($targetListId, $userId) {
        try {
            // Call the service to assign the user
            $this->targetListService->assignUserToTargetList($targetListId, $userId);

            // Redirect or return a success response
            header('Location: /targetlist.php?targetListId=' . $targetListId);
            // echo json_encode(['success' => true]); // For API
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Lock the target list for exclusive access
    public function lockTargetList($targetListId, $userId) {
        try {
            // Call the service to lock the target list
            $this->targetListService->lockTargetList($targetListId, $userId);

            // Redirect or return a success response
            header('Location: /targetlist.php?targetListId=' . $targetListId);
            // echo json_encode(['success' => true]); // For API
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Unlock the target list
    public function unlockTargetList($targetListId, $userId) {
        try {
            // Call the service to unlock the target list
            $this->targetListService->unlockTargetList($targetListId, $userId);

            // Redirect or return a success response
            header('Location: /targetlist.php?targetListId=' . $targetListId);
            // echo json_encode(['success' => true]); // For API
        } catch (Exception $e) {
            // Handle any exceptions that occur
            $this->handleError($e->getMessage());
        }
    }

    // Handle errors (generic method for error handling)
    private function handleError($errorMessage) {
        // For rendering an error view, or returning error response for API
        include __DIR__ . '/../views/error.view.php'; // Render error page
        // For API, you might return:
        // echo json_encode(['error' => $errorMessage]);
    }
}
