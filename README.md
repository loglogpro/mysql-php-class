Introduction
-------------------
There is my mysql-php class im working with. It passed through a lot of modifications since the beginning and there's more yet to come.
So, my final goal is to create a tiny, userful and multifunctional library for rapid developing of small/medium-sized web-applications which uses DB. 

Feel free to contribute.

HOW TO
-------------------
In fact, its extremely simple to get this thing to work.
Just define the associative config array, i.e.

```php
$config = array(           
           'host' = 'localhost',  
           'user' = 'dbuser',    
           'password' = 'your_password',       
           'db' = 'your_db_name',           
 );
```

Then create an instance by using:
```php
$db = DBC::GetDefault($config);
```

As you can see, because of specific of singleton's pattern you won't get more than 1 instance of this class. It also means you can have only one connection at the time.

Examples
-------------------

Insert query example:
```php
$sql = "INSERT INTO `table`(`row1`) VALUES ('val')";
$db->query($sql);
```
You also can get an ID of the new record by calling 'getLastID' method right after your query.
```php
$id = $db->getLastID();
```

Building large queries within a second:

Sometimes we need to build a large, boring INSERT/SELECT queries.
It's not a problem now - just let our singleton do all the dirty work.

So, having a input data array like this:
```php
$data = array(
    'foo' => 'bar',
    'val' => 'key',
);
```

After using 
```php
$db->sql_build_array('SELECT',$data);
```
We'll get the following result:

```php
foo = 'bar' AND val = 'key'
```

and this one for INSERT type instead of SELECT:

```php
(foo, val) VALUES ('bar', 'key')
```
Pretty simple, isn't it?
Also, all vars passed to the method will be automaticly filtered and escaped so you dont have to.

