<?php

// ---------------------------------------------------------------------------------------------- //

// cd C:\Users\ahmetalper\Desktop\advanced_web_programming\exercise_2 && php task_1.php thesis

// ---------------------------------------------------------------------------------------------- //

function main(int $argument_count, array $arguments) : void {

    // ---------------------------------------------------------------------------------------------- //

    echo PHP_EOL . '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

    // ---------------------------------------------------------------------------------------------- //

    if ($argument_count < 2) {

        echo PHP_EOL . '[ ERROR ] | please provide a database name as a script parameter' . PHP_EOL . PHP_EOL;

        echo '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

        exit(1);

    }

    // ---------------------------------------------------------------------------------------------- //

    $database_name = $arguments[1];

    // ---------------------------------------------------------------------------------------------- //

    try {

        $database_connection = new PDO('mysql:host=localhost;dbname=' . $database_name . ';charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    } catch (PDOException $database_exception) {

        echo PHP_EOL . '[ ERROR ] | ' . $database_exception -> getMessage() . PHP_EOL . PHP_EOL;

        echo '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

        exit(1);

    }

    // ---------------------------------------------------------------------------------------------- //

    $tables_statement = $database_connection -> query('SHOW TABLES');

    $tables = $tables_statement -> fetchAll(PDO::FETCH_COLUMN);

    $backup_content = '';

    // ---------------------------------------------------------------------------------------------- //

    foreach ($tables as $table_name) {

        $columns_statement = $database_connection -> query('SHOW COLUMNS FROM ' . $table_name);

        $columns_array = $columns_statement -> fetchAll(PDO::FETCH_COLUMN);

        $columns_string = implode(', ', $columns_array);

        $rows_statement = $database_connection -> query('SELECT * FROM ' . $table_name);

        $rows = $rows_statement -> fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {

            $escaped_values = [];

            foreach ($row as $value) {

                if ($value === null) {

                    $escaped_values[] = 'NULL';

                } else {

                    $escaped_values[] = $database_connection -> quote($value);

                }

            }

            $values_string = implode(', ', $escaped_values);

            $backup_content .= 'INSERT INTO ' . $table_name . ' (' . $columns_string . ')' . PHP_EOL;

            $backup_content .= 'VALUES (' . $values_string . ');' . PHP_EOL . PHP_EOL;

        }

    }

    // ---------------------------------------------------------------------------------------------- //

    $text_filename = $database_name . '_backup.txt';

    $zip_filename = $database_name . '_backup.zip';

    file_put_contents($text_filename, $backup_content);

    echo PHP_EOL . '[ INFO ] | ' . $text_filename . ' file created' . PHP_EOL;

    // ---------------------------------------------------------------------------------------------- //

    $zip_archive = new ZipArchive();

    if ($zip_archive -> open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {

        $zip_archive -> addFile($text_filename, $text_filename);

        $zip_archive -> close();

        echo PHP_EOL . '[ INFO ] | ' . $zip_filename . ' file created' . PHP_EOL . PHP_EOL;

    } else {

        echo PHP_EOL . '[ ERROR ] | failed to create zip archive' . PHP_EOL . PHP_EOL;

    }

    // ---------------------------------------------------------------------------------------------- //

    echo '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

    // ---------------------------------------------------------------------------------------------- //

}

// ---------------------------------------------------------------------------------------------- //

main($argc, $argv);

// ---------------------------------------------------------------------------------------------- //

?>
