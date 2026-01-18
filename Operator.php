<html>
    <head>
        <title>
            PHP OPERATOR
        </title>
    </head>
<body>
    <h1>
    <?php

    /*** 
     * There Are Three Category Of Operators Are:
     * 
     * 1) Unary --> (++a),(--b),(~a) {Unary Operators Performs 1 operant execution Like (a++)}
     * 2) Binary --> (*),(+),(-) {Binary Operators Performs 2 operant execution Like (10+20)}
     * 3) Ternary --> (?),(:) {Ternary Operators Are Conditional Operator}
     * 
     * ***/
    
    /***
     * There Are 8 Types Of Oprators Are:
     * 
     * 1) Arithmetic operator --> (+),(-),(*),(**),(%),(/)
     * 2) Relational operator --> (//),(>),(<),(<==),(>==),(==),(!=),(===),(!==),(<>),(<=>)
     * 3) Assignment operator
     * 4) Increment/Decrement operator --> (++),(--)
     * 5) Logical operator --> (&&),(||),(!)
     * 6) String operator (Also called Concat operator) --> (.),(.=)
     * 7) Array operator
     * 8) Conditional assignment operator
     *  
     ***/

    //Arithmetic oprators in code:
    echo "Arithmetic Oprators:<br>";
    echo "<br>";
    echo "10+20= ".(10+20)."<br>";
    echo "10-10= ".(10-10)."<br>";
    echo "10/2= ".(10/2)."<br>";
    echo "10%10= ".(10%10)."<br>";
    echo "10**10= ".(10**10)."<br>"; // This Opratior (**) Is For Power Opration
    echo "<br>";

    //Relational operator
    $a=50;
    $b=60;
    $c=50;

    echo "Relational Oprators:<br>";
    echo "<br>";
    echo "50<60= ".($a<$b)."<br>";
    echo "50>60= ".($a>$b)."<br>";
    echo "50==60= ".($a==$c)."<br>"; //Now It Will Give True (But It Will Not ❌ Check Datatype)
    echo "50===60= ".($a===$c)."<br>"; //Now It Will Give True (But It Will ✔️ Check Datatype)
    echo "50<=>60= ".($a<=>$b)."<br>"; // It Will Give (+1) OR (0) OR (-1)
    echo "<br>";

    /*** 
     * This Below Code Is For Relational Operators
     * For Triple Equal
     * Too.
     * 
     * (===) See the below code 👇 we are comparing A,B And C
     * 
     * ***/

    if(($a===$b)==$c)
        echo "Hello<br>";
    else
        echo "Bye Bye!<br>";

    echo "<br>";

    //Increment/Decrement operator
    $a=50;

    echo "Increment/Decrement operator:<br>";
    echo "<br>";
    echo "a=a+1= ".(++$a)."<br>";  // $a=$a+1 --> Pre Increment
    echo "a=a+1= ".($a++)."<br>"; // $a=$a+1 --> Post Increment
    echo "a=a-1= ".(--$a)."<br>";  // $a=$a-1 --> Pre Increment
    echo "a=a-1= ".($a--)."<br>"; // $a=$a-1 --> Post Increment
    echo "<br>";

    /*** 
     * Q1. Assignment Question
     * 
     * $a=5;
     * $b=++$a;
     * $c=$b--;
     * $a=++$b;
     * $d=$a + ++$a + $a++;
     * 
     * What will be the answer of this question?
     * 
     * Answer: D= 21
     *         A= 8
     *         C= 6
     * 
     * ***/

    $a=5;
    $b=++$a;
    $c=$b--;
    $a=++$b;
    $d=$a + ++$a + $a++;

    echo "D= ".$d."<br>","A= ".$a."<br>","C= ".$c."<br>";
    echo "<br>";

    //Logical operator
    $a=50;
    $b=60;

    echo "Logical operator:<br>";
    echo "<br>";

    // (&&) AND operator

    if($a>25 && $b>10)
        echo "True<br>";
    else
        echo "False<br>";

    // (||) OR operator

    if($a>60 || $b>70)
        echo "True<br>";
    else
        echo "False<br>";

    // (!) NOT operator

    if(!($a>25 && $b>10))
        echo "True<br>";
    else
        echo "False<br>";

    echo "<br>";

    /*** 
     * Q2. Now What Will Be Output Of This Code:
     * 
     * $a=10;
     * $b=20;
     * 
     *     if($a and $b)
     *     echo "True<br>";
     *  else
     *     echo "False<br>";
     *
     * 
     * Answer: True (Becouse Both Numbers Having (1) Not Zero
     *         If We Write B=0 Then It Will Give False)
     *         
     * ***/

    ?>
    </h1>
</body>
<footer style="position:fixed; left:0; right:0; bottom:0; background:#f8f8f8; padding:10px 0; text-align:center; border-top:1px solid #e0e0e0; font-family:Arial, sans-serif;">
    <strong>Code Is Writen By <a href="https://www.linkedin.com/in/parasgupta-binary0101">Paras Gupta</a></strong>
</footer>
</html>