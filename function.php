<?php

function genereAccordion($data) {
    $html = '';

    // Ici c'est le building, ça va créer une card pour chaque building
    foreach ($data['children'] as $building) {
        if ($building['type'] !== 'geographicBuilding') {
            
            continue;
        }
        $html .= '<div class="card mt-4">';
        $html .= '<div class="card-header" id="building' . $building['dynamicId'] . '">';
        $html .= '<h5 class="mb-0">';
        $html .= '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseBuilding' . $building['dynamicId'] . '" aria-expanded="true" aria-controls="collapseBuilding' . $building['dynamicId'] . '">';
        $html .= 'BATIMENT ' . $building['name'];
        $html .= '</button>';
        $html .= '</h5>';
        $html .= '</div>'; 

        $html .= '<div id="collapseBuilding' . $building['dynamicId'] . '" class="collapse" aria-labelledby="building' . $building['dynamicId'] . '" data-parent="#accordion">';
        $html .= '<div class="card-body">'; 

        

        // Les étages
        foreach ($building['children'] as $etages) {
            if ($etages['type'] !== 'geographicFloor') {
                
                continue;
            }

            
            $salleOcupper = 0;
            $etagePourcentage = 0;

            $html .= '<div class="card mt-2">';
            $html .= '<div class="card-header" id="floor' . $etages['dynamicId'] . '">';
            $html .= '<h5 class="mb-0">';
            $html .= '<button class="btn btn-link" data-toggle="collapse" data-target="#collapseFloor' . $etages['dynamicId'] . '" aria-expanded="true" aria-controls="collapseFloor' . $etages['dynamicId'] . '">';
            $html .= '' . $etages['name'];
            $html .= '</button>';

            
            
            $totalEtages = count($etages['children']);
            if ($totalEtages > 0) {
                // Compte les salles occupées
                foreach ($etages['children'] as $salle) {
                    if ($salle['type'] === 'geographicRoom' && room_occuper($salle['dynamicId']) == 'TRUE') {
                        $salleOcupper++;
                    }
                }


                // Calcule le %
                $etagePourcentage = ($salleOcupper / $totalEtages) * 100;
            }

            $html .= '<span class="float-right">' . round($etagePourcentage, 2) . '% occupation  </span>';

            $html .= '</h5>';
            $html .= '</div>';

            $html .= '<div id="collapseFloor' . $etages['dynamicId'] . '" class="collapse" aria-labelledby="floor' . $etages['dynamicId'] . '" data-parent="#collapseBuilding' . $building['dynamicId'] . '">';
            $html .= '<div class="card-body">';

            // Les salles
            foreach ($etages['children'] as $salle) {
                if ($salle['type'] !== 'geographicRoom') {
                    
                    continue;
                }

                
                $occupationValue = room_occuper($salle['dynamicId']);

                $html .= '<ul class="list-group">';
                $html .= '<li class="list-group-item">' . $salle['name'] . ' - ' . $occupationValue . '</li>';
                $html .= '</ul>';
            }

            $html .= '</div>'; 
            $html .= '</div>'; 

            $html .= '</div>';
        }

        $html .= '</div>'; 
        $html .= '</div>'; 
        $html .= '</div>'; 
    }

    return $html;
}





function room_occuper($room) {
    // L'url pour check les room
    $apiUrl = 'https://api-developers.spinalcom.com/api/v1/room/' . $room . '/control_endpoint_list';

    // Récupération des données
    $jsonData = file_get_contents($apiUrl);

    $data = json_decode($jsonData, true);



    if (!empty($data) && isset($data[0]['endpoints'])) {
        foreach ($data[0]['endpoints'] as $endpoint) {
            if ($endpoint['name'] === 'Occupation') {

                if($endpoint['currentValue'] == 1){
                    $endpoint['currentValue'] = "TRUE";
                }
                                                                            // Ici on retourne si l'occupation elle est true, false ou undefined
                else{
                    $endpoint['currentValue'] = "FALSE";
                }
                return $endpoint['currentValue'];
            }
        }
    }


    return 'undefined';
}



?>