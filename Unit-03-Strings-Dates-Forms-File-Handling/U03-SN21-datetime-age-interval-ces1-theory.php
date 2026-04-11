    <?php
    // Set your birthdate
    $birthdate = "2005-07-10 08:30:00";

    // Current date & time
    $currentDate = new DateTime();
    $birthDateObj = new DateTime($birthdate);

    // Calculate difference
    $interval = $currentDate->diff($birthDateObj);

    // Display exact age with time
    echo "Hey Ankit, you are "
        . $interval->y . " years "
        . $interval->m . " months "
        . $interval->d . " days "
        . $interval->h . " hours "
        . $interval->i . " minutes "
        . $interval->s . " seconds old.<br>";

    echo "<b>Keep growing 🎉</b>";
    ?>

    //or 

    <?php
    // Set your birthdate
    $birthdate = "2005-07-10 08:30:00";

    // Current date & time
    $currentDate = new DateTime();
    $birthDateObj = new DateTime($birthdate);

    // Calculate difference
    $interval = $currentDate->diff($birthDateObj);

    // Convert total days into hours, minutes, seconds
    $totalDays = $interval->days;
    $totalHours = $totalDays * 24 + $interval->h;
    $totalMinutes = $totalHours * 60 + $interval->i;
    $totalSeconds = $totalMinutes * 60 + $interval->s;

    // Display message
    echo "Hey <Ankit> you are "
        . $interval->y . " years "
        . $interval->m . " months "
        . $interval->d . " days old.<br>";

    echo "You have spent $totalHours hours, $totalMinutes minutes, and $totalSeconds seconds on this beautiful Earth.<br>";

    echo "<b>Happy Birthday 🎉</b>";
    ?>
