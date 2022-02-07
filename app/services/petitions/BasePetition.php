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


    public function test()
    {
        $this->requestVariables = explode('&',  $this->requestVariables);
        $this->requestVariables = explode('=',  implode("=", $this->requestVariables));

        $variablesArray = [];

        for ($i = 0; $i < count($this->requestVariables); $i++) {
            if ($i % 2 == 0) {
                $variablesArray[$this->requestVariables[$i]] = $this->requestVariables[$i + 1];
            }
        }
    }



    public function make()
    {
        $this->petitionProcesser->process();
    }

    /**
     * 
     */
    public function send()
    {
        echo $this->result;
    }
}
