<?php

namespace App\Library;

/**
 *
 * Применяется для итерации по массиву с целочисленными последовательными индексами
 *
 */
class MyIterator {

    /**
     *
     * Позиция элемента в массиве
     *
     * @var integer
     */
    protected $position = 0;

    /**
     *
     * Итерируемый массив
     *
     * @array type
     */
    protected $array = [];

    /**
     *
     * Конструктор класса
     * Помещает итерируемый массив во внутреннюю переменнную класса
     * Устанавливает указатель на начало
     *
     * @param array $arr
     */
    public function __construct( &$arr ) {
        $this->array = &$arr;
        if( is_null( $this->array ) ){
            $this->position = 0;
        } else {
            $this->rewind();
        }
    }

    /**
     * Переместить указатель в начало массива
     */
    public function rewind() {
        reset( $this->array );
        $this->position = key(  $this->array  );
    }

    /**
     *
     * Получить текущий элемент
     *
     * @return type
     */
    public function current() {
        return $this->array[$this->position];
    }

    /**
     *
     * Получить ключ текущего элемента
     *
     * @return integer
     */
    public function key() {
        return $this->position;
    }

    /**
     * Сдвинуть указатель на следующий элемент
     */
    public function next() {
        ++$this->position;
    }

    /**
     *
     * Проверка на сущестование элемента
     *
     * @return boolean
     */
    public function valid() {
        return isset($this->array[$this->position]);
    }

    /**
     *
     * Передвинуть указатель на предыдущий элемент
     *
     * @return integer
     */
    public function prev() {
        --$this->position;
    }

    /**
     *
     * Получить весть итерируемый массив
     *
     * @return array
     */
    public function getAll(){
        return $this->array;
    }

    /**
     *
     * Проверка наличия потомков у элемента
     *
     * @param integer $id_parent
     * @return boolean
     */
    public function hasChildrens( $id_parent  ){
        $a = false;
        foreach ( $this->array as $k => $v) {
            if( $v->id_parent == $id_parent ) {
                $a = true;
            }
        }
        return $a;
    }


    /**
     * Удаление элемента
     *
     * @return void
     */
    public function remove(){
        unset( $this->array[$this->position] );
    }
}
