<?php
header('Content-Type: text/plain');
echo "Checking Environment Variables:\n\n";

$mysql_url = getenv('MYSQL_URL');
echo "getenv('MYSQL_URL') = " . ($mysql_url ? $mysql_url : 'EMPTY/NOT SET') . "\n";

echo "\nAll \$_ENV:\n";
print_r($_ENV);

echo "\nAll \$_SERVER:\n";
foreach ($_SERVER as $key => $val) {
    if (strpos($key, 'MYSQL') !== false || strpos($key, 'DB_') !== false || strpos($key, 'RAILWAY') !== false) {
        echo "$key = $val\n";
    }
}
