<?php

require_once dirname(__FILE__) . '/../../DAL/Error/ErrorDAL.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ErrorBLL
 *
 * @author DESARROLLO2
 */
class ErrorBLL {

    function LogDeErrores($consulta) {
        $eh = new ErrorDAL();
        $eh->LogDeErrores($consulta);
    }

}
