<?php
/**
 * QueryBuilder
 * Clase encargada de generar consultas usando métodos de apoyo
 * @author nelson rojas
 */
class QueryBuilder {

    protected $_table = '';
    protected $_columns = '';
    protected $_conditions = '';
    protected $_joins = '';
    protected $_limit = '';
    protected $_group = '';
    protected $_having = '';
    protected $_orderBy = '';

    /**
     * constructor
     * @params table
     */
    public function __construct($tableName) {
        $this->_table = $tableName;
        return $this;
    }

    /**
     * columns
     * @params columns
     */
    public function columns($columns) {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * where
     * @params condition
     * @params operator
     */
    public function where($condition) {
        $operator = 'AND';
        if (empty($this->_conditions)) {
            $this->_conditions = $condition;
        } else {
            $this->_conditions .= $operator . ' ' . $condition;
        }

        $this->_conditions .= ' '; // extra space after condition
        return $this;
    }

    /**
     * orWhere
     * @params condition
     * @params operator
     */
    public function orWhere($condition) {
        $operator = 'OR';
        if (empty($this->_conditions)) {
            $this->_conditions = $condition;
        } else {
            $this->_conditions .= $operator . ' ' . $condition;
        }

        $this->_conditions .= ' '; // extra space after condition
        return $this;
    }

    /**
     * join
     * @params join
     */
    public function join($join) {
        $this->_joins .= $join . ' ';
        return $this;
    }

    /**
     * limit
     * @params limit
     */
    public function limit($limit) {
        $this->_limit = 'LIMIT ' . $limit;
        return $this;
    }

    /**
     * group
     * @params group
     */
    public function group($group) {
        $this->_group = ' GROUP BY ' . $group;
        return $this;
    }

    /**
     * having
     * @params having
     */
    public function having($having) {
        $this->_having = ' HAVING ' . $having;
        return $this;
    }

    /**
     * orderBy
     * @params orderBy
     */
    public function orderBy($orderBy) {
        $this->_orderBy = ' ORDER BY ' . $orderBy;
        return $this;
    }

    /**
     * generar string con la consulta a partir de los métodos 
     * cargados
     */
    public function __toString() {
        $sql = 'SELECT ' . (empty($this->_columns) ? '*' : $this->_columns) . 
        	   ' ' . 'FROM ' . $this->_table;

        $check_for = [
            '_joins',
            '_conditions',
            '_group',
            '_having',
            '_limit',
            '_orderBy'
        ];

        if (!empty($this->_conditions)) {
            $this->_conditions = 'WHERE ' . $this->_conditions;
        }

        foreach ($check_for as $element) :
            if (!empty(trim($this->$element))) :
                $sql .= ' ' . $this->$element . ' ';
            endif;

        endforeach;

        return $sql;
    }        

    /**
     * clear
     */
    private function clear() {
        $clear_in = [
            '_table',
            '_joins',
            '_conditions',
            '_group',
            '_having',
            '_limit',
            '_orderBy'
        ];
        foreach ($clear_in as $elem) :
            $this->$elem = '';
        endforeach
        ;        
    }

    /**
     * destruct
     */
    public function __destruct() {
        $this->clear();
    }

}
