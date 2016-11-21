<?php
require '../Slim/Slim.php';
$app = new Slim();

require('WSConexion.php');

$app->get('/', 'getDefault');

//EMPRESAS
$app->post('/empresa/', 'addEmpresa');
$app->get('/empresa/', 'getEmpresas');
$app->get('/empresa/:id', 'getEmpresa');
$app->put('/empresa/:id', 'updateEmpresa');

//MOTES
$app->post('/mote/','addMote');
$app->get('/mote/','getMotes');
$app->get('/mote/:id','getMote');
$app->get('/motesensor/:id','getMoteSensor');
$app->get('/motesensor/empresa/:id','getMoteSensorEmpresa');
$app->put('/mote/:id','updateMote');

//SENSORES
$app->get('/sensor/','getSensores');
$app->get('/sensor/:id','getSensor');

//USUARIOS
$app->get('/usuario/:id','getUsuario');
$app->get('/usuario/logeo/:username/:password','logeoUsuario');
$app->put('/usuario/:id','updateUsuario');

//PISCINAS
$app->get('/piscina/:id','getPiscina');

//ASIGANACION SENSORES PISCINA
$app->get('/sensoractuador/piscina/:id','getSensorActuadorPiscina');

//TAREAS
$app->get('/tarea/', 'getTareas');

//ROLES
$app->get('/rol/:id', 'getRol');

//CARGOS
$app->get('/cargo/', 'getCargos');

//PAISES
$app->get('/pais/', 'getPaises');

//PROVINCIAS
$app->get('/provincia/:id', 'getProvincia');

//CANTONES
$app->get('/canton/:id', 'getCanton');

//PARROQUIAS
$app->get('/parroquia/:id', 'getParroquia');

$app->run();

//DEFAULT
function getDefault() {
    echo '<table><tr><td><img src="../../img/safac.png" width="70px" alt="User Image" style="opacity:0.7;"></td><td><br><h1> WEBSERVICE -SAFAC-</h1></td></tr></table><hr>';
    echo '<marquee><h3>Consume los webservice rest</h3></marquee>';
}

