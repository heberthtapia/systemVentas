<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of numeroAleatorios
 *
 * @author OmarGuevara
 */
class numeroAleatorios {

    //put your code here
//    private $numero;
    private $numero;

//    private $arrNum[]= new ;



    public function numeroAleatorio() {
        $array[0] = 11;
        do {
            $numero = rand(0, 9);
            if (in_array($numero, $array)) {
//                echo 'Numero repetido<br>';
            } else {
                $array[] = $numero;
            }
        } while (count($array) <= 10);
        return $array;
    }

}
