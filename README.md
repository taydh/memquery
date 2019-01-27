# Memquery
Run SQL query in php using sqlite memory

```
require('path_to/Taydh.Memquery.php');

$memquery = new \Taydh\Memquery();

// parse CSV anyway as you see fit
$arrCsv = array_map('str_getcsv', file('data.csv'));
$fields = array_shift($arrCSV);

// create table and populate with data
$memquery->createTable('data1', $fields, $arrCSV);

$rs = $memquery->fetchAll('select * from data1');
```
