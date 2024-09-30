<?php
class TargetListModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch the target list by its ID
    public function getTargetListById($targetListId) {
        $sql = "SELECT * FROM target_lists WHERE id = ?";
        return $this->db->query($sql, [$targetListId])->fetch(PDO::FETCH_ASSOC);
    }
}
