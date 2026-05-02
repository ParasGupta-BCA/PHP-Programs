<?php

// PHP Indexed Arrays
$user=array("Paras Gupta","Tanish Gupta","Rakshit Jain","Prabhdeep","Arnav Arora","Arpit Gupta");

// Print Whole Array Value
print_r($user);
echo "<br>";

// Dispaly Single User Name
echo "<br>Zero Index   [0]: $user[0]";
echo "<br>First Index  [1]: $user[1]";
echo "<br>Second Index [2]: $user[2]";
echo "<br>Third Index  [3]: $user[3]";
echo "<br>Fourth Index [4]: $user[4]";
echo "<br>Fifth Index  [5]: $user[5]";
echo "<br>";

// Print/Display All The Users Without Using Array Functionality

for($i = 0; $i <= 5; $i++){
    echo "<br>$user[$i]";
}
echo "<br>";

// Foreach loop in array
$netflix = array("Stranger Things", "Squid Game", "Money Heist", "The Witcher", "Breaking Bad", "Dark");

foreach($netflix as $x){
    echo "<br>".$x;
}
echo "<br>";

// PHP Associative Arrays

$usersdetails=["name"=>"Paras Gupta","age"=>20,"city"=>"New Delhi","email"=>"parasgupta@gmail.com"];

// echo "<br><br>".$usersdetails['name'];
// echo "<br>".$usersdetails['age'];
// echo "<br>".$usersdetails['city'];
// echo "<br>".$usersdetails['email'];
echo "<font color='red'><b>";
foreach($usersdetails as $key => $userinfo){
    echo "<br>".$key.": ".$userinfo;
}
echo "</font><b>";

// PHP Multidimensional Array
$users=[[1,"Paras Gupta","New Delhi","parasgupta@gmail.com"],
        [2, "Tanish Gupta", "New Delhi","tanishgupta@gmail.com"],
        [3, "Arnav Arora", "New Delhi","arnavarora@gmail.com"]
    ];

    echo "<pre>";
    print_r($users);
    echo "</pre>";
?>