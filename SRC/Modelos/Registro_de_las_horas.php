<?php 

class Registo_Horas {
    public static function turno_Abierto(int $alumnoId): ?array {
        $st = db()->prepare("SELECT * FROM registro_horas WHERE alumno_id = ? AND estatus = 'en_curso' ORDER BY id DESC LIMIT 1");
        $st->execute([$alumnoId]);
        return $st->fetch() ?: null;
    }
}