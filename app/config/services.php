<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
                '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
            ));

            return $view;
        }, true);

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function () use ($config) {
            return new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                "charset" => $config->database->charset
            ));
        });

        /**
         * If the configuration specify the use of metadata adapter use it or use memory otherwise
         */
        $di->set('modelsMetadata', function () {
            return new MetaDataAdapter();
        });

        /**
         * Start the session the first time some component request the session service
         */
        $di->set('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

        $di->setShared('dispatcher', function() {
            $eventsManager = new Phalcon\Events\Manager();

            $eventsManager->attach("dispatch", function($event, $dispatcher, $exception) {
                /* @var $dispatcher Phalcon\Mvc\Dispatcher */
                if ($event->getType() == 'beforeException') {
                    $ctrl = $dispatcher->getActiveController();

                    if($ctrl instanceof \Test\Controllers\API\ApiControllerBase) {
                        $dispatcher->forward(array(
                            'namespace' => '\\',
                            'controller' => 'error',
                            'action' => 'api',
                            'params' => array('message' => $exception->getMessage())
                        ));
                        return false;
                    }
                }
            });

            $dispatcher = new Phalcon\Mvc\Dispatcher();
            $dispatcher->setDefaultNamespace('Test\Controllers');
            //Bind the EventsManager to the Dispatcher
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });

        $di->setShared('router', function() {
             $router = new \Phalcon\Mvc\Router();
            include __DIR__ . '/routes_api.php';
            $router->mount($api);
            return $router;
        });
