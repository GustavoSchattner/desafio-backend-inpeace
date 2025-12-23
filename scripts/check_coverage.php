<?php

declare(strict_types=1);

if ($argc < 2) {
    echo "Usage: php scripts/check_coverage.php <clover-file> [threshold]
";
    exit(2);
}

$clover = $argv[1];
$threshold = isset($argv[2]) ? (int)$argv[2] : 80;

if (!is_readable($clover)) {
    echo "Coverage file not found: $clover\n";
    exit(3);
}

$xml = simplexml_load_file($clover);
if ($xml === false) {
    echo "Invalid XML in coverage file\n";
    exit(4);
}

$metrics = $xml->project->metrics ?? null;
if (!$metrics) {
    echo "Coverage metrics not found in file\n";
    exit(5);
}

$statements = (int) $metrics['statements'];
$covered = (int) $metrics['covered'];

if ($statements === 0) {
    echo "No statements found in coverage report\n";
    exit(6);
}

$percent = $covered / $statements * 100;
$percentFormatted = number_format($percent, 2);

echo "Coverage: $percentFormatted% (threshold: {$threshold}%)\n";

if ($percent < $threshold) {
    echo "Coverage check failed: below threshold\n";
    exit(1);
}

echo "Coverage check passed\n";
exit(0);
