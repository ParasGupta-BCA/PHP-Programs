<?php
$income = 750000; // Example income

switch(true){
    case ($income < 500000):
        $tax = 0;
        break;
    case ($income >= 500000 && $income < 1000000):
        $tax = ($income - 500000) * 0.10; // 10% tax for income between 500k and 10L
        break;
    case ($income >= 1000000):
        $tax = ($income - 1000000) * 0.20 + 50000; // 20% tax for income above 10L + base tax
        break;
    default:
        $tax = 0;
        break;
}

echo "For an income of $income, the tax is: $tax";
?>