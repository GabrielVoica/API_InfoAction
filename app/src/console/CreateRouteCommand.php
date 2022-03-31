<?php

namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateRouteCommand extends Command
{
    protected function configure()
    {
        $this->setName('new-route')
            ->setDescription('Creates a new route and a controller')
            ->setHelp('..')
            ->addArgument('routeName', InputArgument::REQUIRED, 'Pass the new api route name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routes = file_get_contents('app/routes.yml');
        $routes .= $this->returnRouteText($input->getArgument('routeName'));
        file_put_contents("app/routes.yml", $routes);

        file_put_contents('app/controllers/' . ucfirst($input->getArgument('routeName') . 'Controller.php'), $this->returnControllerText($input->getArgument('routeName')));

        return 1;
    }


    public function returnRouteText($route)
    {
        $text = "\n \n" .  $route . ": \n route: '" . $route . "'\n controller: '" . ucfirst($route) . "Controller'";

        return $text;
    }

    public function returnControllerText($className)
    {
        return 
        "<?php\n\nrequire_once('services/responses/Response.php');
        \nclass " . ucfirst($className) . "Controller implements Controller \n{
        public function __construct(){\n
        }\n

        public function get(\$params){\n
        }\n
        
        public function post(\$variables){\n
        }\n

        public function put(\$variables){\n
        }\n

        public function delete(\$variables){\n
        }
        \n} 
        ";
    }
}
