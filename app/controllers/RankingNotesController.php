<?php

require_once('src/models/RankingNotes.php');
require_once("services/errors/BadRequestError.php");
require "services/responses/Response.php";
require_once("services/Database.php");

class RankingNotesController implements Controller
{

    private $connection;
    private $badRequestError;
    private $database;


    public function __construct($database)
    {
        $database->connect();
        $this->connection = $database->getConnection();
        $this->badRequestError = new BadRequestError();
        $this->database = new Database();
        $this->response = new Response();
    }

    public function get($params)

    {
    }

    public function post($variables)
    {
        $result = RankingNotes::insert($variables);

        if ($result['result'] == true) {
            $response = Response::successful();
            $response['message'] = Response::successful()['message'];
            return $response;
        } else {
            $response = BadRequestError::throw();
            $response['message'] = $result['message'];
            return $response;
        }
    }

    public function put($variables)
    {
    }

    public function delete($variables)
    {
    }
}
