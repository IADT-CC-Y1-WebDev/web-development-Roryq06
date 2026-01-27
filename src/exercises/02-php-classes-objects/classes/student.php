<?php


class Student {
    public $name;
    public $number;

    public function __construct($name, $number) {
        $this->name = $name;
        $this->number = $number;
    }

    public function getName() {
        return $this->name;
    }

    public function getNumber() {
        return $this->number;
    }
}


?>