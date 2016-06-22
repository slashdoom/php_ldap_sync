<?php
/************************************************************
* FILENAME:    ldap.php
* DESCRIPTION: ldap functions for php
* 
* AUTHOR:      Patrick K. Ryon (Slashdoom)
* LICENSE:     BSD 3-clause (see LICENSE file)
************************************************************/

function ldap_get_members($ldap_fqdn,$ldap_port,$ldap_user,$ldap_pass,$search_group) {

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
      $ldap_search_stat = ldap_search($ldap_conn_stat,$search_group,'cn=*',array('member'));
      if ($ldap_search_stat === FALSE) {
        // ldap search failed
        return "ldap search failed, check query info";
      }

      $members = ldap_get_entries($ldap_conn_stat,$ldap_search_stat);

      // no members found
      if(!isset($members[0]['member'])) {
        return "search completed but no members found";
      }

      // remove count header element
      array_shift($members[0]['member']);

      // Append to output
      $ldap_output = array_merge($ldap_output,$members[0]['member']);
		
      // Retrieve pagination information/position
      ldap_control_paged_result_response($ldap_conn_stat,$ldap_search_stat,$counter);
    } while($counter !== null && $counter != "");

    // disable pagination
    $member_attr = array();
    $member_result = array();
    ldap_control_paged_result($ldap_conn_stat,1);

    foreach($ldap_output as $member_dn) {
      $member_result_stat = ldap_search($ldap_conn_stat,$member_dn,'cn=*',$attributes);
      if ($member_result_stat === FALSE) {
        // ldap search failed
        return "ldap attribute search failed, check query info";
      }
      $member_attr = ldap_get_entries($ldap_conn_stat,$member_result_stat);
      // remove count header element
      array_shift($member_attr[0]['samaccountname']);
      array_shift($member_attr[0]['distinguishedname']);
      array_shift($member_attr[0]['userprincipalname']);
      array_shift($member_attr[0]['useraccountcontrol']);
      array_shift($member_attr[0]['mail']);
      $member_result = array_merge($member_result,$member_attr);
    }

}
