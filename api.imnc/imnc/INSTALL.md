# INSTALACIÓN

El siguiente procedimiento es necesario para configurar el backend.

Todos los archivos necesarios estan en la carpeta 000-install

- Utiliza dbadminer para crear la base de datos a partir del archivo CERTIFICANDO.sql.gz

- Copiar todos los archivos de la carpeta 000-Install a la carpeta common

- Cada archivo necesita configurarse apropiadamente
- conn-apiserver.php => Requiere la url al API
- conn-medoo.php => Requiere los datos de conexión a la BD
- conn-sendgrid-php => Requiere el API Key de Sendgrid

CARPETAS CON PERMISOS DE ESCRITURA

./ArchivoExpediente
./repositorio/archivos