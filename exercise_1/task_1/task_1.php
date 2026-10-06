<?php

// ---------------------------------------------------------------------------------------------- //

// cd C:\Users\ahmetalper\Desktop\advanced_web_programming\exercise_1 && php task_1.php

// ---------------------------------------------------------------------------------------------- //

interface iRadio {

    public function create(string $work_name, string $work_text, string $work_link, string $identification_number) : void;

    public function save() : void;

    public function read() : array;

}

// ---------------------------------------------------------------------------------------------- //

class GraduateThesis implements iRadio {

    // ---------------------------------------------------------------------------------------------- //
    
    public string $work_name = '';
    
    public string $work_text = '';
    
    public string $work_link = '';
    
    public string $identification_number = '';

    // ---------------------------------------------------------------------------------------------- //

    public function create(string $work_name, string $work_text, string $work_link, string $identification_number) : void {

        $this -> work_name = $work_name;

        $this -> work_text = $work_text;

        $this -> work_link = $work_link;

        $this -> identification_number = $identification_number;

    }

    // ---------------------------------------------------------------------------------------------- //

    public function save() : void {

        try {

            $database_connection = new PDO('mysql:host=localhost;dbname=thesis;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $sql_statement = $database_connection -> prepare('INSERT INTO graduate_theses (work_name, work_text, work_link, identification_number) VALUES (:work_name, :work_text, :work_link, :identification_number)');

            $sql_statement -> execute([':work_name' => $this -> work_name,':work_text' => $this -> work_text,':work_link' => $this -> work_link,':identification_number' => $this -> identification_number]);

        } catch (PDOException $database_exception) {

            echo PHP_EOL . '[ ERROR ] | GraduateThesis | save() | ' . $database_exception -> getMessage() . PHP_EOL;

        }

    }

    // ---------------------------------------------------------------------------------------------- //

    public function read() : array {

        try {

            $database_connection = new PDO('mysql:host=localhost;dbname=thesis;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $sql_statement = $database_connection -> query('SELECT * FROM graduate_theses');

            return $sql_statement -> fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $database_exception) {

            echo PHP_EOL . '[ ERROR ] | GraduateThesis | read() | ' . $database_exception -> getMessage() . PHP_EOL;

            return [];

        }

    }

    // ---------------------------------------------------------------------------------------------- //

}

// ---------------------------------------------------------------------------------------------- //

function main() : void {

    // ---------------------------------------------------------------------------------------------- //

    echo PHP_EOL . '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

    // ---------------------------------------------------------------------------------------------- //

    try {

        $setup_connection = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $setup_connection -> exec('DROP DATABASE IF EXISTS thesis');

        $setup_connection -> exec('CREATE DATABASE thesis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        $setup_connection -> exec('USE thesis');

        $setup_connection -> exec('CREATE TABLE graduate_theses (id INT AUTO_INCREMENT PRIMARY KEY, work_name VARCHAR(255), work_text TEXT, work_link VARCHAR(255), identification_number VARCHAR(255))');

        echo PHP_EOL . '[ INFO ] | database \'thesis\' and table \'graduate_theses\' created' . PHP_EOL;

    } catch (PDOException $database_exception) {

        echo '[ ERROR ] | ' . $database_exception -> getMessage() . PHP_EOL;
        
        exit;

    }

    // ---------------------------------------------------------------------------------------------- //

    for ($page_number = 2; $page_number <= 6; $page_number++) {

        // ---------------------------------------------------------------------------------------------- //

        echo PHP_EOL . '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

        $page_url = 'https://stup.ferit.hr/zavrsni-radovi/page/' . $page_number;

        echo PHP_EOL . '[ INFO ] | ' . $page_url . PHP_EOL . PHP_EOL;

        // ---------------------------------------------------------------------------------------------- //

        $curl_handle = curl_init($page_url);

        curl_setopt_array($curl_handle, [CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false]);

        $html_result = curl_exec($curl_handle);

        $html_content = is_string($html_result) ? $html_result : '';

        if (empty($html_content)) {

            echo PHP_EOL . '[ ERROR ] | failed to fetch ' . $page_url . PHP_EOL;

            continue;

        }

        // ---------------------------------------------------------------------------------------------- //

        $dom_document = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom_document -> loadHTML($html_content);

        libxml_clear_errors();

        $dom_xpath = new DOMXPath($dom_document);

        $article_nodes = $dom_xpath -> query('//article[contains(@class, \'fusion-post-medium\')]');

        if ($article_nodes -> length === 0) {

            echo PHP_EOL . '[ ERROR ] | no articles found on ' . $page_url . PHP_EOL;

            continue;

        }

        // ---------------------------------------------------------------------------------------------- //

        foreach ($article_nodes as $article_node) {

            // ---------------------------------------------------------------------------------------------- //

            if (!($article_node instanceof DOMElement)) {

                continue;

            }

            // ---------------------------------------------------------------------------------------------- //

            $identification_number = '';

            $image_node = $dom_xpath -> query('.//img', $article_node) -> item(0);

            if ($image_node instanceof DOMElement) {

                $image_src = $image_node -> getAttribute('src');

                if (preg_match('/\/(\d+)\.(png|jpg|jpeg)$/i', $image_src, $matches)) {

                    $identification_number = $matches[1];

                }

            }

            // ---------------------------------------------------------------------------------------------- //

            $title_node = $dom_xpath -> query('.//h2[contains(@class, \'entry-title\')]/a', $article_node) -> item(0);

            // ---------------------------------------------------------------------------------------------- //

            if (!($title_node instanceof DOMElement)) {

                continue;

            }

            // ---------------------------------------------------------------------------------------------- //

            $work_name = $title_node -> nodeValue;

            // ---------------------------------------------------------------------------------------------- //

            $work_link = $title_node -> getAttribute('href');

            // ---------------------------------------------------------------------------------------------- //

            $inner_curl_handle = curl_init($work_link);

            curl_setopt_array($inner_curl_handle, [CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false]);

            $inner_result = curl_exec($inner_curl_handle);

            $inner_html = is_string($inner_result) ? $inner_result : '';

            // ---------------------------------------------------------------------------------------------- //

            $work_text = '';

            if (!empty($inner_html)) {

                $inner_dom = new DOMDocument();

                libxml_use_internal_errors(true);

                $inner_dom -> loadHTML($inner_html);

                libxml_clear_errors();

                $inner_xpath = new DOMXPath($inner_dom);

                $content_node = $inner_xpath -> query('//div[contains(@class, \'post-content\')]') -> item(0);

                if ($content_node instanceof DOMElement) {

                    $work_text = trim($content_node -> nodeValue);

                }

            }

            // ---------------------------------------------------------------------------------------------- //

            echo '[ INFO ] | ' . $work_name . ' | ' . $identification_number . PHP_EOL;

            // ---------------------------------------------------------------------------------------------- //

            $graduate_thesis = new GraduateThesis();

            $graduate_thesis -> create($work_name, $work_text, $work_link, $identification_number);

            $graduate_thesis -> save();

            // ---------------------------------------------------------------------------------------------- //

        }

        // ---------------------------------------------------------------------------------------------- //

    }

    // ---------------------------------------------------------------------------------------------- //

    echo PHP_EOL . '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

    // ---------------------------------------------------------------------------------------------- //

    $graduate_thesis = new GraduateThesis();

    $theses = $graduate_thesis -> read();

    // ---------------------------------------------------------------------------------------------- //

    foreach ($theses as $thesis) {

        echo PHP_EOL . 'id : ' . $thesis['id'] . PHP_EOL . PHP_EOL;

        echo 'work_name : ' . $thesis['work_name'] . PHP_EOL . PHP_EOL;

        echo 'work_link : ' . $thesis['work_link'] . PHP_EOL . PHP_EOL;

        echo 'identification_number : ' . $thesis['identification_number'] . PHP_EOL . PHP_EOL;

        echo 'work_text : ' . PHP_EOL . PHP_EOL . $thesis['work_text'] . PHP_EOL;

        echo PHP_EOL . '// ---------------------------------------------------------------------------------------------- //' . PHP_EOL;

    }

    // ---------------------------------------------------------------------------------------------- //

}

// ---------------------------------------------------------------------------------------------- //

main();

// ---------------------------------------------------------------------------------------------- //

?>
