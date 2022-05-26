<?php
    require_once "../../config/connection.php";
    require_once "../functions.php";


    if(!($user && $user->id_uloga == 1)){
        header("Location: ../../index.php?page=pocetna");
        die();
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=ukupna_statistika.xls");

    
    $statistic = get_statistic("all");

    $string_excel = '
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Stranica</th>
                    <th>Posecenost</th>
                </tr>
            </thead>
        <tbody>';

        $i = 1;
        foreach($statistic["page_percentages"] as $key=>$value){
            
            $string_excel .= '
            <tr>
                <th>'.$i.'</th>
                <td>'.$key.'</td>
                <td>'.$value.' %</td>
            </tr>';$i++;
        }
        $string_excel .= '
            </tbody>
        </table>';

    echo $string_excel;