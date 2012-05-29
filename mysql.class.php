<?
//
// Mysql PHP Class
// https://github.com/okwinza/mysql-php-class
//
class DBC {
  private static $_cache; 
  private $_link;

  private function DBC($config) {
  
    $this->_link = mysql_pconnect($config['host'], $config['user'], $config['password']);
    if (!$this->_link) {
      die(__FUNCTION__." : DB connection error : "
              .mysql_error($this->_link));
    }
    $result = mysql_select_db($config['db'], $this->_link);
    if (!$result) {
      die(__FUNCTION__." : DB connection error : ".mysql_error($this->_link));
    }
    mb_internal_encoding("UTF-8"); // you may have to change this
    $sql = "SET NAMES 'utf8'";
    mysql_query($sql, $this->_link);
    mysql_query ("SET @@local.wait_timeout=900;", $this->_link);
    mysql_query ("SET @@wait_timeout=900;", $this->_link);

    mysql_query ("SET @@local.interactive_timeout=900;", $this->_link);
    mysql_query ("SET @@interactive_timeout=900;", $this->_link);

  }

  public static function getDefault($config) {
    if (is_null(self::$_cache)) {
      self::$_cache = new DBC($config);
    }
    return self::$_cache;
  }

  public function getError() {
    return "" . mysql_errno($this->_link) . ": " . mysql_error($this->_link);
  }
 
  //main query method. Will die on error.
  public function query($aQuery) {
    $result = mysql_query($aQuery, $this->_link);
    if (!$result) {
      die("DB connection error : ".mysql_error($this->_link));
    }
    return $result;
  }
  //Second query method. Will echo'd on error, not die.
  public function squery($aQuery) {
    $result = mysql_query($aQuery, $this->_link);
    if (!$result) {
      echo "DB connection error : " . mysql_error($this->_link);
    }
    return;
  }

  public function close() {
    mysql_close($this->_link);
    return;
  }
  
  //Getting ID of the last insterted record.
  public function getLastID(){
	return mysql_insert_id($this->_link);
  }
 
  public function _sql_validate_value($var) {
    if (is_null($var)) {
      return 'NULL';
    }
    else if (is_string($var)) {
      return "'" . $this->sql_escape($var) . "'";
    }
    else {
      return (is_bool($var)) ? intval($var) : $var;
    }
  }

  public function sql_escape($msg) {
    return @mysql_real_escape_string($msg);
  }
  

  //array-building helper method for long sql queries.
  public function sql_build_array($query, $assoc_ary = false) {
    if (!is_array($assoc_ary)) {
      return false;
    }

    $fields = $values = array();

    if ($query == 'INSERT' || $query == 'INSERT_SELECT') {
      foreach ($assoc_ary as $key => $var) {
        $fields[] = $key;

        if (is_array($var) && is_string($var[0])) {
          // This is used for INSERT_SELECT(s)
          $values[] = $var[0];
        }
        else {
          $values[] = $this->_sql_validate_value($var);
        }
      }

      $query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
    }
    else if ($query == 'UPDATE' || $query == 'SELECT') {
      $values = array();
      foreach ($assoc_ary as $key => $var) {
        $values[] = "$key = " . $this->_sql_validate_value($var);
      }
      $query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
    }

    return $query;
  }
}
?>