<?php
/************************************************************
* FILENAME:    ldap.php
* DESCRIPTION: ldap functions for php
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

function ldap_get_members($ldap_fqdn,$ldap_port,$ldap_dn,$ldap_user,$ldap_pass,$search_group) {

    // connect to ldap
    $ldap_conn_stat = ldap_connect($ldap_fqdn,$ldap_port);
    if ($ldap_conn_stat === FALSE) {
      // could not connet
      return "could not connect to ldap server, check domain settings";
    }
    
    // bind as ldap_user
    ldap_set_option($ldap_conn_stat,LDAP_OPT_PROTOCOL_VERSION,3);
    $ldap_bind_stat = ldap_bind($ldap_conn_stat,$ldap_user,$ldap_pass);
    if ($ldap_bind_stat === FALSE) {
      // could not bind ldap user
      return "could not bind to ldap server, check user settings";
    }

    // pagination to overcome 1000 entry limit
    $ldap_output = array();
    $ldap_pagesize = 1000;
    $counter = "";
    do {
		// paginated results
		ldap_control_paged_result($ldap_conn_stat,$ldap_pagesize,true,$counter);

    // run ldap search
    $ldap_search_stat = ldap_search($ldap_conn_stat,$ldap_dn,'cn=*',array('member'));
    if ($ldap_search_stat === FALSE) {
      // ldap search failed
      return "ldap search failed, check query info";
    }

		$members = ldap_get_entries($ldap_conn_stat,$results);

		// no members found
		if(!isset($members[0]['member'])) {
		  return false;
		}

		// remove count header element
		array_shift($members[0]['member']);

		// Append to output
		$ldap_output = array_merge($ldap_output,$members[0]['member']);
		
		// Retrieve pagination information/position
		ldap_control_paged_result_response(ldap_conn_stat,$ldap_results,$counter);
	} while($counter !== null && $counter != "");

	// return member list
	return $output;
}
