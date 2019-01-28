<?php
namespace Taydh;

/*
MEMQUERY is a helper class to execute sql query inside process scope using sqlite in-memory database
*/
class Memquery
{
	private $link;
	private $tableFields = array();
	
	function __construct()
	{
		$this->link = new \PDO('sqlite::memory:');
	}
	
	function isTableExists($table)
	{
		return isset($this->tableFields[$table]);
	}
	
	function createTable($table, $fields, $values = null)
	{
		$this->dropTable($table);
		$this->tableFields[$table] = $fields;
		
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
		if (!is_array($values) || empty($values)) return;
		
		$fields = $this->tableFields[$table];
		
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
		
		$assoc = is_string(array_keys($values)[0]);
			
		for ($i=0; $i<$count; $i++){
			if ($assoc) {
				$field = $hasType ? $keys[$i] : $fields[$i];
				$type = $hasType ? $fields[$field] : '';
				$value = $values[$field];
			} else {
				$value = $values[$i];
			}
			
			$q .= "'".$value."'";
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
			if(isset($values[0]) && is_array($values[0])){
				// bulk insert
				foreach($values as $rowValues){
					$this->insertData($table, $rowValues);
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
