<?php namespace Classes;

use Exception;

class Designer
{
    public
        $name,
        $surname,
        $avatar,
        $salary;


    /**
     * Функция загрузки данных с внешнего источника
     * @throws Exception
     */
    public function load()
    {
        $html = file_get_contents('https://zety.com/blog/graphic-designer-resume');

        # Получить Имя и Фамилию
        $names = $this->getNames($html);
        $this->name = $names->name;
        $this->surname = $names->surname;

        # Получить аватар
        $this->avatar = $this->getAvatarSRC($html);
    }

    /**
     * Функция получения имени и фамилии из html-строки
     * @param $html
     * @return object - [name:(string) Имя, surname:(string) Фамилия]
     * @throws Exception
     */
    protected function getNames($html): object
    {
        $fullname = $this->wordBetweenWords('<div class="author__name">','</div>', $html);
        $fullname = explode(' ', $fullname);

        # Validation
        if(count($fullname) !== 2) throw new Exception('Fullname parsing error');

        return (object) [
            'name' => $fullname[0],
            'surname' => $fullname[1]
        ];
    }

    /**
     * Функция получения ссылки на аватар
     * @param $html
     * @return string
     * @throws Exception
     */
    protected function getAvatarSRC($html): string
    {
        $avatar_src = $this->wordBetweenWords('class="author__image" src="','"', $html);
        
        # Validation
        if(!$avatar_src || !preg_match('/^\/\/.*[.jpg|.jpeg|.webp|.png]$/', $avatar_src))
            throw new Exception('Avatar parsing error');
        $avatar_src = str_replace('//', 'https://', $avatar_src);
        return $avatar_src;
    }


    /**
     * Функция для быстрого получения фрагмента строки между
     * указанных фрагментов (быстрее и удобнее чем по regexp)
     * @param string $entry_start - Стартовое вхождение строки
     * @param string $entry_end - Конечное вхождение строки
     * @param string $string - Строка
     * @return string - Фрагмент строки между начальным и конечным вхождениями
     * @throws Exception
     */
    function wordBetweenWords(string $entry_start, string $entry_end, string $string): string
    {
        if(!$entry_start || !$entry_end || !$string)
            throw new Exception('Input data error');

        $a = explode($entry_start, $string);
        if(!isset($a[1])) return false;
        $b = explode($entry_end, $a[1]);
        if(!isset($b[0])) return false;
        return trim($b[0]);
    }

    /**
     * Функция вычисления зарплаты
     * @param $class
     * @return string
     * @throws Exception
     */
    function Calc($class): string
    {
        $class = intval($class);
        if($class < 1 || $class > 3)
            throw new Exception('Input data error');
        return ($class+1) * 5000;
    }

}