<?php
/**
 *  Trigger the file on plugin deactivation
 *
 * @package  CdFeedbackForm
 */

class CdFeedbackFormDeactivate
{
    public static function deactivate(){
            flush_rewrite_rules();
    }
} 