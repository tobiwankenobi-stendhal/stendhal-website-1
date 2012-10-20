<?php
class SpecialSpammerHelper extends SpecialPage {
        function __construct() {
                parent::__construct( 'SpammerHelper' );
        }
 
        function execute( $par ) {
                $this->setHeaders();
                global $wgOut; // this is where we can put our output
        		$wgOut->addHTML('<h1>Special Page to deal with notorious spammers</h1>');
        		$wgOut->addHTML('The following table shows accounts that have more than one deleted contribution:');
        		$dbr = wfGetDB( DB_SLAVE );
        		$table = 'recentchanges';
        		$vars = 'rc_title';
        		$conds = '';
        		$res = $dbr->query('SELECT rc_title FROM recentchanges');
        		foreach ($res as $row) {
        			$display = '<b>'.$row.'</b>';
        			$wgOut->addHTML($display);
        		}
        }
}