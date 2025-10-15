<?php
// Intentional Sonar probe — DO NOT KEEP THIS IN PRODUCTION.
// Purpose: create clearly-detectable "new code" issues for SonarCloud.

# 1) Hardcoded credentials (security hotspot / secret)
$DB_USER = 'admin';
$DB_PASSWORD = 'P@ssw0rd_For_Sonar_Test_2025';

# 2) Magic numbers
$retries = 3;
$timeout = 5;

# 3) SQL concatenation (pattern similar to SQL injection)
function findUsersByName() {
    // Using superglobal and direct concatenation: Sonar will flag this pattern.
    $name = $_GET['name'] ?? 'anonymous';
    $sql = "SELECT * FROM users WHERE name = '" . $name . "' LIMIT 10";
    // pretend to return the SQL for analysis; don't execute anything
    return $sql;
}

# 4) Empty catch block (bad practice)
try {
    // pretend call that may throw
    if (false) {
        throw new \Exception('not really');
    }
} catch (\Throwable $e) {
    // intentionally empty — Sonar flags empty catch blocks
}

# 5) Long function with many statements (complexity / maintainability)
function longAndUglyFunction() {
    $a = 1; $b = 2; $c = 3; $d = 4; $e = 5;
    $f = 6; $g = 7; $h = 8; $i = 9; $j = 10;
    $k = $a + $b + $c + $d + $e + $f + $g + $h + $i + $j;
    $k *= 2;
    if ($k > 50) {
        $k -= 10;
    } else {
        $k += 5;
    }
    // duplicated logic to increase smell
    $k = $k + ($a * $b) - ($c * $d);
    $k = $k + ($a * $b) - ($c * $d);
    return $k;
}

# 6) Function missing type hints and returning mixed arrays frequently (weak typing)
function buildRecord($id, $title) {
    return ['id' => $id, 'title' => $title, 'done' => 0];
}

# 7) Duplicate code block (another duplication smell)
$rec1 = buildRecord(1, 'one');
$rec2 = buildRecord(2, 'two');
$rec3 = buildRecord(3, 'three');
$rec3 = buildRecord(3, 'three'); // duplicated line

// End of probe file.
