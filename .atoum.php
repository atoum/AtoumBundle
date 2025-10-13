<?php

use atoum\atoum;
use atoum\atoum\reports;
use atoum\atoum\writers\std;

define('TEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR . 'tests');

function colorized(): bool {
    $color = -1;
    if(false !== ($term = getenv('TERM'))) {
        if(preg_match('/\d+/', $term, $matches) > 0) {
            $color = (int) $matches[0];
        }
    }

    if($color < 0) {
        $color = (int) system('tput colors 2> /dev/null');
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

$script->noCodeCoverageForNamespaces('atoum');
$runner->addTestsFromDirectory(TEST_ROOT . DIRECTORY_SEPARATOR . 'units');

if (file_exists(__DIR__ . '/vendor/autoload.php') === true) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if (class_exists('atoum\atoum\reports\telemetry') === true) {
    $telemetry = new reports\telemetry();
    $telemetry->readProjectNameFromComposerJson(__DIR__ . '/composer.json');
    $telemetry->addWriter(new std\out());
    $runner->addReport($telemetry);
}
