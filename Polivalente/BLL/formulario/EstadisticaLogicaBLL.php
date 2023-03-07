<?php
/**
 * Description of EstadisticaLogicaBLL
 *
 * @author DESARROLLO2
 */

require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../configuracion/ServicioBLL.php';

class EstadisticaLogicaBLL {
    protected function GenerateEstadistica($lst, $UsuarioId) {
        return Array(
            "TipoReporteSede" => $this->TipoReportesBySede($lst),
            "TipoReporteServicio" => $this->TipoReportesByServicios($lst,$UsuarioId),
            "EstadoFinalSede" => $this->EstadoFinalBySede($lst),
//            "EstadoFinalServicio" => $this->EstadoFinalByServicio($lst),
            'FallaDetectadaSede' => $this->FallaDetectadaBySede($lst),
//            'FallaDetectadaServicio' => $this->FallaDetectadaByServicio($lst)
        );
    }

    private function FallaDetectadaByServicio($lst) {
        $list = Array();
        $FallasDetectadas = ["MAL USO", "ACCESORIOS", "DESGASTE", "SIN FALLA", "OTRO"];
        $Helper = new ServicioBLL();
        $Servicios = json_decode($Helper->GetAll());
        foreach ($Servicios as $s) {
            $MalUso = 0;
            $Accesorio = 0;
            $Desgaste = 0;
            $SinFalla = 0;
            $Otro = 0;
            $TMalUso = 0;
            $TAccesorio = 0;
            $TDesgaste = 0;
            $TSinFalla = 0;
            $TOtro = 0;
            foreach ($lst as $value) {
                foreach ($FallasDetectadas as $falla) {
                    $fd = $value->FallaDetectada != NULL ? json_decode($value->FallaDetectada) : [];
                    foreach ($fd as $f) {
                        if ($falla == $f && $value->ServicioId == $s->ServicioId) {
                            if ($falla == "MAL USO") {
                                $MalUso++;
                                $TMalUso += $value->TotalRepuesto;
                            } else if ($falla == "ACCESORIOS") {
                                $Accesorio++;
                                $TAccesorio += $value->TotalRepuesto;
                            } else if ($falla == "DESGASTE") {
                                $Desgaste++;
                                $TDesgaste += $value->TotalRepuesto;
                            } else if ($falla == "SIN FALLA") {
                                $SinFalla++;
                                $TSinFalla += $value->TotalRepuesto;
                            } else if ($falla == "OTRO") {
                                $Otro++;
                                $TOtro += $value->TotalRepuesto;
                            }
                        }
                    }
                }
            }
            array_push($list, Array(
                "Sede" => $s->Nombre,
                'MalUso' => $MalUso,
                'Accesorio' => $Accesorio,
                'Desgaste' => $Desgaste,
                'SinFalla' => $SinFalla,
                'Otro' => $Otro,
                'TMalUso' => $TMalUso,
                'TAccesorio' => $TAccesorio,
                'TDesgaste' => $TDesgaste,
                'TSinFalla' => $TSinFalla,
                'TOtro' => $TOtro,
                'TotalFalla' => $MalUso + $Accesorio + $Desgaste + $SinFalla + $Otro,
                'TotalMantenimientos' => $TMalUso + $TAccesorio + $TDesgaste + $TSinFalla + $TOtro,
            ));
        }
        return $list;
    }

