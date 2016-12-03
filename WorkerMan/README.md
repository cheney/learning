# php async

用php7+WokerMan+redis实现php项目的轻量级异步

## Basic Usage

server :

```
php run.php start

```
client :

```php
<?php
$Redis = new PhpRedis();
$Redis->init(array('host'=>'127.0.0.1','port'=>'6801'));
// you process api
$demo['process_url'] = "http://127.0.0.1/demo/client.php?action=doit";
// task source 
$demo['task_source'] = "my_test";
// execute class 
$demo['class_name'] = "Test";
// execute method 
$demo['method_name'] = "output";
//if you method is static
$demo['method_type'] = "static"; // or empty;
//if you need params 
$demo['params'] = array("user_id"=>1);
//if you need callback some url
$demo['callback_url'] = "http://127.0.0.1/demo/client.php?action=callback";
// task json encode
$set_val = json_encode($demo);
//add task to list
$Redis->Lists()->push('task_list',$set_val);

```

## Available commands
```php run.php start  ```  
```php run.php start -d  ```  
```php run.php status  ```  
```php run.php stop  ```  
```php run.php restart  ```  
```php run.php reload  ```  
