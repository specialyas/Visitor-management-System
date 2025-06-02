<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// reports.php
include '../database/db_connection.php';

// Handle form submissions
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
?>

<h2 style="color:#AF3E3E">Visitor Reports</h2>

<!-- Report Selection Form -->
<div class="panel panel-default">
    <div class="panel-heading">Generate Reports</div>
    <div class="panel-body">
        <form method="GET" action="">
            <input type="hidden" name="page" value="reports">
            
            <div class="row">
                <div class="col-md-3">
                    <label>Report Type:</label>
                    <select name="report_type" class="form-control" required>
                        <option value="">Select Report Type</option>
                        <option value="daily" <?php echo ($report_type=='daily')?'selected':''; ?>>Daily Report</option>
                        <option value="weekly" <?php echo ($report_type=='weekly')?'selected':''; ?>>Weekly Report</option>
                        <option value="monthly" <?php echo ($report_type=='monthly')?'selected':''; ?>>Monthly Report</option>
                        <option value="date_range" <?php echo ($report_type=='date_range')?'selected':''; ?>>Date Range</option>
                        <option value="status_report" <?php echo ($report_type=='status_report')?'selected':''; ?>>Status Report</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                </div>
                
                <div class="col-md-3">
                    <label>End Date:</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                </div>
                
                <div class="col-md-3">
                    <label>Status Filter:</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Signed In" <?php echo ($status_filter=='Signed In')?'selected':''; ?>>Signed In</option>
                        <option value="Signed Out" <?php echo ($status_filter=='Signed Out')?'selected':''; ?>>Signed Out</option>
                    </select>
                </div>
            </div>
            <!-- index.php?page=visitors -->
            <br>
            <!-- <button type="submit" class="btn btn-primary">Generate Report</button> -->
            <a href="export_report.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success">Export to Excel</a>
            <!-- <button type="button" onclick="printReport()" class="btn btn-info">Print Report</button> -->
        </form>
    </div>
</div>

<?php
if($report_type) {
    // Build query based on report type
    $where_conditions = array();
    
    switch($report_type) {
        case 'daily':
            $where_conditions[] = "DATE(visit_date) = CURDATE()";
            $report_title = "Daily Visitor Report - " . date('Y-m-d');
            break;
            
        case 'weekly':
            $where_conditions[] = "WEEK(visit_date) = WEEK(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())";
            $report_title = "Weekly Visitor Report - Week " . date('W, Y');
            break;
            
        case 'monthly':
            $where_conditions[] = "MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())";
            $report_title = "Monthly Visitor Report - " . date('F Y');
            break;
            
        case 'date_range':
            if($start_date && $end_date) {
                $where_conditions[] = "visit_date BETWEEN '$start_date' AND '$end_date'";
                $report_title = "Visitor Report - $start_date to $end_date";
            }
            break;
            
        case 'status_report':
            $report_title = "Status Report";
            break;
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
    $total_visitors = mysqli_num_rows($result);
?>

<div id="reportContent">
    <h3><?php echo $report_title; ?></h3>
    <p><strong>Total Visitors:</strong> <?php echo $total_visitors; ?></p>
    <p><strong>Generated on:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <?php if($total_visitors > 0): ?>
    <table class="table table-bordered table-striped">
        <thead>
            <tr class="success">
                <th>Sr.No</th>
                <th>Visitor ID</th>
                <th>Visitor Name</th>
                <th>Phone Number</th>
                <th>Visit Purpose</th>
                <th>Date</th>
                <th>Sign In Time</th>
                <th>Sign Out Time</th>
                <th>Status</th>
                <th>Signed In By</th>
                <th>Signed Out By</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            while($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars($row['visitor_id']); ?></td>
                <td><?php echo htmlspecialchars($row['visitor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($row['visit_purpose']); ?></td>
                <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                <td><?php echo htmlspecialchars($row['sign_in_time']); ?></td>
                <td><?php echo htmlspecialchars($row['sign_out_time']); ?></td>
                <td>
                    <span class="badge <?php echo ($row['status']=='Signed In') ? 'badge-success' : 'badge-info'; ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($row['signed_in_by']); ?></td>
                <td><?php echo htmlspecialchars($row['signed_out_by']); ?></td>
            </tr>
            <?php 
            $i++;
            endwhile; 
            ?>
        </tbody>
    </table>
    
    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-md-6">
            <h4>Summary Statistics</h4>
            <?php
            // Get status counts
            $status_query = "SELECT status, COUNT(*) as count FROM visitors $where_clause GROUP BY status";
            $status_result = mysqli_query($conn, $status_query);
            ?>
            <table class="table table-sm">
                <tr><th>Status</th><th>Count</th></tr>
                <?php while($status_row = mysqli_fetch_assoc($status_result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($status_row['status']); ?></td>
                    <td><?php echo $status_row['count']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    
    <?php else: ?>
    <div class="alert alert-info">No visitors found for the selected criteria.</div>
    <?php endif; ?>
</div>

<?php } ?>

<script>
function printReport() {
    var printContents = document.getElementById('reportContent').innerHTML;
    var originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>