    private function FallaDetectadaBySede($lst) {
        $list = Array();
        $FallasDetectadas = ["MAL USO", "ACCESORIOS", "DESGASTE", "SIN FALLA", "OTRO"];
        $Helper = new SedeBLL();
        $Sedes = json_decode($Helper->GetAll());
        foreach ($Sedes as $s) {
            $MalUso = 0;
            $Accesorio = 0;
            $Desgaste = 0;
            $SinFalla = 0;
            $Otro = 0;
            $TMalUso = 0;
            $TAccesorio = 0;
            $TDesgaste = 0;
            $TSinFalla = 0;
            $TOtro = 0;
            foreach ($lst as $value) {
                foreach ($FallasDetectadas as $falla) {
                    $fd = $value->FallaDetectada != NULL ? json_decode($value->FallaDetectada) : [];
                    foreach ($fd as $f) {
                        if ($falla == $f && $value->SedeId == $s->SedeId) {
                            if ($falla == "MAL USO") {
                                $MalUso++;
                                $TMalUso += $value->TotalRepuesto;
                            } else if ($falla == "ACCESORIOS") {
                                $Accesorio++;
                                $TAccesorio += $value->TotalRepuesto;
                            } else if ($falla == "DESGASTE") {
                                $Desgaste++;
                                $TDesgaste += $value->TotalRepuesto;
                            } else if ($falla == "SIN FALLA") {
                                $SinFalla++;
                                $TSinFalla += $value->TotalRepuesto;
                            } else if ($falla == "OTRO") {
                                $Otro++;
                                $TOtro += $value->TotalRepuesto;
                            }
                        }
                    }
                }
            }
            array_push($list, Array(
                "Sede" => $s->Nombre,
                'MalUso' => $MalUso,
                'Accesorio' => $Accesorio,
                'Desgaste' => $Desgaste,
                'SinFalla' => $SinFalla,
                'Otro' => $Otro,
                'TMalUso' => $TMalUso,
                'TAccesorio' => $TAccesorio,
                'TDesgaste' => $TDesgaste,
                'TSinFalla' => $TSinFalla,
                'TOtro' => $TOtro,
                'TotalFalla' => $MalUso + $Accesorio + $Desgaste + $SinFalla + $Otro,
                'TotalMantenimientos' => $TMalUso + $TAccesorio + $TDesgaste + $TSinFalla + $TOtro,
            ));
        }
        return $list;
    }
    
    private function EstadoFinalByServicio($lst) {
        $list = Array();
        $EstadoFinal = ["EQUIPO FUNCIONANDO CORRECTAMENTE", "EQUIPO EN ESPERA DE REPUESTOS", "EQUIPO FUERA DE SERVICIO", 
            "EQUIPO RETIRADO DEL SERVICIO", "RECOMENDACION DE BAJA", "EQUIPO FUERA DE RANGOS"];
        $Helper = new ServicioBLL();
        $Servicios = json_decode($Helper->GetAll());
        foreach ($Servicios as $s) {
            $FuncCorrectamente = 0;
            $EsperaRep = 0;
            $FueraServ = 0;
            $RetiradoServ = 0;
            $RecBaja = 0;
            $FueraRangos = 0;
            $TFuncCorrectamente = 0;
            $TEsperaRep = 0;
            $TFueraServ = 0;
            $TRetiradoServ = 0;
            $TRecBaja = 0;
            $TFueraRangos = 0;
            foreach ($EstadoFinal as $ef) {
                foreach ($lst as $value) {
                    $Estados = json_decode($value->EstadoFinal);
                    foreach ($Estados as $e) {
                        if ($ef == $e && $value->ServicioId == $s->ServicioId) {
                            if ($ef == "EQUIPO FUNCIONANDO CORRECTAMENTE") {
                                $FuncCorrectamente++;
                                $TFuncCorrectamente += $value->TotalRepuesto;
                            } else if ($ef == "EQUIPO EN ESPERA DE REPUESTOS") {
                                $EsperaRep++;
                                $TEsperaRep += $value->TotalRepuesto;
                            } else if ($ef == "EQUIPO FUERA DE SERVICIO") {
                                $FueraServ++;
                                $TFueraServ += $value->TotalRepuesto;
                            } else if ($ef == "EQUIPO RETIRADO DEL SERVICIO") {
                                $RetiradoServ++;
                                $TRetiradoServ += $value->TotalRepuesto;
                            } else if ($ef == "RECOMENDACION DE BAJA") {
                                $RecBaja++;
                                $TRecBaja += $value->TotalRepuesto;
                            } else if ($ef == "EQUIPO FUERA DE RANGOS") {
                                $FueraRangos++;
                                $TFueraRangos += $value->TotalRepuesto;
                            }
                        }
                    }
                }
            }
            array_push($list, Array(
                "Servicio" => $s->Nombre,
                'FuncCorrectamente' => $FuncCorrectamente,
                'EsperaRep' => $EsperaRep,
                'FueraServ' => $FueraServ,
                'RetiradoServ' => $RetiradoServ,
                'RecBaja' => $RecBaja,
                'FueraRangos' => $FueraRangos,
                'TFuncCorrectamente' => $TFuncCorrectamente,
                'TEsperaRep' => $TEsperaRep,
                'TFueraServ' => $TFueraServ,
                'TRetiradoServ' => $TRetiradoServ,
                'TRecBaja' => $TRecBaja,
                'TFueraRangos' => $TFueraRangos,
                'TotalEstados' => $FuncCorrectamente + $EsperaRep + $FueraServ + $RetiradoServ + $RecBaja + $FueraRangos,
                'TotalMantenimientos' => $TFuncCorrectamente + $TEsperaRep + $TFueraServ + $TRetiradoServ + $TRecBaja + $TFueraRangos,
            ));
        }
        return $list;
    }

