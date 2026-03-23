<?php

class BookFormat {
    public static function create($bookId, $formatId) {
        $db = DB::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO book_format (book_id, format_id)
            VALUES (:book_id, :format_id)
        ");

        return $stmt->execute([
            'book_id' => $bookId,
            'format_id' => $formatId
        ]);
    }
}