<?php

/**
 * Description of ModuloBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../../DAL/ambulancia/KMAmbulanciaDAL.php';
require_once dirname(__FILE__) . '/../../Security.php';

class KMAmbulanciaBLL {
    
    public function GetAll() {
        $db = new KMAmbulanciaDAL();
        return $db->getAll();
    }
    
    public function GetExcelKM($From, $To) {
        $db = new KMAmbulanciaDAL();
        $List = $db->GetHistoriaKM($From, $To);
//        print_r($List);
        $he = new ExcelBLL();
        $he->BuildExcel_KiloMetraje($List);
    }
    
    public function CreateKM($KM) {
        $db = new KMAmbulanciaDAL();
        $db->CreateKM($this->MAPToArray($KM));
        return $KM;
    }
    
    public function CreateKM2($KM) {
        $db = new KMAmbulanciaDAL();
        $db->CreateKM($KM);
        return $KM;
    }
    
    public function GetLastKMByHojaVidaId($HojaVidaId) {
        $db = new KMAmbulanciaDAL();
        return $db->GetLastKMByHojaVidaId($HojaVidaId);
    }
    
    public function DeleteKM($KMId) {
        $db = new KMAmbulanciaDAL();
        return $db->DeleteKM($KMId);
    }
    public function UpdateKM($list, $id) {
        $db = new KMAmbulanciaDAL();
        return $db->UpdateKM($this->MAPToArray2($list), $id);
    }

    public function MAPToArray($list) {
        $list2 = Array();
            array_push($list2, Array(
                'HojaVidaId' => $list->HojaVidaId,
                'KM' => $list->Km,
                'KmAnterior' => $list->LastKm,
                'CreatedBy' => $list->CreatedBy
            ));
        return $list2;
    }
    public function MAPToArray2($list) {
        $list2 = Array();
        array_push($list2, Array(
            'Km' => $list->Km,
            'ModifiedBy' => $list->ModifiedBy,
            'ModifiedAt' => date("Y-m-d"),
        ));
        return $list2;
    }

}
