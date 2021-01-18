<?php

/**
 * social network for mybb Plugin
 *
 * @author risuena
 * @version 1.0
 * @copyright risuena 2020
 * 
 */
// enable for Debugging:
// error_reporting(E_ERROR | E_PARSE);
// ini_set('display_errors', true);



// Disallow direct access to this file for security reasons
if (!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
function characterAlert_info()
{
    global $lang, $db, $plugins_cache, $mybb;

    return array(
        "name" => "Alertsanzeige für Mehrfachcharaktere",
        "description" => "Zeigt eine Meldung an, wenn verbundene Charaktere neue Alerts haben",
        "author" => "risuena",
        "authorsite" => "https://lslv.de/risu",
        "version" => "1.0",
        "compatability" => "18*"
    );
}

function characterAlert_is_installed()
{
}

function characterAlert_install()
{
    global $db;
    $insert_array = array(
        "title" => "characterAlert_index",
        "template" => '
        <div class="char_alertBox pm_alert">
        {$characterAlert_row}
        </div>
        ',
        "sid" => "-1",
        "version" => "",
        "dateline" => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);

    $insert_array = array(
        'title'        => 'characterAlert_row',
        'template'    => '
        <strong><a id="switch_{$alertTo[\\\'uid\\\']}" href="#switch" class="switchlink">{$username}</span></a></strong> hat neue Alerts. <br/>
        ',
        'sid'        => '-1',
        'version'    => '',
        'dateline'    => TIME_NOW
    );
    $db->insert_query("templates", $insert_array);
}

function characterAlert_uninstall()
{
    global $db;
    //remove templates
    $db->delete_query("templates", "title LIKE 'characterAlert_%'");
}

function characterAlert_activate()
{
    //im memberprofil variable hinzufügen
    include  MYBB_ROOT . "/inc/adminfunctions_templates.php";
    find_replace_templatesets("header", "#" . preg_quote('{$pm_notice}') . "#i", '{$pm_notice}{$characterAlert}');
}

function characterAlert_deactivate()
{
    include  MYBB_ROOT . "/inc/adminfunctions_templates.php";
    find_replace_templatesets("header", "#" . preg_quote('{$characterAlert}') . "#i", '');
}

$plugins->add_hook('global_start', 'characterAlert_header');
function characterAlert_header()
{
    global $db, $mybb, $templates, $characterAlert, $characterAlert_row, $alertTo;
    $all_uids = getCharacters();

    $getAlerts = $db->write_query("SELECT DISTINCT(uid) FROM " . TABLE_PREFIX . "alerts WHERE uid IN (" . $all_uids . ") and unread = 1");
    $thisuser = (int) $mybb->user['uid'];
    $rownum = $db->num_rows($getAlerts);
    $flag = 0;
    while ($alertTo = $db->fetch_array($getAlerts)) {
        $user = get_user($alertTo['uid']);
        $username = format_name(
            htmlspecialchars_uni($user['username']),
            $user['usergroup'],
            $user['displaygroup']
        );
        if ($thisuser != $alertTo['uid']) {
            eval("\$characterAlert_row .= \"" . $templates->get("characterAlert_row") . "\";");
        }
        if ($thisuser == $alertTo['uid']) {
            $flag = 1;
        }
    }
    if ($rownum > 0) {
        if ($flag != 1) {
            eval("\$characterAlert= \"" . $templates->get("characterAlert_index") . "\";");
        }
    }
}

/**
 * Get the shared Accounts from Accountswitcher
 * @return string all_uids 
 */
//EIGENE ÄNDERUNG
function getCharacters()
{
    global $db, $mybb;
    $thisuser = (int) $mybb->user['uid'];
    $as_uid = (int) $mybb->user['as_uid'];
    $all = array();
    if ($as_uid == 0) {
        $hauptchar = $thisuser;
        $get_all_uids = $db->query("SELECT uid FROM " . TABLE_PREFIX . "users WHERE 
			 		   ((as_uid=$thisuser) OR (uid=$thisuser)) ORDER BY username");
    } else if ($as_uid != 0) { //nicht mit Hauptaccoung online
        //id des users holen wo alle angehangen sind + alle charas
        $hauptchar = $as_uid;
        $get_all_uids = $db->query("SELECT uid FROM " . TABLE_PREFIX . "users WHERE 
					  ((as_uid=$as_uid) OR (uid=$thisuser) OR (uid=$as_uid)) 
			 		  ORDER BY username");
    }

    while ($uid = $db->fetch_array($get_all_uids)) {
        array_push($all, $uid['uid']);
    }

    $all_ids = implode(',', $all);
    return $all_ids;
}
