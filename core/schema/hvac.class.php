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

class hvac {

    public static function parserMessage($_message) {
        $type = $_message->getIdentifier(); // 1,2,3
        if ($type == xPLMessage::xplcmnd) {
          return false;
        }
        /* Status
         * hvac.gateinfo
            {
            protocol=[X10|UPB|CBUS|ZWAVE|INSTEON]
            description=
            version=
            author=
            info-url=
            zone-count=#
            }
         * hvac.zonelist
            {
            zone-count=#
            zone-list=id,id,...,id
            [zone-list=]
            }
         * hvac.zoneinfo
            {
            zone=id
            command-list=
            hvac-mode-list=
            fan-mode-list=
            timer-mode-list=
            setpoint-list=
            hvac-state-list=
            fan-state-list=
            [boost-duration=true|false]
            [room=room name]
            [floor=floor name]
            [comment=comments]
            }
         * hvac.runtime
            {
            zone=id
            state=
            time=#
            }
         * hvac.fantime
            {
            zone=id
            state=
            time=#
            }
         *
         * Trigger
         * hvac.gateway
            {
            event=ready|changed
            }
         * hvac.zone
            {
            zone=id
            hvac-mode=
            fan-mode=
            timer-mode=
            hvac-state=
            fan-state=
            temperature=#
            }
         * hvac.setpoint
            {
            zone=id
            setpoint=name
            temperature=#
            }
         * hvac.timer
            {
            zone=id
            timer=[SuMoTuWeThFrSa,hh:mm-hh:mm,hh:mm-hh:mm,...etc]
            [timer=]
            }
         *
         **/
        $schema = $_message->messageSchemeIdentifier();
        // checking if correct message (see list above)
        if ($type == xPLMessage::xplstat && ($schema != "hvac.gateinfo" && $schema != "hvac.zonelist" && $schema != "hvac.zoneinfo" && $schema != "hvac.runtime" && $schema != "hvac.fantime")) {
          return false;
        }
        if ($type == xPLMessage::xpltrig && ($schema != "hvac.gateway" && $schema != "hvac.zone" && $schema != "hvac.zoneinfo" && $schema != "hvac.setpoint" && $schema != "hvac.timer")) {
          return false;
        }

        if (XPL_DEBUGLEVEL>=2) log::add('xpl', 'debug', "Handling schema '".$schema."'...");
        
        $list_event = array();
        $source = $_message->source();
        $zone = $_message->bodyItem('zone');
        
        $xPL = xPL::byLogicalId($source, 'xpl');
        if (is_object($xPL)) {
          $list_cmd = $xPL->getCmd();
          foreach ($list_cmd as $cmd) {
            $schema_compare = $cmd->getConfiguration('xPLschema');
            $zone_compare = $cmd->getItem('zone');
            $type_compare = $cmd->getConfiguration('xPLtypeCmd'); 
            $request = $cmd->getItem('request');
            if ($schema === $schema_compare && 
                ($type == xPLMessage::xpltrig && $type_compare == 'XPL-TRIG' || $type == xPLMessage::xplstat && $type_compare == 'XPL-STAT') &&
                ($zone === $zone_compare || $schema == "hvac.gateinfo" || $schema == "hvac.zonelist"  || $schema == "hvac.gateway")
               ) {
              $value=$_message->bodyItem($request);
              if ($value != '') {
                if (XPL_DEBUGLEVEL>=3) log::add('xpl', 'debug', "Found '" . $schema . "' command with request='" . $request . "': id='" . $cmd->getId() ."'; new value='" . $value ."'");
                $event_info = array();
                $event_info['cmd_id'] = $cmd->getId();
                $event_info['value'] = $value;
                $list_event[] = $event_info;
              }
            }
          }
        }
        return $list_event;
    }

}

?>
