<?php

use Chgk\ChgkDb\Parser\Iterator\FileLineIterator;
use Chgk\ChgkDb\Parser\ParserFactory\ParserFactory;

require_once __DIR__.'/../vendor/autoload.php';

$iterator = new FileLineIterator(__DIR__.'/ruch17st.txt', 'koi8-r');

try {
    $package = (new ParserFactory())->getParser('text')->parse($iterator);
} catch (\Chgk\ChgkDb\Parser\TextParser\Exception\ParseException $e) {
    echo "Can not parse file:".$e->getMessage();
    exit;
}
?>
<meta charset="utf-8">
<style>
    p.first {
        display: inline;
    }
    div.field {
        margin-top: 0.5em;
        margin-bottom: 0.9em;
    }
    .field p {
        margin-top: 3px;
        margin-bottom: 3px;
        text-indent: 2em;
    }
    div.razdatka {
        background-color: #EEEEFF;
        padding: 4px;
        margin-top: 35px;
        border: 1px solid black;
    }
    div.razdatka:before {
        content: "Раздаточный материал";
        margin-top: -31px;
        margin-bottom: 9px;
        margin-left: -5px;
        padding-left: 4px;
        display: block;
        border: 1px solid black;
        background-color: #EEEEFF;
        width: 230px;
        height: 25px;
    }
    audio {
        width: 600px;
    }
</style>
<?php
$formatter = \Chgk\ChgkDb\Parser\Formatter\HtmlFormatter::create();

echo $formatter->format($package);


