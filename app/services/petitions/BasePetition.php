<?php

require "src/interface/Petition.php";

/**
 * 
 */
class BasePetition implements Petition{

   /**
    * 
    */
   private $petitionProcesser;

   /**
    * 
    */
   private $controllerInstance;

   /**
    * 
    */
   private $requestMethod;

   /**
    * 
    */
   private $requestVariables;

   /**
    * 
    */
   private $result;


   public function __construct($controllerInstance,$requestMethod,$requestVariables){
       $this->controllerInstance = $controllerInstance;
       $this->requestMethod = $requestMethod;
       $this->$requestVariables = $requestVariables;
       $this->petitionProcesser = new ProcessPeticion();
   }

    /**
     * 
     */
    public function make(){
        $this->petitionProcesser->process();
    }

    /**
     * 
     */
    public function send(){
        echo $this->result;
    }
}