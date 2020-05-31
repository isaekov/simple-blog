<?php


namespace System\model;


class Logic
{
    private int $index;

    private ModelCore $class;

    public function __construct(ModelCore $class, int $index)
    {
        $this->index = $index;
        $this->class = $class;
    }

    public function logicAnd()
    {
        $this->class->logic[$this->class->index] = " AND ";
        $this->class->index++;
        return $this->class;
    }
    public function logicOr()
    {
        $this->class->logic[$this->class->index] = " OR ";
        $this->class->index++;
        return $this->class;
    }

    public function confirm()
    {
        return $this->class->confirm();
    }
}