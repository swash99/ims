<?php
require_once "database_table.php";


class InvoiceBulkTable extends DataBaseTable {

    public static function get_tracked_invoices() {
    $sql = "SELECT * FROM InvoiceBulk
            ORDER BY date_end DESC";

        return parent::query($sql);
    }

    public static function track_invoice($date_start, $date_end, $date_created) {

        $sql = "INSERT INTO InvoiceBulk (date_start, date_end, date_created)
                VALUES ('$date_start', '$date_end', '$date_created')
                ON DUPLICATE KEY UPDATE
                date_start = VALUES(date_start), date_end = VALUES(date_end), date_created = VALUES(date_created)";

        return parent::query($sql);
    }

    public static function get_tracked($date_start, $date_end) {
        $sql = "SELECT * FROM InvoiceBulk
                WHERE  date_start = '$date_start'
                AND date_end = '$date_end'";

        if ($result = parent::query($sql)) {
            $tracked = count($result->fetch_assoc());
            return $tracked;
        }
    }

    public static function remove_invoice($date_start, $date_end) {
        $sql = "DELETE FROM InvoiceBulk
                WHERE date_start = '$date_start'
                AND date_end = '$date_end'";

        return parent::query($sql);
    }
}
?>