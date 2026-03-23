<?php

class Student {
    protected $name;
    protected $number;

    public function __construct($name, $number) {
        if (empty($number)) {
            throw new Exception("Student number cannot be empty");
        }

        $this->name = $name;
        $this->number = $number;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumber() {
        return $this->number;
    }

    public function __toString() {
        return "Student: {$this->name} ({$this->number})";
    }


    public function __destruct() {
        echo "<p>Student {$this->name} has left the system</p>";
    }
}

?>