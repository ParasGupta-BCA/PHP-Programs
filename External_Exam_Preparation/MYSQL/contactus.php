<html>
    <head>
        <title>
            Contact Us
        </title>
    </head>
    <body>
        <form method="POST" action="contactus.php">
            <h1>Contact Us</h1><br>
            Name: <input type="text" name="name" value="name" required><br><br>
            Email ID: <input type="email" name="email" value="email" required><br><br>
            Phone No: <input type="number" name="number" value="number" required><br><br>
            Message: <input type="text" name="message" value="message" required><br><br>
            <br>
            <button type="submit" name="submit" value="submit">Submit</button>
        </form>
        <?php

        if(isset($_POST['submit'])){
            
            // Step-1 Establishing Connection With MYSQL

            $my_con=mysqli_connect("localhost","root","","contactusinfo");
            echo "Connection Established Successfully!<br>";


            //  Step-2 Run Query

            $name=$_POST['name'];
            $email=$_POST['email'];
            $number=$_POST['number'];
            $message=$_POST['message'];

            $sql="insert into contactus value(?,?,?,?)";
            $ps=$my_con->prepare($sql);
            $ps->bind_param("ssis",$name,$email,$number,$message);
            $ps->execute();
            echo "<br>Record Inserted Successfully!";

            //   Step-3 Close The Connection After Running Query On MYSQL
            mysqli_close($my_con);

        }
        ?>
    </body>
</html>