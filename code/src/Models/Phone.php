<?php 

namespace Geekbrains\Application1\Models;

class Phone
{
    private string $phone;

    public function __construct()
    {
     //   $this->phone = '+7844432423';
    }

    public function getPhone(){
        var_dump($_GET);
        $this->phone =$_GET['phone'] ?? '';
        return $this->phone;
    }

}