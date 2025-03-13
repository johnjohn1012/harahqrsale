<?php
require_once '../config/database.php';

try {
    $stmt = $conn->query("SELECT * FROM tables ORDER BY table_number");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $output = '';
    foreach ($tables as $table) {
        $statusClass = '';
        $statusText = '';
        
        switch ($table['status']) {
            case 'AVAILABLE':
                $statusClass = 'available';
                $statusText = 'Available';
                break;
            case 'OCCUPIED':
                $statusClass = 'occupied';
                $statusText = 'Occupied';
                break;
            case 'DIRTY':
                $statusClass = 'dirty';
                $statusText = 'Needs Cleaning';
                break;
        }
        
        $output .= '
        <div class="table-box ' . $statusClass . ' shadow" 
             onclick="updateTableStatus(' . $table['table_id'] . ', \'' . ($table['status'] === 'DIRTY' ? 'AVAILABLE' : ($table['status'] === 'OCCUPIED' ? 'DIRTY' : 'OCCUPIED')) . '\')">
            <div class="text-center">
                <h4>Table ' . htmlspecialchars($table['table_number']) . '</h4>
                <p class="mb-0">' . $statusText . '</p>
            </div>
        </div>';
    }
    
    echo $output;
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?> 