<?php

class Employee {
    private $id;
    private $name;
    private $position;
    private $department;
    private $salary;

    public function __construct($id, $name, $position, $department, $salary) {
        $this->id = $id;
        $this->name = $name;
        $this->position = $position;
        $this->department = $department;
        $this->salary = $salary;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function getSalary() {
        return $this->salary;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function setDepartment($department) {
        $this->department = $department;
    }

    public function setSalary($salary) {
        $this->salary = $salary;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->position,
            'department' => $this->department,
            'salary' => $this->salary,
        ];
    }
}