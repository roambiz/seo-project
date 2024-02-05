<?php
/*
 * Class: Flush_CPT
 * Description: Handles flushing rewrite rules after custom post type registration.
 * Path: modules/flush.php
 */

class Flush_CPT {

    const CPT_TRANSIENT_FLAG = 'add_flush_after_cpt';

    public static function init() {
        $flush_first = new self();
        $flush_first->add_flush_after_cpt();
    }

    public function add_flush_after_cpt() {
        // Check if the flag exists in transients
        if (get_transient(self::CPT_TRANSIENT_FLAG)) {
            // Flush the rewrite rules
            flush_rewrite_rules();
            // Delete the transient to avoid repeated flushing
            delete_transient(self::CPT_TRANSIENT_FLAG);
        }
    }
}

