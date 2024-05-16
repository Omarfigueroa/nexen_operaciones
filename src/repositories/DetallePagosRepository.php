<?php

namespace App\Repositories;

use App\Models\DetallePagos;

class DetallePagosRepository
{
    protected DetallePagos $model;

    public function __construct()
    {
        $this->model = new DetallePagos();
    }

    public function all() 
    {
        return $this->model->index();
    }

    public function show($id)
    {
        return $this->model->show($id);
    }

    public function getMovementsAll()
    {
        return $this->model->getMovementsAll();
    }
    public function getDetailsPaymentsByReference($reference)
    {
        return $this->model->getDetailsPaymentsByReference($reference);
    }

    public function setFileInvoice($archivo, $nombreArchivo, $Num_Operacion, $Mensaje_Update = null)
    {
        return $this->model->setFileInvoice($archivo, $nombreArchivo, $Num_Operacion, $Mensaje_Update);
    }


}