    private function EstadoFinalBySede($lst) {
        $list = Array();
        $EstadoFinal = ["EQUIPO FUNCIONANDO CORRECTAMENTE", "EQUIPO EN ESPERA DE REPUESTOS", "EQUIPO FUERA DE SERVICIO", 
            "EQUIPO RETIRADO DEL SERVICIO", "RECOMENDACION DE BAJA", "EQUIPO FUERA DE RANGOS"];
        $Helper = new SedeBLL();
        $Sedes = json_decode($Helper->GetAll());
        foreach ($Sedes as $s) {
            $FuncCorrectamente = 0;
            $EsperaRep = 0;
            $FueraServ = 0;
            $RetiradoServ = 0;
            $RecBaja = 0;
            $FueraRangos = 0;
            $TFuncCorrectamente = 0;
            $TEsperaRep = 0;
            $TFueraServ = 0;
            $TRetiradoServ = 0;
            $TRecBaja = 0;
            $TFueraRangos = 0;
            foreach ($EstadoFinal as $ef) {
                foreach ($lst as $value) {
                    if ($value->EstadoFinal != "N/A") {
                        $Estados = json_decode($value->EstadoFinal);
                        foreach ($Estados as $e) {
                            if ($ef == $e && $value->SedeId == $s->SedeId) {
                                if ($ef == "EQUIPO FUNCIONANDO CORRECTAMENTE") {
                                    $FuncCorrectamente++;
                                    $TFuncCorrectamente += $value->TotalRepuesto;
                                } else if ($ef == "EQUIPO EN ESPERA DE REPUESTOS") {
                                    $EsperaRep++;
                                    $TEsperaRep += $value->TotalRepuesto;
                                } else if ($ef == "EQUIPO FUERA DE SERVICIO") {
                                    $FueraServ++;
                                    $TFueraServ += $value->TotalRepuesto;
                                } else if ($ef == "EQUIPO RETIRADO DEL SERVICIO") {
                                    $RetiradoServ++;
                                    $TRetiradoServ += $value->TotalRepuesto;
                                } else if ($ef == "RECOMENDACION DE BAJA") {
                                    $RecBaja++;
                                    $TRecBaja += $value->TotalRepuesto;
                                } else if ($ef == "EQUIPO FUERA DE RANGOS") {
                                    $FueraRangos++;
                                    $TFueraRangos += $value->TotalRepuesto;
                                }
                            }
                        }
                    }
                }
            }
            array_push($list, Array(
                "Sede" => $s->Nombre,
                'FuncCorrectamente' => $FuncCorrectamente,
                'EsperaRep' => $EsperaRep,
                'FueraServ' => $FueraServ,
                'RetiradoServ' => $RetiradoServ,
                'RecBaja' => $RecBaja,
                'FueraRangos' => $FueraRangos,
                'TFuncCorrectamente' => $TFuncCorrectamente,
                'TEsperaRep' => $TEsperaRep,
                'TFueraServ' => $TFueraServ,
                'TRetiradoServ' => $TRetiradoServ,
                'TRecBaja' => $TRecBaja,
                'TFueraRangos' => $TFueraRangos,
                'TotalEstados' => $FuncCorrectamente + $EsperaRep + $FueraServ + $RetiradoServ + $RecBaja + $FueraRangos,
                'TotalMantenimientos' => $TFuncCorrectamente + $TEsperaRep + $TFueraServ + $TRetiradoServ + $TRecBaja + $TFueraRangos,
            ));
        }
        return $list;
    }

