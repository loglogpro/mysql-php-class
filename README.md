Introduction
-------------------
There is my mysql-php class im working with. It passed through a lot of modifications since the beginning and there's more yet to come.
So, my final goal is to create a tiny, userful and multifunctional library for rapid developing of small/medium-sized web-applications which uses DB. 

Feel free to contribute.

Example & HOW TO
-------------------
In fact, its extremely simple to get this thing to work.
Just define the associative config array, i.e.
`$config = array (  

           'host' = 'localhost',
           'user' = 'dbuser',   
           'password' = 'your_password',  
           'db_name' = 'your_db_name',    
  
 );`

Then create an instance by using:
`$db = DBC::GetDefault($config);`

As you can see, because of specific of singleton's pattern you won't get more than 1 instance of this class. It also means you can have only one connection at the time.