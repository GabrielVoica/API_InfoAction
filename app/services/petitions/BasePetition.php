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


    private $requestUrlParams;

    /**
     * 
     * 
     */
    private $result;



    public function __construct($controllerInstance, $requestMethod, $requestVariables, $requestUrlParams)
    {
        $this->controllerInstance = $controllerInstance;
        $this->requestMethod = $requestMethod;

        /**
         * 
         */
        $this->requestVariables = $requestVariables;

        /**
         * 
         * 
         */
        $this->requestUrlParams = $requestUrlParams;


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
                $this->result = $this->controllerInstance->get($this->requestUrlParams);
                break;
            case 'POST':
                $this->result = $this->controllerInstance->post($this->requestVariables);
                break;
            case 'PUT':
                $this->result = $this->controllerInstance->put($this->requestVariables);
                break;
            case 'DELETE':
                $this->result = $this->controllerInstance->delete($this->requestUrlParams);
                break; 
        }

        $this->send();
    }

    /**
     * 
     */
    public function send()
    {
        if($this->result == null){
          $error = new MethodNotAllowedError();
          $this->result = $error::throw();
        }

        echo json_encode($this->result);
    }
}
