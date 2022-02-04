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
       /**
        * 
        */
       $this->controllerInstance = $controllerInstance;

       /**
        * 
        */
       $this->requestMethod = $requestMethod;

       /**
        * 
        */
       $this->requestVariables = $requestVariables;

       /**
        * 
        */
       $this->petitionProcesser = new ProcessPeticion();
   }

    /**
     * 
     */


     public function test(){

     

        $this->requestVariables = explode('&',  $this->requestVariables);
        $this->requestVariables = explode('=',  implode("=",$this->requestVariables));

       
        for($i = 0; $i < $this->requestVariables; $i++) {
            if ($this->requestVariables[$i].sizeof % 2 == 0) 
            $this->requestVariables[null][$i] = $this->requestVariables[$i];

            else 
                $this->requestVariables[null][$i] = $this->requestVariables[$i];
            
        }

        echo "<pre>";
        print_r($this->requestVariables);
        echo "<pre>";

     }



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