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

require_once dirname(__FILE__) . '/xpl.schema.php';

define('DEFAULT_HBEAT_INTERVAL', 5);
define('XPL_PORT', 3865);
define('XPL_VENDOR', 'xpl');
define('XPL_DEVICE', 'jeedom');
define('XPL_INSTANCE', gethostname());
define('XPL_IP', gethostbyname(gethostname()));
define('XPL_DEBUGLEVEL', 1); // debug Level
define('XPL_MAX_RETRY_CONNEXION_TO_HUB', '300');


global $XPL_BODY;
$XPL_BODY = array(
    'control.basic' => array(
        'XPL-CMND' => "type=<sensor type>
                       current=<value>
                       [data1=<additional data>]
                       [name=]",
    ),
    'sensor.basic' => array(
        'XPL-CMND' => "request=current
                       device=<sensor name>
                       type=<sensor type>
                       [name=]",
        'XPL-TRIG' => "device=<sensor name>
                       type=<sensor type>
                       current=<current value>
                       [lowest=<lowest recorded value>]
                       [highest=<highest recorded value>]
                       [units=<optional specifier for current units]>",
    ),
    'homeeasy.basic' => array(
        'XPL-CMND' => "address=<sensor address>
                       unit=<sensor unit>
                       command=<command value>",
        'XPL-TRIG' => "address=<sensor address>
                       unit=<sensor unit>
                       command=<command value>",
    ),
    'x10.basic' => array(
        'XPL-CMND' => "device=<sensor address>
                       command=<command value>",
        'XPL-TRIG' => "device=<sensor address>
                       command=<command value>",
    ),
    'x10.security' => array(
        'XPL-TRIG' => "command=<command value>
                       device=<module address>
                       type=<module type>
                       [temper=]
                       [low-battery=]
                       [delay=",
    ),
     'ac.basic' => array(
        'XPL-CMND' => "address=<sensor address>
                       unit=<sensor unit>
                       command=<command value>
                       [level=<level>]",
					   
        'XPL-TRIG' => "address=<sensor address>
                       unit=<sensor unit>
                       command=<command value>
                       [level=<level>]",
    ),
    'osd.basic' => array(
        'XPL-CMND' => "command=clear|write|exclusive|release
                       text=<text to display. each line should be seperated by the \n seperator>
                       [row=<display row>]
                       [column=<display from column>]
                       [delay=<seconds to display>]",
    ),
    'remote.basic' => array(
        'XPL-TRIG' => "keys=<keys remote>
                       device=<device modele>
                       [zone=<command value>]",
        'XPL-CMND' => "keys=<keys remote>
                       device=<device modele>
                       [zone=<command value>]
		       [delay=<delay>]",
    ),
    'lighting.basic' => array(
        'XPL-CMND' => "command=[goto|activate|deactivate]
                       [network=ID]
                       [[device=ID]|[scene=ID]]
                       [channel=#]
                       [level=0-100]
                       [fade-rate=[default|<time>]",
    ),
/*    'lighting.request' => array(
        'XPL-CMND' => "request=[gateinfo|netlist|netinfo|devlist|devinfo|devstate|scnlist|scninfo]
                       [network=ID]
                       [[device=ID]|[scene=ID]][channel=#]",
    ),
    'lighting.gateinfo' => array(
        'XPL-STAT' => "[network=ID]
                       [[device=ID]|[scene=ID]][channel=#]",
    ),
    'lighting.netlist' => array(
        'XPL-STAT' => "",
    ),
    'lighting.netinfo' => array(
        'XPL-STAT' => "network=ID",
    ),
    'lighting.devlist' => array(
        'XPL-STAT' => "network=ID",
    ),
    'lighting.devinfo' => array(
        'XPL-STAT' => "network=ID
                       device=ID",
    ),
    'lighting.scnlist' => array(
        'XPL-STAT' => "network=ID",
    ),
    'lighting.scninfo' => array(
        'XPL-STAT' => "network=ID
                       scene=ID",
    ),
    'lighting.gateway' => array(
        'XPL-TRIG' => "",
    ),*/
    'lighting.device' => array(
        'XPL-TRIG' => "network=ID
                       device=ID
                       channel=#",
    ),    
/*    'lighting.scene' => array(
        'XPL-TRIG' => "network=ID
                       scene=ID",
    ),*/
    'teleinfo.basic' => array(
        'XPL-STAT' => "adco=<Adresse du compteur>
                       request= ** one of the item below **
                         optarif=<Option tarifaire>
                         isousc=<Intensité souscrite>
                         base=<Index option base>
                         iinst=<Intensité instantanée>
                         imax=<Intensité maximale appelée>
                         motdetat=<Mot d'état du compteur>
                         hchc=<Heures creuses>
                         hchp=<Heures pleines>
                         ejphn=<Heures normales>
                         ejphpm=<Heures de pointe>
                         bbrhcjb=<Heures creuses jours bleus>
                         bbrhpjb=<Heures pleines jours bleus>
                         bbrhcjw=<Heures creuses jours blancs>
                         bbrhpjw=<Heures pleines jours blancs>
                         bbrhcjr=<Heures creuses jours rouges>
                         bbrhpjr=<Heures pleines jours rouges>
                         pejp=<Préavis début EJP (30min)>
                         ptec=<Période tarifaire actuelle>
                         demain=<Couleur du lendemain>
                         adps=<Avertissement de dépassement>
                         papp=<Puissance apparente>
                         hhphc=<Horaire heure pleine/here creuse>
                         ppot=<Présence des potentiels>
                         iinst1=<Intensité instantanée phase 1>
                         iinst2=<Intensité instantanée phase 2>
                         iinst3=<Intensité instantanée phase 3>
                         imax1=<Intensité maximale phase 1>
                         imax2=<Intensité maximale phase 2>
                         imax3=<Intensité maximale phase 3>
                         pmax=<Puissance maximale triphasée>",
    ),
/*    'teleinfo.short' => array(
        'XPL-STAT' => "adir1=<Dépassement d'intensité sur la phase 1>
                       adir2=<Dépassement d'intensité sur la phase 2>
                       adir3=<Dépassement d'intensité sur la phase 3>
                       adco=<Adresse du compteur>
                       iinst1=<Intensité instantanée phase 1>
                       iinst2=<Intensité instantanée phase 2>
                       iinst3=<Intensité instantanée phase 3>
    ),*/
// Addition of security messages
    'security.gateinfo' => array(
        'XPL-STAT' => "request= ** one of the item below **
                        protocol=[X10|UPB|CBUS|ZWAVE|INSTEON]
                        description=
                        version=
                        author=
                        info-url=
                        zone-count=#",
    ),
    'security.zonelist' => array(
        'XPL-STAT' => "request= ** one of the item below **
                        zone-count=#
                        zone-list=id,id,...,id",
    ),
    'security.arealist' => array(
        'XPL-STAT' => "request= ** one of the item below **
                        area-count=#
                        area-list=id,id,...,id",
    ),
    'security.zoneinfo' => array(
        'XPL-STAT' => "zone=<id>
                       request= ** one of the item below **
                      ",
    ),
    'security.areainfo' => array(
        'XPL-STAT' => "area=<id>
                       request= ** one of the item below **
                      ",
    ),
    'security.gatestat' => array(
        'XPL-STAT' => "request= ** one of the item below **
                      ",
    ),    
    'security.zonestat' => array(
        'XPL-STAT' => "zone=<id>
                       request= ** one of the item below **
                      ",
    ),   
    'security.areastat' => array(
        'XPL-STAT' => "area=<id>
                       request= ** one of the item below **
                      ",
    ),    
    'security.gateway' => array(
        'XPL-TRIG' => "type= ** one of the item below **
                        event-id=<event #>
                        event=<event name>
                        zone=<zone #>
                        zone-name=<zone name>
                        zone-list=<zone list>
                        user=<user #>
                        user-name=<user name>",
    ),
    'security.zone' => array(
        'XPL-TRIG' => "zone=<id>
                       request= ** one of the item below **
                      ",
    ),   
    'security.area' => array(
        'XPL-TRIG' => "area=<id>
                       request= ** one of the item below **
                      ",
    ),    
    'security.basic' => array(
        'XPL-CMD' => "command=
                     ",
    ), 
    'security.request' => array(
        'XPL-CMD' => "command=
                     ",
    ),     
//addition of hvac messages
    'hvac.gateinfo' => array(
        'XPL-STAT' => "request= ** one of the item below **
                        protocol=[X10|UPB|CBUS|ZWAVE|INSTEON]
                        description=
                        version=
                        author=
                        info-url=
                        zone-count=#",
    ),
    'hvac.zonelist' => array(
        'XPL-STAT' => "request= ** one of the item below **
                        zone-count=#
                        zone-list=id,id,...,id",
    ),
    'hvac.zoneinfo' => array(
        'XPL-STAT' => "zone=<id>
                       request= ** one of the item below **
                        command-list=
                        hvac-mode-list=
                        fan-mode-list=
                        timer-mode-list=
                        setpoint-list=
                        hvac-state-list=
                        fan-state-list=
                        boost-duration=true|false
                        room=room name
                        floor=floor name
                        comment=comments",
    ),
    'hvac.runtime' => array(
        'XPL-STAT' => "zone=<id>
                       request= ** one of the item below **
                         state=
                         time=#",
    ),
    'hvac.fantime' => array(
        'XPL-STAT' => "zone=<id>
                       request= ** one of the item below **
                        state=
                        time=#",
    ),
    'hvac.gateway' => array(
        'XPL-TRIG' => "request= ** one of the item below **
                        event=ready|changed",
    ),
    'hvac.zone' => array(
        'XPL-TRIG' => "zone=<id>
                       request= ** one of the item below **
                        hvac-mode=
                        fan-mode=
                        timer-mode=
                        hvac-state=
                        fan-state=
                        temperature=#",
    ),
    'hvac.setpoint' => array(
        'XPL-TRIG' => "zone=<id>
                       request= ** one of the item below **
                        setpoint=name
                        temperature=#",
    ),
    'hvac.timer' => array(
        'XPL-TRIG' => "zone=<id>
                       request= ** one of the item below **
                        timer=[SuMoTuWeThFrSa,hh:mm-hh:mm,hh:mm-hh:mm,...etc]",
    ),
    'hvac.basic' => array(
        'XPL-CMD' => "command=hvac-mode|fan-mode|timer-mode|setpoint|timer|reset-runtime|reset-fantime
                      zone=id
                      [setpoint=name]
                      [mode=]
                      [state=]
                      [temperature=#]
                      [duration=#]",
    ),
    'hvac.request' => array(
        'XPL-CMD' => "request=gateinfo|zonelist|zoneinfo|zone|setpoint|timer|runtime|fantime
                      zone=id
                      [setpoint=]
                      [state=]",
    ),
    


);
?>
