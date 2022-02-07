<?php

require "src/interface/Petition.php";

/**
 * 
 */
class BasePetition implements Petition
{

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
     * 
     */
    private $result;


    public function __construct($controllerInstance, $requestMethod, $requestVariables)
    {
        $this->controllerInstance = $controllerInstance;
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
    public function explodeUrlVariables()
    {
        $this->requestVariables = explode('&',  $this->requestVariables);
        $this->requestVariables = explode('=',  implode("=", $this->requestVariables));
        $this->formatUrlVariablesArray($this->requestVariables);
    }


    public function formatUrlVariablesArray($array){
       $variablesArray = [];

        for ($i = 0; $i < count($this->requestVariables); $i++) {
            if ($i % 2 == 0) {
                $variablesArray[$this->requestVariables[$i]] = $this->requestVariables[$i + 1];
            }
        }

        $this->requestVariables = $variablesArray;
    }



    public function make()
    {
        if($this->requestVariables !== null){
            $this->explodeUrlVariables();
        }
        
        $this->result = $this->callControllerMethod();
        $this->petitionProcesser->process();
    }

    public function callControllerMethod(){
        switch($this->requestMethod){
            case 'GET':
                $this->result = $this->controllerInstance->get($this);
                break;
            case 'POST':
                $this->result = $this->controllerInstance->post();
                break;
            case 'PUT':
                $this->result = $this->controllerInstance->put();
                break;
            case 'DELETE':
                $this->result = $this->controllerInstance->delete();
                break; 
        }

        $this->send();
    }

    /**
     * 
     */
    public function send()
    {
        echo json_encode($this->result);
    }
}
