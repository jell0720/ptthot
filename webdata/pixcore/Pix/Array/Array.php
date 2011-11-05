<?php

/**
 * Pix_Array_Array 
 * 這是傳入一個 array ，會把他生成 Pix_Array class
 * 
 * @uses Pix
 * @uses _Array
 * @package Pix_Array
 * @version $id$
 * @copyright 2003-2009 PIXNET
 * @author Shang-Rung Wang <srwang@pixnet.tw> 
 * @license 
 */
class Pix_Array_Array extends Pix_Array
{
    protected $_data = array();
    protected $_offset = 0;
    protected $_order = array();
    protected $_limit = null;
    protected $_cur_data = array();

    /**
     * __construct 
     * 傳一個 array $data 進來
     * 
     * @param array $data 
     * @access public
     * @return void
     */
    public function __construct(array $data)
    {
	$this->_data = $data;
	$this->_cur_data = $data;
    }

    public function getRand($count = null)
    {
	$rand_data = $this->_data;
	shuffle($rand_data);
	if ($count) {
	    return new Pix_Array_Array(array_slice($rand_data, 0, $count));
	} else {
	    return new $rand_data[0];
	}
    }

    public function getOffset()
    {
	return $this->_offset;
    }

    public function offset($offset = 0)
    {
	$this->_offset = $offset;
	return $this;
    }

    public function _sort($a, $b)
    {
	$way_num = array('asc' => 1, 'desc' => -1);
	foreach ($this->_order as $column => $way) {
	    if (is_array($a)) {
		if (strtolower($a[$column]) > strtolower($b[$column])) {
		    return $way_num[$way];
		}
		if (strtolower($a[$column]) < strtolower($b[$column])) {
		    return -1 * $way_num[$way];
		}
	    } else {
		if (strtolower($a->{$column}) > strtolower($b->{$column})) {
		    return $way_num[$way];
		}
		if (strtolower($a->{$column}) < strtolower($b->{$column})) {
		    return -1 * $way_num[$way];
		}
	    }
	}
	return 0;
    }

    public function getOrder()
    {
	return $this->_order;
    }

    public function order($order = null)
    {
	$resultorder = array();
	if (is_array($order)) {
	    foreach ($order as $column => $way) {
		if (is_int($column)) {
		    $resultorder[$way] = 'asc';
		    continue;
		}

		$resultorder[$column] = strtolower($way);
		if (!in_array(strtolower($way), array('asc', 'desc'))) {
		    $resultorder[$column] = 'asc';
		    continue;
		}
	    }
	}

	if (is_scalar($order)) {
	    $orders = explode(',', $order);
	    $resultorder = array();
	    foreach ($orders as $ord) {
		if (preg_match('#^`?([^` ]*)`?( .*)?$#', trim($ord), $ret)) {
		    $way = strtolower(trim($ret[2]));
		    $resultorder[$ret[1]] = $way;
		    if (!in_array($way, array('asc', 'desc'))) {
			$resultorder[$ret[1]] = 'asc';
		    }
		} else {
		    throw new Pix_Array_Exception('->order($order) 的格式無法判斷');
		}
	    }
	}
	$this->_order = $resultorder;
	return $this;
    }

    public function getLimit()
    {
	return $this->_limit;
    }

    public function limit($limit = null)
    {
	$this->_limit = $limit;
	return $this;
    }

    public function sum($column = null)
    {
	if (!$column) {
	    return array_sum($this->_data);
	}
	throw new Pix_Array_Exception('TODO');
    }

    public function max($column = null)
    {
	throw new Pix_Array_Exception('TODO');
    }

    public function min($column = null)
    {
	throw new Pix_Array_Exception('TODO');
    }

    public function first()
    {
	return $this->rewind()->current();
    }

    public function toArray($column = null)
    {
	if (!$column) {
	    return $this->rewind()->_cur_data;
	}
	$arr = array();
	foreach ($this->rewind()->_cur_data as $data) {
	    if ($data[$column]) {
		$arr[] = $data[$column];
	    }
	}
	return $arr;
    }

    public function getPosition($obj)
    {
	throw new Pix_Array_Exception('TODO');
    }

    public function count()
    {
	return count($this->_data);
    }

    public function seek($pos)
    {
	return $this->_data[$pos];
    }

    public function current()
    {
	return current($this->_cur_data);
    }

    public function next()
    {
	next($this->_cur_data);
	return $this;
    }

    public function key()
    {
	return key($this->_cur_data);
    }

    public function valid()
    {
	return isset($this->_cur_data[key($this->_cur_data)]);
    }

    public function rewind()
    {
	$this->_cur_data = $this->_data;
	if ($this->_order) {
	    uasort($this->_cur_data, array($this, '_sort'));
	}
	$this->_cur_data = array_slice($this->_cur_data, $this->_offset, $this->_limit, true);
	return $this;
    }

    public function offsetExists($pos)
    {
	return isset($this->_data[$pos]);
    }

    public funCtion offsetGet($pos)
    {
	return $this->_data[$pos];
    }

    public function __get($name)
    {
	return $this->_data[$name];
    }

    public function offsetSet($pos, $value)
    {
	if (is_null($pos)) {
	    $this->_data[] = $value;
	} else {
	    $this->_data[$pos] = $value;
	}
    }

    public function offsetUnset($pos)
    {
	unset($this->_data[$pos]);
    }

    public function push($value)
    {
	return array_push($this->_data, $value);
    }

    public function pop()
    {
	return array_pop($this->_data);
    }

    public function shift()
    {
	return array_shift($this->_data);
    }

    public function unshift($value)
    {
	return array_unshift($this->_data, $value);
    }

    public function reverse($preserve_keys = false)
    {
	return array_reverse($this->_data, $preserve_keys);
    }
}
