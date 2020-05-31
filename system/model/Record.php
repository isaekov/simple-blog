<?php


namespace System\model;


use ReflectionClass;
use ReflectionException;
use System\db\Database;

class Record extends Database
{


    private ReflectionClass $reflection;

    /**
     * @var static  $class
     *
     */
    private string $class;

    private object $classObj;

    private array $classProperty = [];


    public function __construct($class)
    {

       parent::__construct();
        if (is_object($class)) {
            $this->classObj = $class;
        } else {
            $this->class = $class;
        }
        try {
            $this->reflection = new ReflectionClass($class);
            foreach ($this->reflection->getProperties() as $property) {
                $this->preg($this->reflection->getProperty($property->getName())->getDocComment(), $property->getName());
            }
        } catch (ReflectionException $e) {
            $e->getMessage();
        }
    }
        

    public function setProperty(array $data)
    {
        if (empty($data)) {
            return null;
        }
        $object = new $this->class;
        try {
            $this->propertyAs($data, $object);
        } catch (ReflectionException $e) {
        }
        return $object;
    }


    /**
     * @param array $data
     * @return array
     */
    public function setProperties(array $data)
    {
        if (empty($data)) {
            return null;
        }
        $arraysObject = [];
        $object = null;
        try {
            foreach ($data as $datum) {
                $object = new $this->class;
                $this->propertyAs($data, $object);
                $arraysObject[] = $object;
            }
        } catch (ReflectionException $e) {
        }
        return $arraysObject;
    }

    public function getVars()
    {
        $tmp = [];
        $data = array_column($this->reflection->getProperties(), "name");
        foreach ($data as $index => $value) {
            try {
                $d = $this->reflection->getProperty($value);
                $d->setAccessible(true);
                $tmp[$value] = $d->getValue($this->classObj);
            } catch (ReflectionException $e) {

            }
        }
        array_shift($tmp);
        return $tmp;
    }

    private function preg($str, $prop) {
        $re = '/@propertyAlias\("([A-Za-z_]+)/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        if (is_array($matches) && !empty($matches)) {
            $this->classProperty[$matches[0][1]] = $prop;
        }
    }

    private function ad($str){
        $re = '/@propertyAlias\("([A-Za-z_]+)/m';
        preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
        return $matches[0][1];
    }

    public function id($as = false){
        if (!$as) {
            return $this->reflection->getProperties()[0]->getName();
        }
        return $this->ad($this->reflection->getProperties()[0]->getDocComment());
    }

    /**
     * @param array $data
     * @param $object
     * @throws ReflectionException
     */
    private function propertyAs(array $data, $object): void
    {
        foreach ($data as $key => $value) {
            if ($this->reflection->hasProperty($key)) {
                $valueSet = $this->reflection->getProperty($key);
                $valueSet->setAccessible(true);
                $valueSet->setValue($object, $value);
            } else {
                if (isset($this->classProperty[$key])) {
                    $valueSet = $this->reflection->getProperty($this->classProperty[$key]);
                    $valueSet->setAccessible(true);
                    $valueSet->setValue($object, $value);
                }
            }
        }
    }


    public function save()
    {
        $sql = "INSERT INTO " . self::camelToSnakeForClass();
        $sql .= "(" . implode(", ", array_keys($this->getVars())) . ") VALUES ";
        $sql .= "('" . implode("','", $this->getVars()) . "')";
        dd(sql);exit;
        $this->pdo->query($sql);
    }

    public static function camelToSnakeForClass()
    {
        preg_match_all('/\w+$/m', get_called_class(), $matches, PREG_SET_ORDER, 0);
        $snake = preg_replace('/[A-Z]/', '_$0', end($matches)[0]);
        return ltrim(strtolower($snake), '_');
    }
}