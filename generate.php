<?php
define('SYSTEM', $_SERVER['DOCUMENT_ROOT'], true);
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 17.10.16
 * Time: 22:51
 */
function generateSampleName($sample)
{
    return SYSTEM . "/samples/{$sample}.php";
}

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !(
        isset($_POST['codeSample'])
        && ($filename = generateSampleName($_POST['codeSample']))
        && file_exists($filename)
    )
) {
    http_response_code(403);
    exit ("Method not allowed");
}

include_once($filename);

if (!isset($codeSample)) {
    exit(json_encode([
        'success' => false,
        'error' => "codeSample " . htmlentities($_POST['codeSample']) . ".php parsing error",
    ]));
}

try {
    /** @var array $codeSample */
    require_once(SYSTEM . "/Core/generate.php");

    $generator = new Generate($codeSample);

    $template = $generator->render(['border' => 1, 'class' => 'generatedTable']);

    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 4);

    $json = [
        'success' => true,
        'generated' => $template,
        'time' => $total_time,
    ];
} catch (Exception $ex) {
    $json = [
        'success' => false,
        'error' => $ex->getMessage(),
        'trace' => $ex->getTraceAsString(),
    ];
}
exit(json_encode($json));


