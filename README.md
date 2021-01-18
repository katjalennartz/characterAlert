# characterAlert
very small plugin, which adds an alert if one of the other characters (Enhanced Account Switcher needed) have a new alert. 

Enhanced Account Switcher ist unbedingt nötig. Sonst funktioniert das Plugin in.

Das Plugin zeigt eine Meldung (wie bei einer PN) an, wenn ein verbundener Charakter einen neuen Alert hat. 

Keine Änderungen in der Datenbank.

Variable
im header.tpl
{$characterAlert}

Templates:

characterAlert_index:
  <div class="char_alertBox pm_alert">
  {$characterAlert_row}
  </div>

characterAlert_row:
  <strong><a id="switch_{$alertTo['uid']}" href="#switch" class="switchlink">{$username}</span></a></strong> hat neue Alerts. <br/>
