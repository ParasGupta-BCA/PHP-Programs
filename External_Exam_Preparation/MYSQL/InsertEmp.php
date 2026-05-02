<html>
    <head>
        <title>
            MYSQL CONNECTION
        </title>
    </head>
    <body>
            <form method="POST" action=InsertEmp.php>
                Employee ID: <input type="number" name="eid" value="eid" required><br><br>
                Employee Name: <input type="text" name="ename" value="ename" required><br><br>
                Employee Salary: <input type="number" name="esalary" value="esalary" required><br><br>
                Employee Department: <input type="text" name="edept" value="edept" required><br><br>
                <br>

                <button type="submit" name="submit" value="submit">Submit</button>
            </form>
            <?php
            
            // Step-1 Establishing Connection With MYSQL
            $mycon=mysqli_connect("localhost","root","","mynewdata");
            echo "Connection Established Successfully!<br>";

           //  Step-2 Run Query 
           
        # This Is The Way We Can Insert The Record As A Static

        //    $eid=501;
        //    $ename="Paras Gupta";
        //    $esalary=500000;
        //    $edept="IT";
           
           if(isset($_POST['submit'])){

           $eid=$_POST['eid'];
           $ename=$_POST['ename'];
           $esalary=$_POST['esalary'];
           $edept=$_POST['edept'];


           $sql="insert into emp values(?,?,?,?)";
           $ps=$mycon->prepare($sql);
           $ps->bind_param("isis",$eid,$ename,$esalary,$edpet);
           $ps->execute();
           echo "<br>Record Inserted Successfully!";

           }
           

          //   Step-3 Close The Connection After Running Query On MYSQL
           mysqli_close($my_con);

            ?>
    </body>
</html>