<?php
namespace Adduc\DomainTracker;
use Doctrine\Common\Inflector\Inflector;

include('app/vendor/autoload.php');

$params = explode("/", isset($_GET['p']) ? $_GET['p'] : '');
$params = array_merge(array('','',''), $params);
$params[0] = ltrim($params[0], "/");

$config = parse_ini_file('app/config/config.ini.php', true);
ob_start();

try {
    $ns = __NAMESPACE__ . "\\Controller";
    $class = "{$ns}\\" . Inflector::classify($params) . "Controller";

    switch(true) {
        case !class_exists($class, true):
        case !is_subclass_of($class, "{$ns}\\Controller"):
            throw new Exception\NotFoundException();
    }

    $class = new $class($config);
    $class->run($class, array_slice($params, 2));

} catch(Exception\Ex404 $e) {

    // Clean up output, if any
    ob_get_clean();

    // Let user know they're not anywhere special.
    header('HTTP/1.0 404 Not Found');
    echo "404, dude.";

    if(isset($config['errors']['display']) && $config['errors']['display']) {
        echo "<br />Exception: ";
        echo trim($e->getMessage()) ?: "<em>Not Provided</em>";

        if(isset($config['errors']['verbose']) && $config['errors']['verbose']) {
            echo "<pre>" . $e->getTraceAsString();
            echo "\n\$_REQUEST: " . var_export($_REQUEST, true);
            echo "\n\$_SERVER: " . var_export($_SERVER, true);
        }
    }

} catch(Exception\Ex500 $e) {

    // Log useful info to file.
    $file = tempnam(sys_get_temp_dir(), 'dt.err.');
    error_log("\$_SERVER:" . print_r($_SERVER, true), 3, $file);
    error_log("\$_REQUEST:" . print_r($_REQUEST, true), 3, $file);
    error_log(ob_get_clean(), 3, $file);

    // Log to error log
    error_log("Ex500: '{$e->getMessage()}', logged info to {$file}");

    // Let user know something's up.
    header('HTTP/1.0 500 Internal Server Error');
    echo "Something went wrong. Could be the flux capacitor.";

    if(isset($config['errors']['display']) && $config['errors']['display']) {
        echo "<br />Exception: ";
        echo trim($e->getMessage()) ?: "<em>Not Provided</em>";

        if(isset($config['errors']['verbose']) && $config['errors']['verbose']) {
            echo "<pre>" . $e->getTraceAsString();
            echo "\n\$_REQUEST: " . var_export($_REQUEST, true);
            echo "\n\$_SERVER: " . var_export($_SERVER, true);
        }
    }

}

ob_end_flush();