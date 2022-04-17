
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

$(function () {
    $('#table_cmd tbody').delegate('tr .cmdAttr[data-l1key=configuration][data-l2key=xPLtypeCmd]', 'change', function () {
        changexPLTypeCmd($(this));
    });

    $('#table_cmd tbody').delegate('tr .cmdAttr[data-l1key=configuration][data-l2key=xPLschema]', 'change', function () {
        changexPLTypeCmd($(this));
    });

    $("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
});


function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }

    var selxPlschema = '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="xPLschema" style="width : 150px;">';
    selxPlschema += '<option value="control.basic">Control.basic</option>';
    selxPlschema += '<option value="sensor.basic">Sensor.basic</option>';
    selxPlschema += '<option value="homeeasy.basic">homeeasy.basic</option>';
    selxPlschema += '<option value="remote.basic">Remote.basic</option>';
    selxPlschema += '<option value="x10.basic">x10.basic</option>';
    selxPlschema += '<option value="ac.basic">ac.basic</option>';
    selxPlschema += '<option value="osd.basic">osd.basic</option>';
    selxPlschema += '<option value="x10.security">x10.security</option>';
    selxPlschema += '<option value="lighting.basic">lighting.basic</option>';
/*    selxPlschema += '<option value="lighting.request">lighting.basic</option>';
    selxPlschema += '<option value="lighting.gateinfo">lighting.basic</option>';
    selxPlschema += '<option value="lighting.netlist">lighting.basic</option>';
    selxPlschema += '<option value="lighting.netinfo">lighting.basic</option>';
    selxPlschema += '<option value="lighting.devlist">lighting.basic</option>';
    selxPlschema += '<option value="lighting.devinfo">lighting.basic</option>';
    selxPlschema += '<option value="lighting.scnlist">lighting.scnlist</option>';
    selxPlschema += '<option value="lighting.scninfo">lighting.scninfo</option>';
    selxPlschema += '<option value="lighting.gateway">lighting.gateway</option>';*/
    selxPlschema += '<option value="lighting.device">lighting.device</option>';
//    selxPlschema += '<option value="lighting.scene">lighting.scene</option>';
    selxPlschema += '<option value="teleinfo.basic">teleinfo.basic</option>';
    //adding security messages
    selxPlschema += '<option value="security.basic">security.basic</option>';
    selxPlschema += '<option value="security.request">security.request</option>';
    selxPlschema += '<option value="security.gateinfo">security.gateinfo</option>';
    selxPlschema += '<option value="security.zonelist">security.zonelist</option>';
    selxPlschema += '<option value="security.arealist">security.arealist</option>';    
    selxPlschema += '<option value="security.zoneinfo">security.zoneinfo</option>';
    selxPlschema += '<option value="security.areainfo">security.areainfo</option>';
    selxPlschema += '<option value="security.gatestat">security.gatestat</option>';
    selxPlschema += '<option value="security.zonestat">security.zonestat</option>';
    selxPlschema += '<option value="security.areastat">security.areastat</option>';
    selxPlschema += '<option value="security.gateway">security.gateway</option>';
    selxPlschema += '<option value="security.zone">security.zone</option>';
    selxPlschema += '<option value="security.area">security.area</option>';
    //adding hvac messages
    selxPlschema += '<option value="hvac.basic">hvac.basic</option>';
    selxPlschema += '<option value="hvac.request">hvac.request</option>';
    selxPlschema += '<option value="hvac.gateinfo">hvac.gateinfo</option>';
    selxPlschema += '<option value="hvac.zonelist">hvac.zonelist</option>';
    selxPlschema += '<option value="hvac.zoneinfo">hvac.zoneinfo</option>';
    selxPlschema += '<option value="hvac.runtime">hvac.runtime</option>';
    selxPlschema += '<option value="hvac.fantime">hvac.fantime</option>';
    selxPlschema += '<option value="hvac.gateway">hvac.gateway</option>';
    selxPlschema += '<option value="hvac.zone">hvac.zone</option>';
    selxPlschema += '<option value="hvac.setpoint">hvac.setpoint</option>';
    selxPlschema += '<option value="hvac.timer">hvac.timer</option>';
    
    selxPlschema += '</select>';

    var typeXmdxPL = '<select class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="xPLtypeCmd" style="width : 150px;margin-top : 5px;">';
    typeXmdxPL += '<option value="XPL-CMND">XPL-CMND</option>';
    typeXmdxPL += '<option value="XPL-STAT">XPL-STAT</option>';
    typeXmdxPL += '<option value="XPL-TRIG">XPL-TRIG</option>';
    typeXmdxPL += '</select>';

    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="id" style="display : none;">';
    tr += '<a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fas fa-flag"></i> Icone</a>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" value="' + init(_cmd.name) + '"></td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    tr +=    selxPlschema + typeXmdxPL;
    tr += '</td>';
    tr += '<td class="xPLbody">';
    tr += '<textarea style="height : 100px;" class="cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="xPLbody"></textarea>';
    tr += '</td>';
    tr += '<td>';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isVisible" checked/>{{Afficher}}</label></span> ';
    tr += '<span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr checkbox-inline" data-l1key="isHistorized" checked/>{{Historiser}}</label></span> ';
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width : 40%;display : inline-block;"> ';
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width : 40%;display : inline-block;">';
    tr += '</td>';
    tr += '<td><input class="cmdAttr input-sm form-control" data-l1key="unite" style="width : 100px;"></td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
    tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i></td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}

function changexPLTypeCmd(_el, _xPLbody) {
    var tr = _el.closest('tr');
    tr.find('.cmdAttr[data-l1key=isHistorized]').show();
    tr.find('.cmdAttr[data-l1key=cache][data-l2key=enable]').parent().show();
    tr.find('.cmdAttr[data-l1key=eventOnly]').parent().show();
    switch (_el.value()) {
        case 'XPL-CMND' :
            tr.find('.test_xpl').show();
            tr.find('.eventOnly').parent().hide();
            break;
        case 'XPL-STAT' :
            tr.find('.test_xpl').hide();
            break;
        case 'XPL-TRIG' :
            tr.find('.eventOnly').prop('checked', true);
            tr.find('.test_xpl').hide();
            break;
    }
    updatexPLbody(tr.find('.cmdAttr[data-l1key=configuration][data-l2key=xPLschema]'), _xPLbody);
}

function updatexPLbody(_el, _xPLbody) {
    if (!isset(_xPLbody)) {
        var xPLschema = _el.value();
        var xPltypeCmd = _el.parent().find('.cmdAttr[data-l1key=configuration][data-l2key=xPLtypeCmd]').value();
        var tr = _el.closest('tr');
        tr.find('.cmdAttr[data-l1key=configuration][data-l2key=xPLbody]').value(getxPLbody(xPLschema, xPltypeCmd));
    }
}

function getxPLbody(_xPLschema, _xPltypeCmd) {
    var body = '';
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // methode de transmission des données au fichier php
        url: "plugins/xpl/core/ajax/xpl.ajax.php", // url du fichier php
        data: {
            action: "getxPLbody",
            xPLschema: _xPLschema,
            xPLtypeCmd: _xPltypeCmd
        },
        dataType: 'json',
        async: false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            body = $.trim(data.result);
        }
    });
    return body;
}
