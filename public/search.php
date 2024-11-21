<?php
function searchBooks($search, $user_id, $con) {
    $query = "SELECT * FROM books WHERE user_id = :user_id";

    if (!empty($search)) {
        $query .= " AND (title LIKE :search OR author LIKE :search OR genre LIKE :search)";
    }

    $stmt = $con->prepare($query);

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    if (!empty($search)) {
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

