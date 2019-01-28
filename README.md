# Memquery
Sometime extracting data within a local variable will much easier if we can use SQL rather than using loop and conditional construct. Using in-memory Sqlite is an alternative way to do that. This small helper class provide simple methods to try that.

Principles
----------
0. The goal is execute SQL to a 2 dimentional array
1. Utilize local in-memory SQL library (PDO-SQLITE)
2. Not to be a full data source interface, only insert data, clear and drop table operations, no update and row delete, any change should repopulate data
3. Underlining handler is provided to do uncovered operations

```
// load the script
require('path_to/Taydh.Memquery.php');

// supposed we have data coming from csv
// parse CSV anyway as you see fit
$arrCsv = array_map('str_getcsv', file('data.csv'));
$fields = array_shift($arrCSV);

// create instance
$mq = new \Taydh\Memquery();

// create table and populate with data
$mq->createTable('data1', $fields, $arrCSV);

// run SQL query
$rs = $mq->fetchAll("select * from data1 where field1='abc'");
print_r($rs);
```

Filter with JOIN

```
$mq->createTable('animal', ['id','name','plant_id'], [[1,'cow',1],[2,'mosquito',2]]);
$mq->createTable('plant', ['id','name'], [[1,'Grass'],[2,'Tree']]);
$sql = "SELECT a.id, a.name, b.name as eat FROM animal a LEFT JOIN plant b ON b.id=a.plant_id WHERE a.name='cow'";
$rs = $mq->fetchAll($sql);
print_r($rs);
```
