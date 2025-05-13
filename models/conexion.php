<?php

abstract class Conexion {
    protected static $conexion = null;

    static function conectar() : PDO{
        try{
            self::$conexion = new PDO("informix:host=host.docker.internal; service=9088; database=farmaceutica; server=informix; protocol=onsoctcp;EnableScrollableCursors=1","informix","in4mix");
            self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            error_log("Error de conexión a la BD: " . $e->getMessage());
            self::$conexion = null;
            throw new Exception("Error de conexión a la base de datos");
        }
        return self::$conexion;
    }

    public function ejecutar($sql, $params= [])
    {
        try {
            $conexion = self::conectar();
            $sentencia = $conexion->prepare($sql);
            $resultado = $sentencia->execute($params);
            $idInsertado = $conexion->lastInsertId();
            return [
                "resultado" => $resultado,
                "id" => $idInsertado
            ];
        } catch (Exception $e) {
            error_log("Error en ejecutar: " . $e->getMessage());
            throw new Exception("Error al ejecutar la consulta: " . $e->getMessage());
        }
    }

    public function servir($sql, $params = [])
    {
        try {
            $conexion = self::conectar();
            $sentencia = $conexion->prepare($sql);
            $sentencia->execute($params);
            $data = $sentencia->fetchAll(PDO::FETCH_ASSOC);

            $datos = [];
            foreach ($data as $k => $v) {
                $datos[] = array_change_key_case($v, CASE_LOWER);
            }
            return $datos;
        } catch (Exception $e) {
            error_log("Error en servir: " . $e->getMessage());
            throw new Exception("Error al obtener datos: " . $e->getMessage());
        }
    }
    
    public function traer($sql)
    {
        try {
            $conexion = self::conectar();
            $sentencia = $conexion->prepare($sql);
            $sentencia->execute();
            $data = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $datos = [];
            foreach($data as $key => $value){
                $datos[] = array_change_key_case($value, CASE_LOWER);
            }
            return $datos;
        } catch (Exception $e) {
            error_log("Error en traer: " . $e->getMessage());
            throw new Exception("Error al obtener datos: " . $e->getMessage());
        }
    }
}