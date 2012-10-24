<?php
class SpecialSpammerHelper extends SpecialPage {
        function __construct() {
                parent::__construct( 'SpammerHelper' );
        }
 
        function execute( $par ) {
                $this->setHeaders();
                global $wgOut; // this is where we can put our output
        		$wgOut->addWikiText('==Special Page to deal with notorious spammers==');
        		$wgOut->addWikiText('<p>The following table shows accounts that have more than one deleted contribution:</p>');
        		$dbr = wfGetDB( DB_SLAVE );
        		$tables = array('user');
        		$vars = array('user_name');
        		$conds = 'ipb_user IS NULL';
        		$options = '';
        		$join_conds = array();
        		$limit = 50;
        		$res = $dbr->select($tables, $vars, $conds, __METHOD__, $options, $join_conds);
        		foreach ($res as $row) {
        			$wgOut->addWikiText('<p>');
        			$display = '<b>'.$row->user_name.'</b>';
        			$wgOut->addWikiText($display);
        		}
        }
}
// Idea for a SELECT, which identifies users eligible for a block.
// SELECT u.user_name, COUNT(ar.ar_title) archived, u.user_editcount edits
// FROM user u
// JOIN archive ar ON u.user_id = ar.ar_user
// LEFT OUTER JOIN ipblocks bl ON u.user_id = bl.ipb_user
// WHERE bl.ipb_user IS NULL
// GROUP BY u.user_name HAVING archived > 1 AND NOT edits > archived
// ORDER BY u.user_name;