<?php
// export_report.php
include '../database/db_connection.php';

// Get parameters from URL
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query (same logic as reports.php)
$where_conditions = array();

switch($report_type) {
    case 'daily':
        $where_conditions[] = "DATE(visit_date) = CURDATE()";
        $filename = "Daily_Visitor_Report_" . date('Y-m-d');
        break;
        
    case 'weekly':
        $where_conditions[] = "WEEK(visit_date) = WEEK(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())";
        $filename = "Weekly_Visitor_Report_" . date('Y-W');
        break;
        
    case 'monthly':
        $where_conditions[] = "MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())";
        $filename = "Monthly_Visitor_Report_" . date('Y-m');
        break;
        
    case 'date_range':
        if($start_date && $end_date) {
            $where_conditions[] = "visit_date BETWEEN '$start_date' AND '$end_date'";
            $filename = "Visitor_Report_$start_date" . "_to_$end_date";
        }
        break;
        
    default:
        $filename = "Visitor_Report_" . date('Y-m-d');
}

if($status_filter) {
    $where_conditions[] = "status = '$status_filter'";
}

$where_clause = '';
if(!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(' AND ', $where_conditions);
}

$query = "SELECT * FROM visitors $where_clause ORDER BY visit_date DESC, sign_in_time DESC";
$result = mysqli_query($conn, $query);

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
header('Cache-Control: max-age=0');

// Output Excel content
echo '<table border="1">';
echo '<tr>';
echo '<th>Sr.No</th>';
echo '<th>Visitor ID</th>';
echo '<th>Visitor Name</th>';
echo '<th>Phone Number</th>';
echo '<th>Visit Purpose</th>';
echo '<th>Date</th>';
echo '<th>Sign In Time</th>';
echo '<th>Sign Out Time</th>';
echo '<th>Status</th>';
echo '<th>Signed In By</th>';
echo '<th>Signed Out By</th>';
echo '</tr>';

$i = 1;
while($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>' . $i . '</td>';
    echo '<td>' . htmlspecialchars($row['visitor_id']) . '</td>';
    echo '<td>' . htmlspecialchars($row['visitor_name']) . '</td>';
    echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
    echo '<td>' . htmlspecialchars($row['visit_purpose']) . '</td>';
    echo '<td>' . htmlspecialchars($row['visit_date']) . '</td>';
    echo '<td>' . htmlspecialchars($row['sign_in_time']) . '</td>';
    echo '<td>' . htmlspecialchars($row['sign_out_time']) . '</td>';
    echo '<td>' . htmlspecialchars($row['status']) . '</td>';
    echo '<td>' . htmlspecialchars($row['signed_in_by']) . '</td>';
    echo '<td>' . htmlspecialchars($row['signed_out_by']) . '</td>';
    echo '</tr>';
    $i++;
}

echo '</table>';
?>