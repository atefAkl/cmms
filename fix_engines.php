<?php
$files = glob('database/migrations/*.php');
foreach ($files as $f) {
    if (!is_file($f)) continue;
    $content = file_get_contents($f);
    
    // Check if it's already InnoDB or doesn't have Schema::create
    if (strpos($content, "engine('InnoDB')") !== false || strpos($content, "engine('INNODB')") !== false || strpos($content, 'Schema::create') === false) {
        continue;
    }

    // Replace all occurrences of the Blueprint closure to add the engine
    $content = str_replace(
        "function (Blueprint \$table) {", 
        "function (Blueprint \$table) {\n            \$table->engine('InnoDB');", 
        $content
    );
    
    file_put_contents($f, $content);
    echo "Updated: $f\n";
}
echo "Done.\n";
