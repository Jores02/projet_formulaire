<?php

use PHPUnit\Framework\TestCase;

class FormPage extends TestCase
{
    public function test_form_page_display(): void
    {
        ob_start();
        curl_exec(curl_init("127.0.0.1:8080"));


        $output = ob_get_clean();

        $this->assertStringContainsString(
            '<h1>Formulaire avec 10 champs texte</h1>',
            $output
        );

        $this->assertStringContainsString(
            '<form method="post">',
            $output
        );
    }

    public function test_form_submit(): void
    {

        $host = '127.0.0.1';
        $dbname = 'tdR606';
        $user = 'root';
        $password = 'rootpassword';

        $curl = curl_init("127.0.0.1:8080");
        $fields = [];
        for ($i = 1; $i <= 10; $i++) {
            $name = "field$i";
            $fields[$name] = "value$i";
        }

        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM tirages");
        $stmt->execute();
        $data = $stmt->fetchAll();
        $count = count($data);

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($fields),
            CURLOPT_HTTPHEADER => [
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);
        curl_exec($curl);

        $stmt = $pdo->prepare("SELECT * FROM tirages");
        $stmt->execute();
        $data = $stmt->fetchAll();
        $count2 = count($data);
        $this->assertEquals($count2, $count + 1);


    }
}
