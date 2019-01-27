# Memquery
Sometime extracting data within a local variable will much easier if we can use SQL rather than using loop and conditional construct. Using in-memory Sqlite is an alternative way to do that. This small helper class provide simple methods to try that.

```
// load the script
require('path_to/Taydh.Memquery.php');

// create instance
$memquery = new \Taydh\Memquery();

// supposed we have data coming from csv
// parse CSV anyway as you see fit
$arrCsv = array_map('str_getcsv', file('data.csv'));
$fields = array_shift($arrCSV);

// create table and populate with data
$memquery->createTable('data1', $fields, $arrCSV);

// run SQL query
$rs = $memquery->fetchAll("select * from data1 where field1='abc'");
```
