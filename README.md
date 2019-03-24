# chgkdb-parser

Это компонтента для парсинга файлов в [текстовом формате Базы вопросов](https://db.chgk.info/format_voprosov) 
и форматирования в формате html

По сравнению с описанным форматом также поддерживаются:

### Раздатки
```
<раздатка>
   Текст раздатки
</раздатка>
```

### Картинки
```
(pic: 20180047.jpg)
(pic: http://db.chgk.info/images/db/20180047.jpg)
```
Если указано имя файла, а не полный путь то картинка берётся из http://db.chgk.info/images/db/

### Аудиофайлы
```
(aud: 20170002.mp3)
(aud: https://db.chgk.info/sounds/db/20170002.mp3)
```
Если указано имя файла, а не полный путь то файл берётся из http://db.chgk.info/sounds/db/

## Демо
http://api.baza-voprosov.ru/validator

## Установка
Для установки вам потребуется [git](https://git-scm.com/) и [composer](https://getcomposer.org/)

1. Standalone  
```
git clone https://github.com/chgk/chgkdb-parser.git chgk-parser
cd chgk-parser
composer install
```

2. Как компонента
В каталоге, где есть файл composer.json
```
composer config repositories.chgkdb-parser vcs https://github.com/chgk/chgkdb-parser
composer require chgk/chgkdb-parser
```

## Использование
### Из командной строки
```
bin/parser verify -e koi8-r -f text demo/ruch17st.txt 
```
-e -- кодировка. Если не указана, то считается, что файл в кодировке utf-8. 
-f -- входной формат (сейчас поддерживается только text, он же устанавливается по умолчанию)

### Из php-кода
Рабочий пример --- [demo/demo.php](https://github.com/chgk/chgkdb-parser/blob/master/demo/demo.php)
```
$iterator = new Chgk\ChgkDb\Parser\Iterator\FileLineIterator\FileLineIterator('demo/ruch17st.txt', 'koi8-r');
try {
    $package = (new Chgk\ChgkDb\Parser\ParserFactory\ParserFactory\ParserFactory())->getParser('text')->parse($iterator);
} catch (\Chgk\ChgkDb\Parser\TextParser\Exception\ParseException $e) {
    echo "Can not parse file:".$e->getMessage();
    exit;
}
$formatter = \Chgk\ChgkDb\Parser\Formatter\HtmlFormatter::create();
echo $formatter->format($package);
```

Реальное использование в [api](https://github.com/chgk/chgkdb-api):
* [POST /questions/validate](https://github.com/chgk/chgkdb-api/blob/v1.0.1/src/EventSubscriber/QuestionsVerifySubscriber.php#L58)
* [Демо](https://github.com/chgk/chgkdb-api/blob/v1.0.1/src/Controller/ValidatorFormController.php#L41)

## Добавление своего парсера
Если вам не нравится текстовый формат, вы можете написать свой парсер.
```
use Chgk\ChgkDb\Parser\Iterator\ParserIteratorInterface;
use Chgk\ChgkDb\Parser\ParserFactory\ParserInterface;
use Chgk\ChgkDb\Parser\Result\Package;


class TextParser implements ParserInterface
{
    public function parse(ParserIteratorInterface $iterator)
    {
        // Iterate the line iterator and build $package (instance of Package)
        return $package
    }
}
```
Оформите парсер как отдельную компоненту или сделайте PR к chgk-parser. Тогда База сможет использовать ваш формат.
