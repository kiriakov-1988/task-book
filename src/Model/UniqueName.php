<?php

namespace App\Model;


/**
 * Class UniqueName
 * Генерирует максимально возможное уникальное имя картинки.
 * Возвращает разные значения даже для одинаковых файлов.
 * @package App\Model
 */
class UniqueName
{
    /**
     * Массив данных для транслитерации
     * Русские/украинские буквы заменяются на соответствующие латинчкие в таком формате.
     * Пробелы в имени картинки заменяются на тире.
     * Остальные символы остаются неизменными.
     */
    const CONVERTER  = [
        ' ' => '-',

        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '',    'ы' => 'y',   'ъ' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

        'є' => 'e',   'і' => 'i',    'ї' => 'i',
        'Є' => 'E',   'І' => 'I',    'Ї' => 'I',
    ];

    /**
     * Содержит исходное имя картинки (как на ПК пользователя)
     *
     * @var string
     */
    private $fileName;

    /**
     * UniqueName constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Возвращает максимально возможную уникальную строку текста, которая используется как имя картинки.
     * Используется метка микровремени, дополнительно случайное число и само имя файла.
     *
     *
     * @return string
     */
    public function getUniqueFileName(): string
    {
        $uniqueTimeStamp = microtime(true) * 10000;

        $randomInteger = rand(10, 99);

        $transliterateName = $this->getTransliterateName();

        return $uniqueTimeStamp.'-'.$randomInteger.'-'.$transliterateName;

    }

    /**
     * Выполняет транслитирацию имени картинки
     *
     * @return string
     */
    private function getTransliterateName(): string
    {
        return strtr($this->fileName, self::CONVERTER);
    }
}