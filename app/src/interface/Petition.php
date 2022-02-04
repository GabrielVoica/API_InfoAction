<?php

interface Petition{
    public function __constructor($controllerInstance,$requestMethod,$requestVariables = null);
    public function process();
    public function send();
}