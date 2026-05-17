## 🐋 Guía de Inicialización del Entorno Local

A continuación se detallan las instrucciones técnicas necesarias para desplegar y ejecutar **Edunoly** de forma aislada a través de **Docker** y **Laravel Sail**.

*Recuerde que es necesario tener Docker Desktop activo en su máquina. Si le surge cualquier tipo de duda durante el proceso, ¡póngase en contacto con nosotros en fp.sblancocalsina@salesianosdosa.com y le ayudaremos encantados! :))*

### Preparacion del entorno
1. **Descomprimir el archivo:** Extraiga el contenido del archivo `TFG.zip` en una carpeta de su disco local.
2. **Abrir la terminal:** Acceda mediante la consola de comandos o el terminal de su IDE a la raíz de la carpeta del proyecto:
 ```bash
   cd /ruta_de_extraccion/TFG
   ```
3. **Instanciar las variables de entorno:** Genere el archivo operativo de configuración duplicando la plantilla base:
 ```bash
   cp .env.example .env
   ```
4. **Inicializar la infraestructura Cloud local:** Levante los contenedores del servidor de aplicaciones, Mailpit y la base de datos relacional MySQL en segundo plano:
 ```bash
   ./vendor/bin/sail up -d
   ```
5. **Generar la clave criptográfica del sistema:** Registre una clave única de cifrado de sesiones ejecutando:
 ```bash
   ./vendor/bin/sail artisan key:generate
   ```
6. **Construir y poblar la Base de Datos:** Ejecute las migraciones cronológicas estructurales para armar las tablas en Tercera Forma Normal (3NF) y realice la siembra automatizada del set de datos de prueba:
 ```bash
   ./vendor/bin/sail artisan migrate:fresh --seed
   ```

### Visualizacion en el navegador
1. **Plataforma Educativa (Frontend/Backend):** http://localhost:8080
2. **Bandeja de Entrada de Alertas de Correo (Mailpit):** http://localhost:8025