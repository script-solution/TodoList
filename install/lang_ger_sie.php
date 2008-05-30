<?php
$LANG['installationtitle'] = 'Installation von '.TODOLIST_VERSION;
$LANG['available'] = 'Verf&uuml;gbar';
$LANG['notavailable'] = 'Nicht Verf&uuml;gbar';
$LANG['ok'] = 'OK';
$LANG['notok'] = 'Nicht OK';
$LANG['password'] = 'Passwort';
$LANG['database'] = 'Datenbank';
$LANG['refresh'] = 'Aktualisieren';
$LANG['next_message'] = 'N&auml;chste Nachricht';
$LANG['previous_message'] = 'Letzte Nachricht';
$LANG['edit_message'] = 'Bearbeiten';
$LANG['information'] = 'Information';
$LANG['position'] = 'Position';
$LANG['type'] = 'Typ';
$LANG['error_occurred'] = 'Folgende Angaben fehlen noch oder sind nicht korrekt';

$LANG['step1'] = 'Schritt 1: Einstellungen';
$LANG['step2'] = 'Schritt 2: &Uuml;berpr&uuml;fung der MySQL-Tabellen Struktur';
$LANG['step3'] = 'Schritt 3: Installation';

$LANG['type_post'] = 'Beitrag';
$LANG['type_pm'] = 'Private Message';
$LANG['type_signature'] = 'Signatur';
$LANG['type_link'] = 'Linklisten-Beschreibung';
$LANG['error_text'] = 'Fehler';
$LANG['edit_messages_success'] = 'Die Nachricht wurde erfolgreich editiert.';
$LANG['step7_success'] = 'Alle Nachrichten wurden editiert!';

$LANG['error']['phpversion'] = 'Sie m&uuml;ssen mindestens PHP-Version 4.1.0 besitzen';
$LANG['error']['mysql'] = 'Sie m&uuml;ssen mindestens MySQL 3.x besitzen';
$LANG['error']['chmod_install'] = 'Setzen Sie den CHMOD des Verzeichnisses "install" auf 0777';
$LANG['error']['chmod_install_config'] = 'Setzen Sie bitte den CHMOD der Datei "install/user_config.php" auf 0666';
$LANG['error']['mysql_connect'] = '&Uuml;berpr&uuml;fen Sie "Host", "Login" und "Passwort" der MySQL-Einstellungen';
$LANG['error']['mysql_select_db'] = '&Uuml;berpr&uuml;fen Sie den Datenbanknamen';
$LANG['error']['board_url'] = 'Bitte geben Sie den TodoList-Pfad an.';

$LANG['voraussetzungenerfuellt'] = 'Alle Voraussetzungen f&uuml;r die Installation sind erf&uuml;llt.';
$LANG['noterfuellt'] = 'Es sind nicht alle Voraussetzungen erf&uuml;llt';

$LANG['back'] = 'Zur&uuml;ck';
$LANG['forward'] = 'Weiter';
$LANG['finish'] = 'Installieren';

$LANG['yes'] = 'Ja';
$LANG['no'] = 'Nein';
$LANG['board_url'] = 'TodoList - URL';
$LANG['board_url_desc'] = 'Die absolute URL zu Ihrer TodoList. D.h., wenn die TodoList z.B. hier liegt: "http://www.domain.de/todolist/index.php", dann w&auml;re der URL: "http://www.domain.de/todolist"<br />
Wichtig ist, dass der letzte "/" nicht angegeben wird.';
$LANG['table_praefix'] = 'Tabellen-Pr&auml;fix';
$LANG['btn_update'] = 'Aktualisieren';

$LANG['table_exists_error'] = 'Wenn Sie eine Vollinstallation durchf&uuml;hren wollen, darf keine Tabelle bereits vorhanden sein.<br />Falls Sie die TodoList ein weiteres Mal in dieser Datenbank installieren wollen oder aus anderen Gr&uuml;nden die selbe Datenbank nutzen m&ouml;chten, k&ouml;nnen Sie oben auf dieser Seite das "Tabellen-Pr&auml;fix" ver&auml;ndern.';
$LANG['toboard'] = 'Gehe zur TodoList';
$LANG['installation_complete'] = 'Die Installation wurde erfolgreich abgeschlossen. Bitte l&ouml;schen Sie die Datei "install.php" jetzt.';
$LANG['writing_install_config_failed'] = 'Die Datei "install/config.php" konnte nicht ver&auml;ndert werden. &Uuml;berpr&uuml;fen Sie bitte ob der CHMOD der Datei 0666 ist.';
$LANG['writing_install_community_failed'] = 'Die Datei "install/community.php" konnte nicht ver&auml;ndert werden. &Uuml;berpr&uuml;fen Sie bitte ob der CHMOD der Datei 0666 ist.';
$LANG['writing_install_mysql_config_failed'] = 'Die Datei "install/mysql_config.php" konnte nicht erstellt werden. &Uuml;berpr&uuml;fen Sie bitte ob der CHMOD des Verzeichnisses "install" 0777 ist.';
?>
