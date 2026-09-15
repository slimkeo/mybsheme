<?php
// DATABASE CONNECTION
$conn = new mysqli(
    'localhost',
    'snatbur1_user',
    'Snatburial2025!',
    'snatbur1_db'
);

// CHECK CONNECTION
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// NUMBERS TO SEARCH
$numbers = [
    // Original numbers
    '26876485054', '26878427967', '26876607413', '26876915289',
    '26876044463', '26876058489', '26876331661', '26876975585',
    '26876365271', '26876760546', '26876450127', '26876657494',
    '26876405491', '26876979973', '26876259529', '26876148846',
    '26876073023', '26879493599', '26878115543', '26876443687',
    '26876937720', '26876375757', '26876454323', '26876358260',
    '26876088459', '26876342685', '26876218831', '26879218830',
    '26876908908', '26876470459', '26876065042', '26876251202',
    '26876712591', '26876222820',
    '26876884040', // GAMA MTHOBISI
    '26876326935', // THWALA DUDU
    '26876044222', // NTSHANGASE NOSIMILO
    '26876063714', // MASEKO NONDUMISO
    '26878047817', // HLOPHE DUMSILE
    '26876880688', // DLAMINI SEBENTILE
    '26876327343', // SIFUNDZA NOMCEBO
    '26876672664', // MASUKU SIBUSISO
    '26876244250', // DLAMINI DUMSILE
    '26876053380', // MNCINA HLOBSILE
    '26876783159', // MAMBA ZODWA
    '26876083898', // NGWENYA NOSISA
    '26876154389', // MKHATSHWA GCEBILE
    '26876255496', // MHLANGA THULILE
    '26876225348', // MAGAGULA THULISILE
    '26876639201', // MAVUSO PHEPHILE
    '26876176546', // DLAMINI SIMANGELE
    '26878210952', // DLAMINI KHANYILE
    '26876542655', // MAKHUBU THULILE
    '26876041932', // MLOTSA THANDI
    '26876597175', // MKHATSHWA FISIWE
    '26878524855', // MNCINA PHUMLANI
    '26878382366', // DLAMINI WELILE
    '26876268239', // MAPHALALA MBALI
    '26876530914', // DLAMINI BONGEKILE
    '26876740094', // DLAMINI NKOSI
    '26876311012', // SIMELANE LINDIWE
    '26876065043', // DLAMINI BUSI
    '26878396182', // MTHETHWA BHEKI
];


// PREPARE NUMBERS FOR SQL
$quoted_numbers = array_map(function($num) use ($conn) {
    return "'" . $conn->real_escape_string($num) . "'";
}, $numbers);

$in_clause = implode(',', $quoted_numbers);

// QUERY EXISTING MEMBERS
$sql = "
    SELECT passbook_no, name, surname, cellnumber 
    FROM members 
    WHERE cellnumber IN ($in_clause)
";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// SMS API KEY
$api_key = "c25hdGJ1cmlhbEBzd2F6aS5uZXQtcmVhbHNtcw==";

echo "<h2>SMS Sending Report</h2>";

if ($result->num_rows > 0) {
    $success_count = 0;
    $fail_count = 0;

    while ($row = $result->fetch_assoc()) {
        $phone   = trim($row['cellnumber']);
        $passbook = trim($row['passbook_no']);
        $name    = trim($row['name']);
        $surname = trim($row['surname']);

        $message = "Reminder: You signed to repay funds taken from the SNAT Burial Scheme and eversince you made the commitment you have failed to fulfill the obligation. Settle the outstanding amount immediately or the matter will be escalated to legal authorities. For queries, contact +268 7623 1435.";

        $encoded = urlencode($message);
        $url = "https://www.realsms.co.sz/urlSend?_apiKey={$api_key}&dest={$phone}&message={$encoded}";

        // Send SMS
        $response = @file_get_contents($url);
        $http_response_header_str = implode("\n", $http_response_header ?? []);

        echo "<strong>SMS to:</strong> {$phone} | {$name} {$surname}<br>";

        if ($response !== false) {
            $success_count++;
            echo "<span style='color:green'>✅ Success</span><br>";
            echo "<small>Response: " . htmlspecialchars(substr($response, 0, 300)) . "</small><br>";
        } else {
            $fail_count++;
            echo "<span style='color:red'>❌ Failed</span><br>";
            echo "<small>Error: Could not reach SMS gateway.</small><br>";
        }

        echo "<hr>";

        // Optional: Log to database
        /*
        $log_sql = "INSERT INTO sms_logs (phone, message, response, status, date_sent) 
                    VALUES (
                        '{$conn->real_escape_string($phone)}',
                        '{$conn->real_escape_string($message)}',
                        '{$conn->real_escape_string($response)}',
                        '" . ($response !== false ? 'success' : 'failed') . "',
                        NOW()
                    )";
        $conn->query($log_sql);
        */
    }

    echo "<h3>Summary</h3>";
    echo "Total processed: " . $result->num_rows . "<br>";
    echo "✅ Successful: <strong>$success_count</strong><br>";
    echo "❌ Failed: <strong>$fail_count</strong><br>";

} else {
    echo "No matching members found in the database.";
}

$conn->close();
?>