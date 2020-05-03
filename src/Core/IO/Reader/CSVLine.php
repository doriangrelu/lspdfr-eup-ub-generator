<?php


namespace App\Core\IO\Reader;


use App\Core\Exceptions\IO\CSVException;
use App\Core\Exceptions\IO\UnsupportedOperationException;

/**
 * Class CSVLine
 * @package App\Core\IO\Reader
 */
class CSVLine implements \ArrayAccess, \Countable, \Iterator
{

    /**
     * @var array
     */
    private $values = [];

    /**
     * CSVLine constructor.
     * @param array $headers
     * @param array $line
     * @throws CSVException
     */
    public function __construct(array $headers, array $line)
    {
        if (count($headers) !== count($line)) {
            throw new CSVException("Le fichier CSV est mal formé, le nombre de colone des lignes sont différentes des en-têtes. Merci de corriger cela.");
        }
        foreach ($headers as $k => $header) {
            $this->values[mb_strtoupper($header)] = $line[$k];
        }


    }


    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name) ?? null;
    }

    /**
     * @param $name
     * @param $value
     * @throws UnsupportedOperationException
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }


    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->values[mb_strtoupper($offset)] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new UnsupportedOperationException("Cannot modify line");
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new UnsupportedOperationException("Cannot modify line");
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->values);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->values);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->values);
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->values) !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        return reset($this->values);
    }

    public function toArray()
    {
        return $this->values;
    }
}