    private function TipoReportesBySede($lst) {
        $list = Array();
        $Helper = new SedeBLL();
        $Sedes = json_decode($Helper->GetAll());
        foreach ($Sedes as $s) {
            $Correctivos = 0;
            $Preventivos = 0;
            $Traslados = 0;
            $Instalacion = 0;
            $Calibracion = 0;
            $TotalCorrectivo = 0;
            $TotalPreventivo = 0;
            $TotalCalibracion = 0;
            $TotalInstalacion = 0;
            $TotalTraslado = 0;
            foreach ($lst as $value) {
                if ($value->SedeId == $s->SedeId) {
                    if ($value->TipoServicio == "PREVENTIVO") {
                        $Preventivos++;
                        $TotalPreventivo += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "CORRECTIVO") {
                        $Correctivos++;
                        $TotalCorrectivo += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "CALIBRACION") {
                        $Calibracion++;
                        $TotalCalibracion += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "INSTALACION") {
                        $Instalacion++;
                        $TotalInstalacion += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "TRASLADOS") {
                        $Traslados++;
                        $TotalTraslado += $value->TotalRepuesto;
                    }
                }
            }
            array_push($list, Array(
                "Sede" => $s->Nombre,
                'NCorrectivos' => $Correctivos,
                'NPreventivos' => $Preventivos,
                'NTraslados' => $Traslados,
                'NInstalacion' => $Instalacion,
                'NCalibracion' => $Calibracion,
                'TotalCorrectivo' => $TotalCorrectivo,
                'TotalPreventivo' => $TotalPreventivo,
                'TotalCalibracion' => $TotalCalibracion,
                'TotalInstalacion' => $TotalInstalacion,
                'TotalTraslado' => $TotalTraslado,
                'TotalRepuesto' => $TotalPreventivo + $TotalCorrectivo + $TotalCalibracion + $TotalInstalacion + $TotalTraslado,
                'TotalMantenimientos' => $Preventivos + $Correctivos + $Traslados + $Instalacion + $Calibracion,
            ));
        }
        return $list;
    }

    private function TipoReportesByServicios($lst,$UsuarioId) {
        $list = Array();
        $Helper = new ServicioBLL();
        $Servicios = json_decode($Helper->GetAllByUserId($UsuarioId));
        foreach ($Servicios as $s) {
            $Correctivos = 0;
            $Preventivos = 0;
            $Traslados = 0;
            $Instalacion = 0;
            $Calibracion = 0;
            $TotalCorrectivo = 0;
            $TotalPreventivo = 0;
            $TotalCalibracion = 0;
            $TotalInstalacion = 0;
            $TotalTraslado = 0;
            foreach ($lst as $value) {
                if ($value->ServicioId == $s->ServicioId) {
                    if ($value->TipoServicio == "PREVENTIVO") {
                        $Preventivos++;
                        $TotalPreventivo += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "CORRECTIVO") {
                        $Correctivos++;
                        $TotalCorrectivo += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "CALIBRACION") {
                        $Calibracion++;
                        $TotalCalibracion += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "INSTALACION") {
                        $Instalacion++;
                        $TotalInstalacion += $value->TotalRepuesto;
                    } else if ($value->TipoServicio == "TRASLADOS") {
                        $Traslados++;
                        $TotalTraslado += $value->TotalRepuesto;
                    }
                }
            }
            array_push($list, Array(
                "Servicio" => $s->Nombre,
                'NCorrectivos' => $Correctivos,
                'NPreventivos' => $Preventivos,
                'NTraslados' => $Traslados,
                'NInstalacion' => $Instalacion,
                'NCalibracion' => $Calibracion,
                'TotalCorrectivo' => $TotalCorrectivo,
                'TotalPreventivo' => $TotalPreventivo,
                'TotalCalibracion' => $TotalCalibracion,
                'TotalInstalacion' => $TotalInstalacion,
                'TotalTraslado' => $TotalTraslado,
                'TotalRepuesto' => $TotalPreventivo + $TotalCorrectivo + $TotalCalibracion + $TotalInstalacion + $TotalTraslado,
                'TotalMantenimientos' => $Preventivos + $Correctivos + $Traslados + $Instalacion + $Calibracion,
            ));
        }
        return $list;
    }
}
