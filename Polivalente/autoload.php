<?php


function autoload( $class ) {
      require 'classes/'.$class.'.php';

}
spl_autoload_register(autoload);
$class = 'App';
$code = "<?php class $class {
    public function run() {
        echo '$class<br>';  
    }
    ".'
    public function __call($name,$args) {
        $args=implode(",",$args);
        echo "$name ($args)<br>";
    }
}';

file_put_contents('classes/App.php' ,$code);

$a = new $class();
$a->run();