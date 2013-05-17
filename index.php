<?php
namespace Adduc\DomainTracker;
use Doctrine\Common\Inflector\Inflector;

function showException(\Exception $e, $config) {
    if(isset($config['errors']['display']) && $config['errors']['display']) {
        echo "<br />Exception: ";
        echo htmlentities(trim($e->getMessage())) ?: "<em>Description Not Provided</em>";

        if(isset($config['errors']['verbose']) && $config['errors']['verbose']) {
            echo "<pre>" . $e->getTraceAsString();
            echo "\n\$_REQUEST: " . var_export($_REQUEST, true);
            echo "\n\$_SERVER: " . var_export($_SERVER, true);
        }
    }
}

include('app/vendor/autoload.php');

$params = explode("/", isset($_GET['p']) ? ltrim($_GET['p'], '/') : 'index');
$params = $params + array('','','');

$config_file = 'app/config/config.ini.php';
$config = parse_ini_file($config_file, true);
if(!$config) {
    $config_sample = 'app/config/config.example.ini.php';
    die("Couldn't find a config file. Mind copying {$config_sample}
        to {$config_file} and sprucing it up a bit?");
}

// Set error display/reporting based on config settings
isset($config['errors']['display'])
    && ini_set('display_errors', $config['errors']['display'] ? 1 : 0);
isset($config['errors']['level'])
    && error_reporting($config['errors']['level']);

ob_start();

try {
    $ns = __NAMESPACE__ . "\\Controller";
    $class = "{$ns}\\" . Inflector::classify($params[0]) . "Controller";

    switch(true) {
        case !class_exists($class, true):
        case !is_subclass_of($class, "{$ns}\\Controller"):
            $msg = "{$class} does not exist.";
            throw new Exception\NotFoundException($msg);
    }

    $class = new $class($config);
    $class->run($params[1], array_slice($params, 2));

} catch(Exception\Ex404 $e) {

    // Clean up output, if any
    ob_get_clean();

    // Let user know they're not anywhere special.
    header('HTTP/1.0 404 Not Found');
    echo "404, dude.";
    showException($e, $config);


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
    showException($e, $config);

}

ob_end_flush();
