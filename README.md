# characterAlert
very small plugin, which adds an alert if one of the other characters (Enhanced Account Switcher needed) have a new alert. 

Enhanced Account Switcher ist unbedingt nötig. Sonst funktioniert das Plugin in.

Das Plugin zeigt eine Meldung (wie bei einer PN) an, wenn ein verbundener Charakter einen neuen Alert hat. 

In der Datenbank wird in der Tabelle users ein Feld eingefügt, damit der Benutzer einstellen kann, ob er die Alerts angezeigt bekommt, oder nicht.

Variable
im header.tpl
{$characterAlert}

Templates:

```
characterAlert_index:
  <div class="char_alertBox pm_alert">
  {$characterAlert_row}
  </div>

characterAlert_row:
  <strong><a id="switch_{$alertTo['uid']}" href="#switch" class="switchlink">{$username}</span></a></strong> hat neue Alerts. <br/>
  
characterAlert_ucp:
  <table cellspacing="0" cellpadding="0"> 
  <tr>
  <td colspan="2"> 
	<fieldset class="trow2">
	<legend><strong>Characteralert</strong></legend>
  Benachrichtigung, wenn einer deiner verbundenen Charaktere einen Alert hat?<br/>
  <input type="radio" name="characterAlert" value ="1" {$cayes}> Ja   <input type="radio" name="characterAlert" value ="0" {$cano}> Nein<br/>
  </fieldset>
  </td>
  </tr>
  </table>
```
