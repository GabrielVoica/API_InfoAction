<?php


interface Controller
{
    /**
     * 
     */
    public function get($params);

    /**
     * 
     */
    public function post($variables);

    /**
     * 
     */
    public function put($variables);

    /**
     * 
     */
    public function delete($variables);
}
