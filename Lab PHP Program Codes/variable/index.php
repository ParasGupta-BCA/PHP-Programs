<?php
$globalVar = "I am globa variable"."<br>";

function localScopeDemo() {
    $localVar = "I am local variable"."<br>";
    echo "Inside function: $localVar\n"."<br>";         

    global $globalVar;                            
    echo "Inside function (globalVar): $globalVar\n"."<br>";
}

function staticDemo() {
    static $counter = 0;
    echo "Static counter: $counter\n"."<br>";
    $counter++;
}

class Demo {
    public $prop = "class property"."<br>";

    public function showProp() {
        echo "Inside method: $this->prop\n"."<br>";
    }
}

$demo = new Demo();          
$demo->showProp();           
echo "Outside object: {$demo->prop}\n"."<br>";   

function printProp($propValue) {
    echo "Prop value passed by argument: $propValue\n"."<br>";
}
printProp($demo->prop);

localScopeDemo();   
staticDemo();       
staticDemo();       
staticDemo();

?>