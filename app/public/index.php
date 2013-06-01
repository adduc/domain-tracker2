<?php
namespace Adduc\DomainTracker;
use Doctrine\Common\Inflector\Inflector;

$app_root = dirname(__DIR__);

include("{$app_root}/vendor/autoload.php");

$params = explode("/", isset($_GET['p']) ? ltrim($_GET['p'], '/') : '');
$params = $params + array('','','');

try {

    $config = new Core\Config("{$app_root}/config/config.ini.php");

    // Set error display/reporting based on config settings
    ini_set('display_errors', $config['errors.display'] ? 1 : 0);
    error_reporting($config['errors.level']);

    ob_start();

    $controller = new Controller\Controller($config);
    $controller = $controller->getController($params[0] ?: 'index');
    $controller->run($params[1] ?: 'index', array_slice($params, 2));

} catch(\Exception $e) {

    // Clean up output, if any
    while($c = ob_get_status()) {
        ob_end_clean();
    }

    if($e instanceof Exception\Ex404) {

        // Let user know they're not anywhere special.
        header('HTTP/1.0 404 Not Found');
        echo "404, dude.";

    } else {

        // Let user know something's up.
        header('HTTP/1.0 500 Internal Server Error');
        echo "Something went wrong. Could be the flux capacitor.";

        // Log useful info to file.
        $file = tempnam(sys_get_temp_dir(), 'dt.err.');
        error_log("\$_SERVER:" . print_r($_SERVER, true), 3, $file);
        error_log("\$_REQUEST:" . print_r($_REQUEST, true), 3, $file);
        error_log(ob_get_clean(), 3, $file);

        // Log to error log
        error_log("Ex500: '{$e->getMessage()}', logged info to {$file}");

    }

    if($config['errors.display']) {
        echo "<br />Exception: ";
        echo htmlentities(trim($e->getMessage())) ?: "<em>Description Not Provided</em>";

        if($config['errors.verbose']) {
            echo "<pre>" . $e->getTraceAsString();
            echo "\n\$_REQUEST: " . var_export($_REQUEST, true);
            echo "\n\$_SERVER: " . var_export($_SERVER, true);
        }
    }

}

// Clean up output, if any
while($c = ob_get_status()) {
    ob_end_flush();
}
