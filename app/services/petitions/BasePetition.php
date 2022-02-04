<?php

require "src/interface/Petition.php";


class BasePetition implements Petition{

   private $petition;
   private $controllerInstance;
   private $requestMethod;
   private $requestVariables;

   public function __constructor($controllerInstance,$requestMethod,$requestVariables = null){

       $petitionProcesser = new ProcessPeticion();

   }

    public function process(){

    }

    public function send(){

    }
}