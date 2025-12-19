<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        THis is my first php code

        <?php
            // This is how you define a constant
            define('breakk', "<br>");

            $var1 = 5;
            $var2 = 5;
            $var2 = 6;

            echo $var1 ,$var2;
            echo "<br>";
            //Arithmetic Operators
            echo "The addition of $var1 + $var2 is";
            echo "<br>";  
            $sum = $var1 + $var2;
            echo "<br>";
            // echo var_dump($sum);
            echo "The value of (1==4) is";
            echo var_dump(1==4);
            echo "<br>";

            $var3 = "This is a string";
            echo breakk;
            echo var_dump($var3);


            // Arrays
            $Fruits = array("Mango", "orange", "Banana", "Kiwi");
            echo breakk;
            // echo var_dump($Fruits);
            // echo $Fruits[3];
            $length = count($Fruits);
            $intial = 0;
            while($intial < $length){
                // echo $Fruits[$intial];
                echo breakk;
                $intial++;
            }


            // Functions

            function printNumber($num){
                echo "Your number is $num";
            }

            printNumber(3);
        ?>
    </div>
</body>
</html>