//FUNCIONES EMPRESAS
function getEmpresas() {
    $sql = "select * FROM empresa ORDER BY emp_nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $empresas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"empresa": ' . json_encode($empresas) . '}';
        //echo json_encode($empresas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getEmpresa($id) {
    $sql = "SELECT * FROM empresa, parroquia, canton, provincia, pais WHERE empresa.emp_id=:id AND parroquia.par_id=empresa.par_id
    AND canton.can_id=parroquia.can_id AND provincia.pro_id = canton.pro_id AND pais.pai_id = provincia.pai_id";
    //$sql = "SELECT * FROM empresa, usuario WHERE empresa.cod_empresa=:id AND empresa.cod_empresa=usuario.cod_empresa AND usuario.cod_rol=2";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $empresa = $stmt->fetchObject();
        $db = null;
        echo json_encode($empresa);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addEmpresa() {
    $request = Slim::getInstance()->request();
    $empresa = json_decode($request->getBody());
    $sql = "INSERT INTO empresa (nombre_empresa,ruc_empresa,direccion_empresa,telefono_empresa,estado_empresa) VALUES (:nombre_empresa, :ruc_empresa, :direccion_empresa, :telefono_empresa, :estado_empresa)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre_empresa", $empresa ->nombre_empresa);
        $stmt->bindParam("ruc_empresa", $empresa ->ruc_empresa);
        $stmt->bindParam("direccion_empresa", $empresa ->direccion_empresa);
        $stmt->bindParam("telefono_empresa", $empresa ->telefono_empresa);
        $stmt->bindParam("estado_empresa", $empresa ->estado_empresa);
        $stmt->execute();
        $db = null;
        echo json_encode($empresa);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateEmpresa($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $empresa = json_decode($body);
    $sql = "UPDATE empresa SET nombre_empresa=:nombre_empresa, ruc_empresa=:ruc_empresa, direccion_empresa=:direccion_empresa,
    telefono_empresa=:telefono_empresa, estado_empresa=:estado_empresa WHERE cod_empresa=:cod_empresa";
    try {
        $db = getConnection();
        $db->beginTransaction(); 
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre_empresa", $empresa->nombre_empresa);
        $stmt->bindParam("ruc_empresa", $empresa->ruc_empresa);
        $stmt->bindParam("direccion_empresa", $empresa->direccion_empresa);
        $stmt->bindParam("telefono_empresa", $empresa->telefono_empresa);
        $stmt->bindParam("estado_empresa", $empresa->estado_empresa);
        $stmt->bindParam("cod_empresa", $id);
        $stmt->execute();
        
        $db->commit(); 
        $db = null;
        echo 'BIEN';
    } catch(PDOException $e) {
        $db->rollback();
        $codeError = $e->getCode();
        if($codeError==23505){
            echo 'Verifique RUC (Ya existe)';
        }else{
            echo 'Error al actualizar datos';
        }
       
    }
}



//FUNCIONES MOTES
function getMotes() {
    $sql = "SELECT * FROM mote ORDER BY pin_mote";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $motes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"mote": ' . json_encode($motes) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMote($id) {
    $sql = "SELECT * FROM mote WHERE cod_mote=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $mote = $stmt->fetchObject();
        $db = null;
        echo json_encode($mote);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMoteSensor($id) {
    $sql = "SELECT * FROM mote_sensor_actuador, sensor_actuador, tipo_sensor_actuador
     WHERE mote_sensor_actuador.cod_mote=:id AND mote_sensor_actuador.cod_sensor_actuador=sensor_actuador.cod_sensor_actuador
     AND sensor_actuador.cod_tipo_sensor_actuador=tipo_sensor_actuador.cod_tipo_sensor_actuador";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $motes = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"motesensor": ' . json_encode($motes) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getMoteSensorEmpresa($id) {
    //$sql = "SELECT * FROM mote_sensor_actuador, mote
     //WHERE mote_sensor_actuador.cod_mote=mote.cod_mote AND mote.cod_empresa=:id AND cod_mote_sensor_actuador<>(SELECT cod_mote_sensor_actuador FROM piscina_mote_sensor_actuador)";
    $sql = "SELECT * FROM mote_sensor_actuador, mote, sensor_actuador
     WHERE mote_sensor_actuador.cod_mote=mote.cod_mote AND mote.cod_empresa=:id AND 
     mote_sensor_actuador.cod_sensor_actuador=sensor_actuador.cod_sensor_actuador AND NOT EXISTS 
     (SELECT * FROM piscina_mote_sensor_actuador WHERE cod_mote_sensor_actuador=mote_sensor_actuador.cod_mote_sensor_actuador)
     ORDER BY mote.mac_mote";
     

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $motes = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$motes = '';
        while($fila = $stmt->fetchObject()){
            if($fila->cod_mote_sensor_actuador!=0){
                var_dump($motes[$fila]);
            }
        }


        $db = null;
        echo '{"motesensor": ' . json_encode($motes) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addMote(){

	$request = Slim::getInstance()->request();
	$mote = json_decode($request->getBody());

    $sensores = $mote->sensores;
    //$sensores = explode(',',$sensores);
    $pines = $mote->pines;
    //$pines = explode(',',$pines);

    $estados = $mote->estados;

 	$sql ="insert into mote (mac_mote,cod_empresa,estado_mote) values (:mac_mote, :cod_empresa, :estado_mote)";
	$db = getConnection();

    try{
		
        $db->beginTransaction(); 
		$stmt = $db->prepare($sql);
		$stmt -> bindParam("mac_mote",$mote->mac_mote);
		$stmt -> bindParam("cod_empresa",$mote->cod_empresa);
        $stmt -> bindParam("estado_mote",$mote->estado_mote);
        
		$stmt -> execute();

        $sql3 = "SELECT * FROM mote ORDER BY cod_mote DESC LIMIT 1";
        $stmt3 = $db->prepare($sql3);
        $stmt3 -> execute();
        $idUltimo = 0;
   
        while($fila = $stmt3->fetchObject()){
            $idUltimo = $fila->cod_mote;
        }


        for($i=0;$i<count($sensores);$i++){
            $cod_sensor_actuador = $sensores[$i];
            $pin_mote = $pines[$i];
            $estado_mote_sensor_actuador = $estados[$i];
            
            
            $sql2 = "insert into mote_sensor_actuador (cod_sensor_actuador, cod_mote, pin_mote, estado_mote_sensor_actuador) values (:cod_sensor_actuador,:cod_mote,:pin_mote,:estado_mote_sensor_actuador)"; 
            $stmt2 = $db->prepare($sql2);
            $stmt2 -> bindParam("cod_sensor_actuador",$cod_sensor_actuador);
            $stmt2 -> bindParam("cod_mote",$idUltimo);
            $stmt2 -> bindParam("pin_mote",$pin_mote);
            $stmt2 -> bindParam("estado_mote_sensor_actuador",$estado_mote_sensor_actuador);
            $stmt2 -> execute();
            
        }
        $db->commit(); 
		$db = null;
        echo 'BIEN';
		//echo json_encode($mote);
	}catch(PDOException $e){
        $db->rollback(); 
        $codeError = $e->getCode();
        if($codeError==23505){
            echo 'Esta MAC ya existe';
        }else{
            if($codeError==23503){
                echo 'Esa empresa no existe';
            }else{
                echo 'Error al crear nuevo mote';
            }
            
        }
		
		
	}
}


function updateMote($id){
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $mote = json_decode($body);
    $sensores = $mote->sensores;
    $pines = $mote->pines;
    $estados = $mote->estados;
    $codigos = $mote->codigos;

    $sql = "UPDATE mote SET mac_mote=:mac_mote, cod_empresa=:cod_empresa WHERE cod_mote=:cod_mote";
    $db = getConnection();
    try {
        $db->beginTransaction(); 
        $stmt = $db->prepare($sql);
        $stmt -> bindParam("mac_mote",$mote->mac_mote);
        $stmt -> bindParam("cod_empresa",$mote->cod_empresa);
        $stmt->bindParam("cod_mote", $id);
        $stmt->execute();

        
        for($i=0;$i<count($codigos);$i++){
            $cod_sensor_actuador = $sensores[$i];
            $pin_mote = $pines[$i];
            $estado_mote_sensor_actuador = $estados[$i];
            $cod_mote_sensor_actuador = $codigos[$i];
            
            $sql2 = "UPDATE mote_sensor_actuador SET cod_sensor_actuador=:cod_sensor_actuador, cod_mote=:cod_mote, pin_mote=:pin_mote, estado_mote_sensor_actuador=:estado_mote_sensor_actuador WHERE cod_mote_sensor_actuador=:cod_mote_sensor_actuador";
            $stmt2 = $db->prepare($sql2);
            $stmt2 -> bindParam("cod_sensor_actuador",$cod_sensor_actuador);
            $stmt2 -> bindParam("cod_mote",$id);
            $stmt2 -> bindParam("pin_mote",$pin_mote);
            $stmt2 -> bindParam("estado_mote_sensor_actuador",$estado_mote_sensor_actuador);
            $stmt2 -> bindParam("cod_mote_sensor_actuador",$cod_mote_sensor_actuador);
            $stmt2 -> execute();
            
        }

        $sql2 = "SELECT * FROM mote_sensor_actuador WHERE cod_mote=:cod_mote";
        $stmt2 = $db->prepare($sql2);
        $stmt2 -> bindParam("cod_mote",$id);
        $stmt2 -> execute();

        $listaCodEliminar = [];

        while($fila = $stmt2->fetchObject()){
            $cod_mote_sensor_actuador = $fila->cod_mote_sensor_actuador;
            $Ncod = false;
            for($i=0;$i<count($codigos);$i++){
                if($cod_mote_sensor_actuador==$codigos[$i]){
                    $Ncod = true;
                }
            }
            if($Ncod==false)array_push($listaCodEliminar, $cod_mote_sensor_actuador);
        }

        for($i=0;$i<count($listaCodEliminar);$i++){
            $sql2 = "DELETE FROM mote_sensor_actuador WHERE cod_mote_sensor_actuador=:cod_mote_sensor_actuador";
            $stmt2 = $db->prepare($sql2);
            $stmt2 -> bindParam("cod_mote_sensor_actuador",$listaCodEliminar[$i]);
            $stmt2 -> execute();
        }


       

        for($i=count($codigos);$i<count($sensores);$i++){
            $cod_sensor_actuador = $sensores[$i];
            $pin_mote = $pines[$i];
            $estado_mote_sensor_actuador = $estados[$i];
            
            
            $sql2 = "insert into mote_sensor_actuador (cod_sensor_actuador, cod_mote, pin_mote, estado_mote_sensor_actuador) values (:cod_sensor_actuador,:cod_mote,:pin_mote,:estado_mote_sensor_actuador)"; 
            $stmt2 = $db->prepare($sql2);
            $stmt2 -> bindParam("cod_sensor_actuador",$cod_sensor_actuador);
            $stmt2 -> bindParam("cod_mote",$id);
            $stmt2 -> bindParam("pin_mote",$pin_mote);
            $stmt2 -> bindParam("estado_mote_sensor_actuador",$estado_mote_sensor_actuador);
            $stmt2 -> execute();
            
        }
        

        

        $db->commit(); 
        $db = null;
        echo 'BIEN';
    } catch(PDOException $e) {
        $db->rollback(); 
        $codeError = $e->getCode();
        if($codeError==23505){
            echo 'Esta MAC ya existe';
        }else{
            if($codeError==23503){
                echo 'Esa empresa no existe';
            }else{
                echo 'Error al actualizar los datos'.$e->getMessage();
            }
            
        }
    }
}



//FUNCIONES SENSORES
function getSensores() {
    $sql = "select * FROM sensor_actuador ORDER BY sen_nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $sensores = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"sensor": ' . json_encode($sensores) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getSensor($id) {
    $sql = "SELECT * FROM sensor_actuador, tipo_sensor_actuador WHERE 
    sensor_actuador.sen_id=:id AND 
    sensor_actuador.tse_id=tipo_sensor_actuador.tse_id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $sensor = $stmt->fetchObject();
        $db = null;
        echo json_encode($sensor);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//FUNCIONES USUARIO
function getUsuario($id) {
    $sql = "SELECT * FROM usuario, empresa, rol, cargo WHERE usu_id=:usu_id and empresa.emp_id=usuario.emp_id 
    and rol.rol_id=usuario.rol_id and cargo.car_id=rol.car_id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("usu_id", $id);
        $stmt->execute();
        $usuario = $stmt->fetchObject();
        $db = null;
        echo json_encode($usuario);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function logeoUsuario($username, $password){
    $password = base64_encode($password);
    $sql = "SELECT * FROM usuario, rol WHERE email_usuario=:email_usuario 
    AND password_usuario=:password_usuario AND usuario.cod_rol=rol.cod_rol";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email_usuario", $username);
        $stmt->bindParam("password_usuario", $password);
        $stmt->execute();
        $usuario = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($usuario);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateUsuario($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $usuario = json_decode($body);
    $sql = "UPDATE usuario SET nombre_usuario=:nombre_usuario, apellido_usuario=:apellido_usuario, sexo_usuario=:sexo_usuario,
    estadocivil_usuario=:estadocivil_usuario, email_usuario=:email_usuario, direccion_usuario=:direccion_usuario, telefono_usuario=:telefono_usuario
     WHERE cod_usuario=:id";
    try {
        $db = getConnection();
        $db->beginTransaction(); 
        $stmt = $db->prepare($sql);
        $stmt->bindParam("nombre_usuario", $usuario->nombre_usuario);
        $stmt->bindParam("apellido_usuario", $usuario->apellido_usuario);
        $stmt->bindParam("direccion_usuario", $usuario->direccion_usuario);
        $stmt->bindParam("telefono_usuario", $usuario->telefono_usuario);
        $stmt->bindParam("sexo_usuario", $usuario->sexo_usuario);
        $stmt->bindParam("estadocivil_usuario", $usuario->estadocivil_usuario);
        $stmt->bindParam("email_usuario", $usuario->email_usuario);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        
        $db->commit(); 
        $db = null;
        echo 'BIEN';
    } catch(PDOException $e) {
        $db->rollback();
        $codeError = $e->getCode();
        if($codeError==23505){
            echo 'Verifique Email (Ya existe)';
        }else{
            echo 'Error al actualizar datos';
        }
       
    }
}

//FUNCIONES PISCINA
function getPiscina($id) {
    $sql = "SELECT * FROM piscina WHERE cod_piscina=:cod_piscina";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cod_piscina", $id);
        $stmt->execute();
        $piscina = $stmt->fetchObject();
        $db = null;
        echo json_encode($piscina);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//ASIGANACION SENSORES PISCINA
function getSensorActuadorPiscina($id) {
    $sql = "SELECT * FROM piscina_mote_sensor_actuador, mote_sensor_actuador, sensor_actuador, mote
     WHERE piscina_mote_sensor_actuador.cod_piscina=:id AND piscina_mote_sensor_actuador.cod_mote_sensor_actuador=mote_sensor_actuador.cod_mote_sensor_actuador
     AND mote_sensor_actuador.cod_sensor_actuador=sensor_actuador.cod_sensor_actuador AND mote_sensor_actuador.cod_mote=mote.cod_mote";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $sensorespiscina = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"sensorespiscina": ' . json_encode($sensorespiscina) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//FUNCIONES TAREAS
function getTareas() {
    $sql = "select * FROM tarea, gestion WHERE tarea.ges_id=gestion.ges_id AND tarea.tar_super='f' ORDER BY tar_id";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $tareas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"tarea": ' . json_encode($tareas) . '}';
        //echo json_encode($empresas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//FUNCIONES ROLES
function getRol($id) {
    $sql = "SELECT * FROM permiso, rol, cargo, tarea, gestion WHERE permiso.rol_id=:id and rol.rol_id=:id and 
    cargo.car_id=rol.car_id and tarea.tar_id=permiso.tar_id and gestion.ges_id=tarea.ges_id order by tarea.tar_id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $rol = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($rol);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//FUNCIONES CARGO
function getCargos() {
    $sql = "select * FROM cargo WHERE car_id != 1 ORDER BY car_id";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $cargos = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"cargo": ' . json_encode($cargos) . '}';
        //echo json_encode($empresas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//FUNCIONES PAISES
function getPaises() {
    $sql = "select * FROM pais ORDER BY pai_nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $tareas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"pais": ' . json_encode($tareas) . '}';
        //echo json_encode($empresas);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//FUNCIONES PROVINCIAS
function getProvincia($id) {
    $sql = "SELECT * FROM provincia WHERE pai_id=:id";
    //$sql = "SELECT * FROM empresa, usuario WHERE empresa.cod_empresa=:id AND empresa.cod_empresa=usuario.cod_empresa AND usuario.cod_rol=2";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $provincias = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"provincia": ' . json_encode($provincias) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//FUNCIONES CANTONES
function getCanton($id) {
    $sql = "SELECT * FROM canton WHERE pro_id=:id";
    //$sql = "SELECT * FROM empresa, usuario WHERE empresa.cod_empresa=:id AND empresa.cod_empresa=usuario.cod_empresa AND usuario.cod_rol=2";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $cantones = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"canton": ' . json_encode($cantones) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


//FUNCIONES PARROQUIAS
function getParroquia($id) {
    $sql = "SELECT * FROM parroquia WHERE can_id=:id";
    //$sql = "SELECT * FROM empresa, usuario WHERE empresa.cod_empresa=:id AND empresa.cod_empresa=usuario.cod_empresa AND usuario.cod_rol=2";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $parroquias = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"parroquia": ' . json_encode($parroquias) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

?>