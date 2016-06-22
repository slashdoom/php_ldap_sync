<?php
/************************************************************
* FILENAME:    logging.php
* DESCRIPTION: ldap functions for php
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

  function ldap_get_members($ldap_fqdn,$ldap_port,$ldap_dn,$ldap_user,$ldap_pass,$search_group) {

    // define attributes to keep
    $attributes = array(
      "samaccountname",
      "distinguishedname",
      "userprincipalname",
      "useraccountcontrol",
      "mail"
    );
 
    // connect to ldap
    $ldap_conn_stat = ldap_connect($ldap_fqdn,$ldap_port);
    if ($ldap_conn_stat === FALSE) {
      // could not connet
      $logging->error("could not connect to ldap server, check domain settings");
      return false;
    }
    
    // bind as ldap_user
    $ldap_bind_stat($ldap_conn_stat,$ldap_user,$ldap_pass);
    if ($ldap_bind_stat === FALSE) {
      // could not bind ldap user
      $logging->error("could not bind to ldap server, check user settings");
      return false;
    }
    
 	  // build up ldap query
 	  $ldap_query = "(&(objectClass=user)(objectCategory=person))(memberOf=CN=".$group.",".$ldap_dn.")";

    // run ldap search
    $ldap_search_stat = ldap_search($ldap_conn_stat,$ldap_dn,$ldap_query);
    if ($ldap_search_stat === FALSE) {
      // ldap search failed
      $logging->error("ldap search failed, check query info");
      return false;
    }
    
    $ldap_results = ldap_get_entries($ldap_conn_state, $ldap_search_stat);
 
     // remove blank first entry
    array_shift($ldap_results);
 
    $ldap_output = array();
 
    $i = 0;
    // build up output array
    foreach($ldap_results as $u) {
      foreach($keep as $x) {
      	// check for specified attributes
    		if(isset($u[$x][0])) {
      		$attr_val = $u[$x][0]
      	}
      	  else $attr_val = NULL;
    		}
        // append specified attributes to output array
        $ldap_output[$i][$x] = $attr_val;
      }
      $i++;
    }
 
  return $ldap_output;
}
