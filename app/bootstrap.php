<?php

/**
 * My Application bootstrap file.
 */
use Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList;


// Load Nette Framework
require LIBS_DIR . '/autoload.php';


// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode($configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->register();

\Nella\Console\Config\Extension::register($configurator);
\Nella\Doctrine\Config\Extension::register($configurator);
\Nella\Doctrine\Config\MigrationsExtension::register($configurator);

$configurator->onCompile[] = function ($configurator, $compiler) {
    $compiler->addExtension('gettextTranslator', new \GettextTranslator\DI\Extension);
};

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

// Setup router
$container->router[] = new Route('index.php', ':Front:Home:default', Route::ONE_WAY);

//Admin routes
$container->router[] = $adminRouter = new RouteList('Admin');
$adminRouter[] = new Route('admin/<presenter>/<action>', 'Home:default');

//Front routes
$container->router[] = $frontRouter = new RouteList('Front');
$frontRouter[] = new Route('blog/', 'Blog:default');
$frontRouter[] = new Route('blog[/<slug>]', 'Blog:show');
$frontRouter[] = new Route('blog/category[/<slug>]', 'Blog:showCategory');
$frontRouter[] = new Route('blog/tag[/<slug>]', 'Blog:showTag');
$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Home:default');
$frontRouter[] = new Route('blog', 'Blog:default');


// Configure and run the application!
$container->application->run();
