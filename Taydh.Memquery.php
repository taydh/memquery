<?php
namespace Taydh;

/*
MEMQUERY is a helper class to execute sql query inside process scope using sqlite in-memory database
*/
class Memquery
{
	private $link;
	private $tables = array();
	
	function __construct()
	{
		$this->link = new \PDO('sqlite::memory:');
	}
	
	function isTableExists($table)
	{
		return isset($this->tables[$table]);
	}
	
	function createTable($table, $fields, $values = null)
	{
		$this->dropTable($table);
		$this->tables[$table] = $fields;
		
		$q = 'CREATE TABLE '.$table.' (';
		$count = count($fields);
		$keys = array_keys($fields);
		$hasType = is_string($keys[0]);
		
		for($i=0; $i<$count; $i++){
			$field = $hasType ? $keys[$i] : $fields[$i];
			$type = $hasType ? $fields[$field] : '';
			$q .= $field.' '.$type;
			
			if($i < $count-1){
				$q .= ',';
			}
		}
		
		$q .= ');';
		$this->link->exec($q);
		
		if($values != null){
			$this->insert($table, $values);
		}
	}
	
	function fetchAll($q)
	{
		return $this->link->query($q)->fetchAll();
	}
	
	private function insertData($table, $values)
	{
		$fields = $this->tables[$table];
		
		$q = 'INSERT INTO '.$table.' (';
		$count = count($fields);
		$keys = array_keys($fields);
		$hasType = is_string($keys[0]);
		
		for ($i=0; $i<$count; $i++){
			$field = $hasType ? $keys[$i] : $fields[$i];
			$type = $hasType ? $fields[$field] : '';
			
			$q .= $field.'';
			
			if ($i < $count-1){
				$q .= ',';
			}
		}
		$q .= ') VALUES (';
		
		for ($i=0; $i<$count; $i++){
			$field = $hasType ? $keys[$i] : $fields[$i];
			$type = $hasType ? $fields[$field] : '';
			
			$q .= "'".$values[$field]."'";
			if($i < $count-1){
				$q .= ',';
			}
		}
		
		$q .= ');';
		
		$this->link->exec($q);
	}
	
	function insert($table, $values)
	{
		if(is_array($values)){
			$count = count($values);
			
			if($count > 0 && is_array($values[0])){
				// bulk insert
				foreach($values as $subValues){
					$this->insertData($table, $subValues);
				}
			}
			else{
				$this->insertData($table, $values);
			}
		}
	}
	
	function clearTable($table)
	{
		$this->link->exec("DELETE FROM $table");
	}
	
	function dropTable($table)
	{
		$this->link->exec("DROP TABLE $table");
	}
}
?>
