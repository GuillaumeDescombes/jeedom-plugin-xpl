<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* ------------------------------------------------------------ Inclusions */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../class/xpl.core.class.php';
include_file('core', 'xpl', 'class', 'xpl');
include_file('core', 'xpl', 'config', 'xpl');

class security { 

    public static function parserMessage($_message) {
        $type = $_message->getIdentifier(); // 1,2,3
        if ($type == xPLMessage::xplcmnd) {
          return false;
        }
        /* Status
         * security.gateinfo
            {
              protocol=[X10|UPB|CBUS|ZWAVE|INSTEON|RF|TCP]
              description=
              version=
              author=
              info-url=
              zone-count=#
              area-count=#
              gateway-commands=
              zone-commands=
              area-commands=
            }
          * security.zonelist
            {
              zone-list=id,id,...,id
            }
          * security.zoneinfo
            {
              zone=id
              zone-type=perimeter|interior|24hour
              alarm-type=burglary|fire|flood|gas|other
              area-count=id
              area-list=id,id,...,id
              [room=]
              [floor=]
              [comment=]
            }
          * security.areainfo
            {
              area=id
              zone-count=id
              zone-list=id,id,...,id
              [room=]
              [floor=]
              [comment=]
            }
          * security.gatestat
            {
              ac-fail=true|false
              low-battery=true|false
              status=armed|disarmed|alarm
            }
          * security.zonestat
            {
              zone=id
              armed=true|false
              alert=true|false
              alarm=true|false 
              state=isolated|bypassed|enabled 
              [alarm-type=burglary|fire|flood|gas|silent|duress|other]
              [tamper=true|false]
              [low-battery=true|false]
              [dark=true|false]
            }
          * security.areastat
            {
              area=id
              armed=true|false
              alert=true|false
              alarm=true|false
              [alarm-type=burglary|fire|flood|gas|silent|duress]
              [tamper=true|false]
              [low-battery=true|false]
              [dark=true|false]
            }
          * 
          * Trigger
          * security.gateway
            {
              event=ready|changed|ac-fail|ac-restored|low-battery|battery-ok|armed|disarmed|alarm|error
              [area-list=id,id, ,id]
              [zone-list=id,id, ,id]
              [zone=id]
              [area=id]
              [alarm-type=burglary|fire|flood|gas|silent|duress|other]
              [user=id]
              [error=]
            }
          * security.zone
            {
              event=alarm|alert|tamper|isolated|bypassed|normal 
              zone=id 
              [low-battery=true|false] 
              [alarm-type=burglary|fire|flood|gas|silent|duress|other] 
            }
          * security.area
            {
              event=alarm|alert|tamper|normal 
              area=id 
              [low-battery=true|false] 
              [alarm-type=burglary|fire|flood|gas|silent|duress|other] 
            }            
          *
          **/
        $schema = $_message->messageSchemeIdentifier();
        // checking if correct message (see list above)
        if ($type == xPLMessage::xplstat && ($schema != "security.gateinfo" && $schema != "security.zonelist" && $schema != "security.zoneinfo" && $schema != "security.areainfo" && $schema != "security.gatestat" && $schema != "security.zonestat" && $schema != "security.areastat")) {
          return false;
        }        
        if ($type == xPLMessage::xpltrig && ($schema != "security.gateway" && $schema != "security.zone" && $schema != "security.area")) {
          return false;
        }
        
        if (XPL_DEBUGLEVEL>=2) log::add('xpl', 'debug', "Handling schema '".$schema."'...");
        
        $list_events = array();
        $source = $_message->source();
        $zone = $_message->bodyItem('zone');
        $area = $_message->bodyItem('area');
        
        $zoneName = $_message->bodyItem('zone-name');
        $zoneList = $_message->bodyItem('zone-list');
        $userName = $_message->bodyItem('user-name');
        $user = $_message->bodyItem('user'); 
        $eventName = $_message->bodyItem('event');
        $event = $_message->bodyItem('event-id');
 
        $xPL = xPL::byLogicalId($source, 'xpl');
        if (is_object($xPL)) {
          $list_cmd = $xPL->getCmd();
          foreach ($list_cmd as $cmd) {          
            $schema_compare = $cmd->getConfiguration('xPLschema');
            $zone_compare = $cmd->getItem('zone');
            $area_compare = $cmd->getItem('area');
            $type_compare = $cmd->getConfiguration('xPLtypeCmd'); 
            $request = $cmd->getItem('request');
            if ($schema === $schema_compare && 
                ($type == xPLMessage::xpltrig && $type_compare == 'XPL-TRIG' || $type == xPLMessage::xplstat && $type_compare == 'XPL-STAT') &&
                ($zone === $zone_compare || $area === $area_compare || $schema == "security.gateinfo" || $schema == "security.zonelist" || $schema == "security.gatestat" || $schema == "security.gateway")
               ) {          
              $value=$_message->bodyItem($request);
              if ($value != '') {
                if (XPL_DEBUGLEVEL>=3) log::add('xpl', 'debug', "Found '" . $schema . "' command with request='" . $request . "': id='" . $cmd->getId() ."'; new value='" . $value ."'");
                $event_info = array();
                $event_info['cmd_id'] = $cmd->getId();
                $event_info['value'] = $value;
                $list_events[] = $event_info;
              }
            }
          }
        }
        return $list_events;
    }

}

?>
