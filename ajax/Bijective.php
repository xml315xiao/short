<?php
class Bijective
{
    public $dictionary = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    public function __construct()
    {
        $this->dictionary = str_split($this->dictionary);
    }

    public function encode($i)
    {
        if ($i == 0)
            return $this->dictionary[0];

        $result = '';
        $base = count($this->dictionary);

        while ($i > 0)
        {
            $result[] = $this->dictionary[($i % $base)];
            $i = floor($i / $base);
        }

        $result = array_reverse($result);

        return join("", $result);
    }

    public function decode($input)
    {
        $i = 0;
        $base = count($this->dictionary);

        $input = str_split($input);

        foreach($input as $char)
        {
            $pos = array_search($char, $this->dictionary);

            $i = $i * $base + $pos;
        }

        return $i;
    }
}