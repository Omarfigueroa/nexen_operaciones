<div class="container col-md-5 fixed-top mb-5 position-absolute">
    <div class="row">
        <div class="col">
        <?php 
        if (isset($_SESSION['tipo_alerta']) && !empty($_SESSION['tipo_alerta']) && isset($_SESSION['msj_alerta']) && !empty($_SESSION['msj_alerta'])){

            $alerta=$_SESSION['tipo_alerta'];
            $mensaje=$_SESSION['msj_alerta'];
            
            if(isset($alerta)){ 

                if($alerta==1){ ?>
                    <!-- Success Alert -->
            <!--<div class="alert alert-success alert-dismissible d-flex align-items-center fade show fixed-top">-->
            <div class="alert alert-success alert-dismissible d-flex show top-0 end-0">
                <i class="bi-check-circle-fill"></i> 
                <strong class="mx-2">Exito!</strong> <?php if(isset($mensaje)) {echo  $mensaje;} ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php }?>
            <?php if($alerta==2){ ?> 
            <!-- Error Alert -->
            <div class="alert alert-danger alert-dismissible d-flex align-items-center fade show fixed-top">
                <i class="bi-exclamation-octagon-fill"></i>
                <strong class="mx-2">Error!</strong> <?php if(isset($mensaje)) {echo  $mensaje;} ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } ?>
            <?php if($alerta==3){ ?>  
            <!-- Warning Alert -->
            <div class="alert alert-warning alert-dismissible d-flex align-items-center fade show fixed-top">
                <i class="bi-exclamation-triangle-fill"></i>
                <strong class="mx-2">Warning!</strong> There was a problem with your network connection.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php } ?>
            <?php if($alerta==4){ ?> 
            <!-- Info Alert -->
            <div class="alert alert-info alert-dismissible d-flex align-items-center fade show fixed-top">
                <i class="bi-info-circle-fill"></i>
                <strong class="mx-2">Info!</strong> Please read the comments carefully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php   } 
                    } 
            unset($_SESSION['tipo_alerta']);
            unset($_SESSION['msj_alerta']);            
        } ?>
        </div>
    </div>
</div>