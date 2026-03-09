<?php
class Format
{
    // public properties for each database column
    public $id;
    public $name;
 
    // private $db property for database connection
    private $db;
 
    public function __construct($data = [])
    {
        $this->db = DB::getInstance()->getConnection();
 
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
    }
 
    public static function findAll()
    {
        $db = DB::getInstance()->getConnection();
 
        $stmt = $db->prepare("SELECT * FROM formats ORDER BY name");
        $stmt->execute();
 
        $formats = [];
        while ($row = $stmt->fetch()) {
            $formats[] = new Format($row);
        }
 
        return $formats;
    }
 
    public static function findByBookId($bookId)
    {
        $db = DB::getInstance()->getConnection();
 
        $stmt = $db->prepare("SELECT f.* FROM books b LEFT JOIN book_format bf ON bf.book_id = b.id LEFT JOIN formats f ON bf.format_id = f.id WHERE b.id = :bookId");
        $stmt->execute(['bookId' => $bookId]);
 
        $formats = [];
        while ($row = $stmt->fetch()) {
            $formats[] = new Format($row);
        }
 
        return $formats;
    }
 
    public static function findById($id)
    {
        $db = DB::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM formats WHERE id = :id");
        $stmt->execute(['id' => $id]);
 
        $row = $stmt->fetch();
        if ($row) {
            return new Format($row);
        }
 
        return null;
    }
 
    public static function findByFormat($formatId)
    {
        $db = DB::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM formats WHERE format_id = :format_id ORDER BY name");
        $stmt->execute(['format_id' => $formatId]);
 
        $formats = [];
        while ($row = $stmt->fetch()) {
            $formats[] = new Format($row);
        }
 
        return $formats;
    }
 
    public function save()
    {
        if ($this->id) {
            $stmt = $this->db->prepare("
                UPDATE formats
                SET name = :name
                WHERE id = :id
            ");
 
            $params = [
                'name' => $this->name,
                'id' => $this->id
            ];
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO formats (name)
                VALUES (:name)
            ");
 
            $params = [
                'name' => $this->name,
            ];
        }
 
        $status = $stmt->execute($params);
 
        if (!$status) {
            $error_info = $stmt->errorInfo();
            $message = sprintf(
                "SQLSTATE error code: %s; error message: %s",
                $error_info[0],
                $error_info[2]
            );
            throw new Exception($message);
        }
 
        if ($stmt->rowCount() !== 1) {
            throw new Exception("Failed to save format.");
        }
 
        if ($this->id === null) {
            $this->id = $this->db->lastInsertId();
        }
    }
 
    public function delete()
    {
        if (!$this->id) {
            return false;
        }
 
        $stmt = $this->db->prepare("DELETE FROM formats WHERE id = :id");
        return $stmt->execute(['id' => $this->id]);
    }
 
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}