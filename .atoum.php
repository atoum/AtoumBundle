<?php
use \mageekguy\atoum;

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

if(colorized())
{
    $script
        ->addDefaultReport()
            ->addField(new atoum\report\fields\runner\atoum\logo())
            ->addField(new atoum\report\fields\runner\result\logo())
    ;
}

$script->noCodeCoverageForNamespaces('mageekguy');
$script->bootstrapFile(TEST_ROOT . DIRECTORY_SEPARATOR . 'bootstrap.php');
$runner->addTestsFromDirectory(TEST_ROOT . DIRECTORY_SEPARATOR . 'units');
