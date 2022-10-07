<html>
<body>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    First: <input type="text" name="first" maxlength="6" required>
    End: <input type="text" name="end" minlength="6" maxlength="6" required>
    <input type="submit">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first'];
    $end = $_POST['end'];
    renderResult($first, $end);                             // If Request is POST Then render with POST
} else {
    if (isset($_GET['first']) && isset($_GET['end'])) {
        $first = $_GET['first'];
        $end = $_GET['end'];
        renderResult($first, $end);                         // If Request is GET Then render with GET
    }
}

function renderResult($first, $end)
{                                                                // Rendering result by inputs
    if (validateInputs($first, $end)) {                          // Validate Inputs
        $tickets = calculateLuckyTickets($first, $end);          // Calculate the Lucky Tickets and its count
        $count = count($tickets);                                // Count of Lucky Tickets
        echo "The count of lucky tickets: " . $count;            // Render Results
        echo '<pre>';
        print_r($tickets);
        echo '</pre>';
    } else {
        echo "First and End inputs are not correct";             // Validation Did Not Pass
    }
}

function calculateLuckyTickets($first, $end)
{
    $luckyTickets = [];
    foreach (range($first, $end) as $ticket) {
        $formattedTicket = str_pad($ticket, 6, "0", STR_PAD_LEFT);
        $splitted = str_split($formattedTicket, 3);              // Split ticket by 3 digits (result is array with 2 items)
        $first_result = calculateSumOfPart($splitted[0]);      // The sum of first part (3 digits)
        $second_result = calculateSumOfPart($splitted[1]);     // The sum of last part (3 digits)
        if ($first_result === $second_result) {                // Check if sum of two parts are equal
            $luckyTickets[] = $formattedTicket;
        }
    }

    return $luckyTickets;
}

function calculateSumOfPart($part)
{                                                              // Recursive Function
    $result = array_sum(str_split($part));                     // Split string to every digit (result is sum of array)
    if ($result >= 10) {                                       // Check if result contains from 2 digits
        $result = calculateSumOfPart($result);                 // If result is 2 digital number, then run again current function
    }
    return $result;                                            // Result will be only 1 digit;
}

function validateInputs($first, $end)
{
    $notEmpty = (!empty($first) || !empty($end));                       // Check if first and end is not empty
    $correctOrder = (intval($first) < intval($end));                    // Check if end is greater that first
    $correctDigitCount = (strlen($first) <= 6 && strlen($end) == 6);    // Check if both has right 6 digits
    return $notEmpty && $correctOrder && $correctDigitCount;             //Validate by above conditions
}

?>

</body>
