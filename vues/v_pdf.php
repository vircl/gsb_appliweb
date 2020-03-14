<?php
$html
    =   '<p style="text-align:center">'
    .   '<img src="assets/images/logo.png" 
                  alt="Laboratoires Galaxy Swiss Bourdin" 
                  width="100"/>'
    .   '</p>'
    .   '<h1 style="text-align:center">Remboursement de frais engagés</h1>'
    .   '<p style="height:100px"></p>'
    .   '<p>'
    .       '<b>Visiteur : </b>' . $identiteVisiteur . '<br/>'
    .       '<b>Mois : </b>' . $nomMois . ' ' . $numAnnee
    .   '</p>'
    .   '<p style="height:100px"></p>'
    .   '<h2>Elements forfaitisés</h2>'
    .   '<table border="1" style="text-align:left;">'
    .       '<thead>'
    .           '<tr style="background-color:#0088cc;text-align:center;">'
    .               '<th style="width:40%"><b>Frais forfaitaires</b></th>'
    .               '<th style="width:20%"><b>Quantité</b></th>'
    .               '<th style="width:20%"><b>Montant unitaire</b></th>'
    .               '<th style="width:20%"><b>Total</b></th>'
    .           '</tr>'
    .       '</thead>'
    .       '<tbody>';
foreach ($lesFraisForfait as $unFrais) {
    $libelle = $unFrais['libelle'];
    $montant = $unFrais['montant'];
    $total   = $unFrais['total'];
    if ($unFrais['idfrais'] == 'KM') {
        $montant = $tauxKM;
        $total = $unFrais['quantite'] * $montant;
        $vehicule = $infosVisiteur['typeVehicule'];
        $libelle .= ($vehicule ? '(' . $vehicule . ')'
            : ' (Véhicule non renseigné)');
    }
    $html
    .=          '<tr>'
    .               '<td style="width:40%;">' . $libelle .'</td>'
    .               '<td style="width:20%; text-align:center">'
    .                   $unFrais['quantite']
    .               '</td>'
    .               '<td style="width:20%; text-align:center">'
    .                       $montant . ' € '
    .               '</td>'
    .               '<td style="width:20%; text-align:center">'
    .                   $total . ' €'
    .               '</td>'
    .           '</tr>';
}
$html
    .=      '</tbody>'
    .   '</table>';
$html
    .=  '<p style="height:100px"></p>'
    .  '<h2>Autres frais</h2>'
    .   '<table border="1" style="text-align:center; vertical-align: middle;">'
    .       '<thead>'
    .           '<tr style="background-color:#0088cc;text-align:center;">'
    .               '<th style="width:25%"><b>Date</b></th>'
    .               '<th style="width:50%"><b>Libellé</b></th>'
    .               '<th style="width:25%"><b>Montant</b></th>'
    .           '</tr>'
    .       '</thead>'
    .       '<tbody>';
foreach ($lesFraisHorsForfait as $unFrais) {
    $html
        .=      '<tr>'
        .           '<td style="width:25%">' . $unFrais['date'] .    '</td>'
        .           '<td style="width:50%">' . $unFrais['libelle'] . '</td>'
        .           '<td style="width:25%">' . $unFrais['montant'] . ' €</td>'
        .       '</tr>';
}
$html
    .=      '</tbody>'
    .   '</table>';
$html
    .=  '<p style="height:50px;"></p>'
    .   '<p style="text-align:right">'
    .       'Total ' . $numMois . '/' .$numAnnee . ' : '
    .       '<b>' . $montantValide . ' € </b>'
    .   '</p>';

$html
    .= '<p style="height:100px"></p>'
    .  '<p style="text-align:right;">'
    . 'Fait à Paris le '
    . dateAnglaisVersFrancais($infosFiche['dateModif'])
    . '<br/>'
    . 'Vu l\'agent comptable'
    .  '</p>';


