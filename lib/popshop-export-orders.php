<?php



function popshop_export_orders()
{
    // Yes... Sometimes a little hack is better than writing more complex function logic:
    $orders = popshop_get_orders(0, 100000);
    
    if (isset($_GET['format']) && ($_GET['format'] == "csv")) {
        popshop_export_orders_csv($orders);
        exit;
    }
    
    $output = array();
    foreach ($orders as $order) {
        $output[] = $order->details;
    }
    $output = '['. implode(",\n", $output) .']';
    
    header('Content-Description: File Transfer');
    header('Cache-Control: public, must-revalidate');
    header('Pragma: hack');
    header('Content-Type: text/plain');
    // header('Content-Disposition: attachment; filename="popshop-orders-' . date('Ymd-His') . '.json"');
    header('Content-Length: ' . strlen($output));
    echo $output;
    exit;
}




function popshop_export_orders_csv($orders)
{
    // We first compute the union of all possible fields (one level of depth):
    $structure = array();
    
    foreach ($orders as $order) {
        $order = json_decode($order->details, true);
        foreach ($order as $key => $value) {
            if (is_array($value)) {
                if (!isset($structure[$key])) {
                    $structure[$key] = array();
                }
                $structure[$key] = array_merge(array_flip(array_keys($value)), $structure[$key]);
            }
            else {
                $structure[$key] = "";
            }
        }
    }
    
    // Send headers:
    header('Content-Description: File Transfer');
    header('Cache-Control: public, must-revalidate');
    header('Pragma: hack');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="popshop-orders-' . date('Ymd-His') . '.csv"');
    
    
    $outstream = fopen("php://output", 'w');
    // Write to output, not to a file.
    // @see http://php.net/manual/en/function.fputcsv.php
    
    // Write CSV "header" line:
    $flat_structure = array();
    foreach ($structure as $field => $value) {
        if (is_array($value)) {
            foreach ($value as $subfield => $subvalue) {
                $flat_structure[] = $subfield;
            }
        }
        else {
            $flat_structure[] = $field;
        }
    }
    fputcsv($outstream, $flat_structure);
    
    // Finally, write CSV lines:
    foreach ($orders as $order) {
        $flat_line = array();
        $order = json_decode($order->details, true);
        foreach ($structure as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $subfield => $subvalue) {
                    if (isset($order[$field]) && isset($order[$field][$subfield])) {
                        $flat_line[] = $order[$field][$subfield];
                    }
                    else {
                        $flat_line[] = "";
                    }
                }
            }
            else {
                if (isset($order[$field])) {
                    $flat_line[] = $order[$field];
                }
                else {
                    $flat_line[] = "";
                }
            }
        }
        fputcsv($outstream, $flat_line);
    }
    
    fclose($outstream);
    exit;
}