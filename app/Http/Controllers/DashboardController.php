<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LPetTool;
class HomeController extends Controller
{
    //
    public function gettest(){
        $petTool = new PetTool([
            'id' => 'abc',
            'toolName' => 'Hamster Wheel',
            'type' => 'Exercise',
            'price' => 15.99,
            'availableQuantity' => 100
        ]);
        echo $petTool->toJsonString();
        
    }
}
