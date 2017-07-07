<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

include_file('core', 'xpl', 'config', 'xpl');
include_file('core', 'xpl', 'class', 'xpl');

sendVarToJS('eqType', 'xpl');
$eqLogics = eqLogic::byType('xpl');
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un équipement}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '" style="' . $opacity . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes équipements xPL}}
        </legend>
        <?php
if (count($eqLogics) == 0) {
	echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez pas encore d'équipement xPL, cliquez sur Ajouter un équipement pour commencer}}</span></center>";
} else {
	?>
            <div class="eqLogicThumbnailContainer">
                <?php
foreach ($eqLogics as $eqLogic) {
		$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
		echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
		echo "<center>";
		echo '<img src="plugins/xpl/doc/images/xpl_icon.png" height="105" width="95" />';
		echo "</center>";
		echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
		echo '</div>';
	}
	?>
            </div>
            <?php }
?>
        </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
            <?php
$cron = cron::byId(config::byKey('xPLDeamonCronId', 'xPL'));
if (is_object($cron) && $cron->getState() != 'run') {
	echo '<div class="alert alert-danger" >{{Attention le démon xPL n\'est pas en marche. Vérifiez pourquoi. </div>';
}
?>
            <form class="form-horizontal">
                <fieldset>
                    <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}<i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Nom de l'équipement xPL}}</label>
                        <div class="col-sm-3">
                            <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                            <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement xPL}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Nom logique de l'équipement xPL}}</label>
                        <div class="col-sm-3">
                            <input type="text" class="eqLogicAttr form-control" data-l1key="logicalId"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                        <div class="col-sm-3">
                            <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                <?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Catégorie}}</label>
                        <div class="col-sm-8">
                            <?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"></label>
                        <div class="col-sm-9">
                           <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
			   <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-sm-4 control-label">{{Délai maximum autorisé entre 2 messages (min)}}</label>
                        <div class="col-sm-4">
                            <input class="eqLogicAttr form-control" data-l1key="timeout" />
                        </div>
                    </div>
                </fieldset>
            </form>

            <legend>{{Commandes}}</legend>
            <a class="btn btn-success btn-sm cmdAction" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter une commande xPL}}</a><br/><br/>
            <div class="alert alert-info">
                {{Sous type : <br/>
                - Slider : mettre #slider# pour récupérer la valeur<br/>
                - Color : mettre #color# pour récupérer la valeur<br/>
                - Message : mettre #title# et #message#}}
            </div>
            <table id="table_cmd" class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="width: 150px;">{{Nom}}</th>
                        <th style="width: 110px;">{{Type}}</th>
                        <th style="width: 150px;">{{Schéma}}</th>
                        <th>{{Body}}</th>
                        <th style="width: 200px;">{{Paramètres}}</th>
                        <th style="width: 100px;">{{Unité}}</th>
                        <th style="width: 100px;"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <form class="form-horizontal">
                <fieldset>
                    <div class="form-actions">
                        <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                        <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>

    <?php include_file('desktop', 'xpl', 'js', 'xpl');?>
    <?php include_file('core', 'plugin.template', 'js');?>
