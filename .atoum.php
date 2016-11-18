<?php

use mageekguy\atoum;
use mageekguy\atoum\reports;
use mageekguy\atoum\writers\std;

define('TEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR . 'tests');

function colorized() {
    $color = -1;
    if(false !== ($term = getenv('TERM'))) {
        if(preg_match('/\d+/', $term, $matches) > 0) {
            $color = $matches[0];
        }
    }

    if($color < 0) {
        $color = system('tput colors 2> /dev/null');
    }

    return ($color >= 256);
}

if(colorized()) {
    $script
        ->addDefaultReport()
            ->addField(new atoum\report\fields\runner\atoum\logo())
            ->addField(new atoum\report\fields\runner\result\logo())
    ;
}

$script->noCodeCoverageForNamespaces('mageekguy');
$runner->addTestsFromDirectory(TEST_ROOT . DIRECTORY_SEPARATOR . 'units');

if (file_exists(__DIR__ . '/vendor/autoload.php') === true) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if (class_exists('mageekguy\atoum\reports\telemetry') === true && version_compare(phpversion(), '5.5.0', '>=')) {
    $telemetry = new reports\telemetry();
    $telemetry->readProjectNameFromComposerJson(__DIR__ . '/composer.json');
    $telemetry->addWriter(new std\out());
    $runner->addReport($telemetry);
}
