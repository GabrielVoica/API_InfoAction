<?php

/**
 * 
 */
interface Petition{
    public function __construct($controllerInstance,$requestMethod,$requestVariables);

    /**
     * 
     */


   
    public function make();

    /**
     * 
     */
    public function send();
}