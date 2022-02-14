<?php

/**
 * 
 */
interface Petition{
    public function __construct($controllerInstance,$requestMethod,$requestVariables,$requestUrlParams);

    /**
     * 
     */

   
    public function make();

    /**
     * 
     */
    public function send();
}