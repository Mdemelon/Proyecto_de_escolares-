

## Puesta en marcha (XAMPP)
1. La carpeta del proyecto va en `C:\xampp\htdocs\Servicio_de_tec`.
2. Inicia Apache y MySQL desde el panel de XAMPP.
3. La base de datos es `servicio_social` (configurada en `SRC/Config/Conexicon.php`).
   Su estructura está en `database/esquema_actual.sql`.
4. Si aún no tienes turnos ni dependencias, ejecuta `database/datos-iniciales.sql` en phpMyAdmin
   (pestaña SQL, con `servicio_social` seleccionada).
5. Abre http://localhost/Servicio_de_tec/Public/

Si no recuerdas una contraseña:
`C:\xampp\php\php.exe database\restablecer-clave.php admin NuevaClave123`

Si cambias el nombre de la carpeta, actualiza `BASE_URL` en `SRC/Config/Constantes.php`.

