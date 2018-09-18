Release notes

certificando-web

Versión 1.7.6

Nuevo módulo: Cotizador
Funcionalidad: Todas las tablas guardan quién inserta y elmina registro junto con la fecha y hora
Correcciones en Prospectos y Expediente
Bug fixed: Filtros de Clientes no funcionaba correctamente

___

Versión 1.7.5

Bugs fixed: En módulo Prospectos
Nuevo módulo: Usuarios

Versión 1.7.4

Nuevo módulo: Prospectos
Nuevo módulo: Expediente

Versión 1.7.3

En búsqueda de auditores: Agregué clave de sector y sector NACE en la selección de grupo de auditores
Bug fixed: Las iniciales del auditor deben permitirse solo letras
Bug fixed: Corregir las etiquetas de muestra de acuerdo a las reglas de captura
Bug fixed: Restringir el campo curp en auditores a un máscara de entrada
Bug fixed: La fecha de nacimiento no puede ser a futuro
Bug fixed: Sector Nace solo debe aceptar números con varios puntos
Bug fixed: En grupos auditores se está bloqueando un registro de calificación y debería bloquear un auditor

Nueva funcionalidad: Validación de fechas en todas las secciones
Nueva funcionalidad: Autocompletar de PAIS, CODIGO POSTAL, COLONIA, MUNICIPIO y ESTADO

Otras mejoras: Cambio en el diseño del inicio de sesión

Versión 1.7.2

Bug fixed: En filtros de auditores, búsqueda por sector
En búsqueda de auditores: Filtros de búsqueda con "starts with" en lugar de "contains"
En sitios de auditoría: Cantidad de personas se muestran solo cuando tipo_servicio = CSAST
En sectores de auditoría: Se puso una restricción para no permitir sectores duplicados
En grupo de auditores: Muestra que sectores cubre y que roles tiene asociado

Nueva funcionalidad: Filtros de clientes

Versión 1.7.1

Bug fixed: Se implementó la restricción al RFC de acuerdo a las reglas para personas físicas y morales de México.
Bug fixed: Verificar que SIEC no permite "Guardar" un registro hasta que todoslos campos obligatorios estén llenos en Domicilio de cliente
Bug fixed: Verificar que SIEC no permite "Guardar" un registro de contacto hasta que todoslos campos obligatorios estén llenos. Insertar una alerta de campos obligatorios faltantes en Contactos de Cliente
Bug fixed: Campos de teléfono móvil, teléfono fijo, extensión que sean sólo numéricos en Contactos de Cliente
Bug fixed: Validar que el SIEC sólo habilita el campo de"Es Integral"para el servicio de "Certificación de Sistemas de Gestión". Para todos los demás servicios este campo debe estar bloqueado.
Bug fixed: Para completar los detalles requeridos para la certificación, los campos de Total de empleados, Total de empelados para certificación, Número de Turnos sólo deben admitir números mayores que cero (0).
Bug fixed: El campo de "Condiciones de Seguridad" debe ser un campo de texto libre, dentro del que se pueda explicar las condiciones de seguridad requeridas. Si no se requiere ninguna el usuario puede ingresar N/A en servicio contratado
Bug fixed: El campo de "Alcance"debe ser obligatorio (campo con *) en servicio contratado
Bug fixed: No permitir duplicar clientes ni auditores, validar por RFC en CLIENTES y en AUDITORES
Bug fixed: Quedo atento a tus comentarios.

Versión 1.7

- Nuevo: Módulo para agregar certificado a los servicios contratados
- Nuevo: Los popups se pueden arrastar
- Nuevo: Los sectores ahora se identifican por NUMERO+ID_TIPO_SERVICIO+AÑO

- Demo: Notificación de servicio mejorada
- Demo: Flujo de trabajo para ONAC

- Bug fixed: Error al momento de implementar MVC corregido
- Bug fixed: No mostraba lista de auditores si no había alguno con calificación al momento de armar un grupo de auditores 

Versión 1.6

- Nuevo: Las etiquetas de "auditores" se convierten en "evaluadores" para ONAC
- Nuevo: Sección de agenda en perfil de auditor
- Nuevo: Sección de agenda global para servicios contratados con filtros

- Cambio: Etiqueta de "Etapas de proceso" por "Trámite"
- Cambio: Se cambiaron las etiquetas de auditores a evaluadores para ONAC

- Demo: Flujo de trabajo 
- Demo: Descarga de notificación

- Correcciones de errores y mejoras en la funcionalidad

Versión 1.5.1

- Nuevo: Se implementaron las restricciones para los grupos de auditores y sitios de auditorías


Versión 1.5

- Nuevo: Se implementó el modulo para registrar auditorías para los Sistemas de Gestión


Versión 1.4.2

- Corrección: Registro se recupera de forma automática en sg_tipos_servicio a la hora de insertar una auditor en grupo auditores


Versión 1.4.1

- Mensajes de error agregados en sg_tipos_servicio

Versión 1.4

- GRUPOS DE AUDITORES: Completo
- Mejoras importantes de usabilidad y desempeño

Versión 1.3.2

- Bug fixed: Se vinculó correctamente a los domicilios del Cliente en sg_tipos_servicio


Versión 1.3.1

- Se ordena el catálogo de SECTORES por tipo de servicio y después por sector
- El campo "Alcance" aumenta su tamaño de caracteres
- Se muestra la versión en el pie del webapp
- Bug fixed: Ya funciona el botón "editar" en Sistemas de Gestión: Tipos de servicio


Versión 1.3

- Se agregaron filtros para el padrón de auditores
- Se agregó la sección de servicios contradados	

Versión 1.2.2

- El código fuente se ha estandarizado con el esquema de backend-codeart
- Bug fixed: La calificación_sector ya se inserta correctamente

Versión 1.2.1.4

- Bug fixed: Ahora se puede insertar un cliente sin asociarle un cliente facturario

Versión 1.2.1.3

- Nueva funcionalidad:  La sección de clientes se ha conectado correctamente a TIPOS_ENTIDAD, TIPOS_PERSONA y CLIENTES_FACTURAR
- Adicionales:
	Se agrega usuario "onac" para iniciar sesión
	Perfil de ONAC

Versión 1.2.1.2

- Bug fixed: Los registros de catálogo de domicilos de auditores ya funciona correctamente

Versión 1.2.1.1

- Bug fixed: Los registros de catálogo de sectores ya se pueden editar

Historial de versiones

Versión 1.2.1

- Bug fixed: Las fotos en perfil de clientes y de auditores se muestran correctamente
- Bug fixed: Las fechas en la calificación del auditor ya se pueden editar e insertar
- Bug fixed: El catálogo de Normas ya funciona correctamente, se puede insertar y editar los registros

Versión: 1.2

- Nueva sección: catálogos => tipos de personas
- Nueva sección: catálogos => tipos de entidades

Versión: 1.1

- Nueva funcionalidad: Se implementó inicio de sesión y manejo de sesión. El tiempo límite de duración de la sesión es de 30 minutos. Después de eso el usuario tiene que volver a iniciar sesión.

- Bug fixed: Las imagénes de clientes y auditores ya se despliegan correctamente

- Adicionales: Se agregó un selector para cambiar el frontend según el cliente (imnc o dht)
