<?php
declare(strict_types=1);
$clover = $argv[1] ?? 'coverage.clover';
if (!is_readable($clover)) { echo "Coverage file not found: $clover\n"; exit(2); }
$xml = simplexml_load_file($clover);
$files = [];
foreach ($xml->xpath('//file') as $f) {
    $m = $f->metrics;
    $stat = (int) ($m['statements'] ?? 0);
    $cov = (int) (($m['coveredstatements'] ?? $m['covered']) ?? 0);
    $name = (string) $f['name'];
    if ($stat > 0) {
        $files[$name] = ['stat' => $stat, 'cov' => $cov, 'pct' => ($cov / $stat * 100)];
    }
}
uasort($files, fn($a, $b) => $a['pct'] <=> $b['pct']);
foreach ($files as $name => $d) {
    printf("%6.2f%% %4d/%-4d %s\n", $d['pct'], $d['cov'], $d['stat'], $name);
}
