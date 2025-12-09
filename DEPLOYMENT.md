# Guía de Despliegue en Hostinger - WebAtoon

Esta guía te ayudará a desplegar WebAtoon en Hostinger VPS y configurar todo lo necesario para producción.

---

## Requisitos Previos

- VPS en Hostinger (Ubuntu 24.04)
- Dominio configurado apuntando al VPS
- Acceso SSH al servidor

---

## 1. Configuración del Servidor (Ubuntu 24.04)

### Actualizar el sistema
```bash
sudo apt update && sudo apt upgrade -y
```

### Instalar dependencias necesarias
```bash
# PHP 8.2+ y extensiones
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# MySQL
sudo apt install -y mysql-server

# Nginx
sudo apt install -y nginx

# Node.js (para compilar assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Git
sudo apt install -y git
```

---

## 2. Clonar y Configurar el Proyecto

```bash
# Clonar repositorio
cd /var/www
sudo git clone https://github.com/TU-USUARIO/WebAtoon.git
sudo chown -R www-data:www-data WebAtoon
cd WebAtoon

# Instalar dependencias
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Crear archivo .env
cp .env.example .env
php artisan key:generate
```

---

## 3. Configurar Base de Datos

```bash
# Acceder a MySQL
sudo mysql

# En MySQL:
CREATE DATABASE webatoon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'webatoon_user'@'localhost' IDENTIFIED BY 'TU_CONTRASEÑA_SEGURA';
GRANT ALL PRIVILEGES ON webatoon.* TO 'webatoon_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Editar `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webatoon
DB_USERNAME=webatoon_user
DB_PASSWORD=TU_CONTRASEÑA_SEGURA
```

```bash
# Ejecutar migraciones
php artisan migrate --seed
```

---

## 4. Configurar SMTP para Envío de Correos

### Opción A: Gmail (Más fácil para empezar)

**Paso 1:** Generar contraseña de aplicación en Google
1. Ve a https://myaccount.google.com/security
2. Activa "Verificación en 2 pasos"
3. Ve a "Contraseñas de aplicaciones"
4. Genera una contraseña para "Otra (nombre personalizado)"
5. Copia la contraseña generada (16 caracteres sin espacios)

**Paso 2:** Configurar en `.env`
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD=tu_contraseña_app_16_caracteres
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Opción B: SMTP de Hostinger (Más profesional)

Hostinger proporciona SMTP en el panel de control. Busca la sección "Email" en tu panel de Hostinger.

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=noreply@tudominio.com
MAIL_PASSWORD=contraseña_del_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Opción C: SendGrid (Para producción a gran escala)

1. Crear cuenta en https://sendgrid.com/
2. Generar API Key
3. Configurar:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=TU_API_KEY_DE_SENDGRID
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 5. Configurar Variables de Producción

**Editar `.env` completo:**
```env
APP_NAME="WebAtoon"
APP_ENV=production
APP_KEY=base64:TU_KEY_GENERADA
APP_DEBUG=false
APP_URL=https://tudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webatoon
DB_USERNAME=webatoon_user
DB_PASSWORD=TU_CONTRASEÑA_SEGURA

# SMTP (Elige una de las opciones de arriba)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tudominio.com"
MAIL_FROM_NAME="${APP_NAME}"

# Filesystem
FILESYSTEM_DISK=public

# Queue (para notificaciones asíncronas)
QUEUE_CONNECTION=database

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database
```

---

## 6. Configurar Permisos

```bash
cd /var/www/WebAtoon

# Permisos de storage y cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Enlazar storage público
php artisan storage:link

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 7. Configurar Nginx

**Crear archivo de configuración:**
```bash
sudo nano /etc/nginx/sites-available/webatoon
```

**Contenido:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name tudominio.com www.tudominio.com;

    root /var/www/WebAtoon/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Archivos estáticos
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Activar el sitio:**
```bash
sudo ln -s /etc/nginx/sites-available/webatoon /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 8. Configurar SSL (HTTPS)

```bash
# Instalar Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtener certificado SSL
sudo certbot --nginx -d tudominio.com -d www.tudominio.com

# El certificado se renovará automáticamente
```

---

## 9. Configurar Colas (Workers)

Las notificaciones por email usan colas. Necesitas un proceso que las procese.

**Crear servicio systemd:**
```bash
sudo nano /etc/systemd/system/webatoon-worker.service
```

**Contenido:**
```ini
[Unit]
Description=WebAtoon Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/WebAtoon
ExecStart=/usr/bin/php /var/www/WebAtoon/artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

**Activar el servicio:**
```bash
sudo systemctl daemon-reload
sudo systemctl enable webatoon-worker
sudo systemctl start webatoon-worker
sudo systemctl status webatoon-worker
```

---

## 10. Crear Usuario Administrador Inicial

```bash
php artisan tinker
```

En Tinker:
```php
$user = App\Models\User::create([
    'name' => 'Administrador',
    'email' => 'admin@tudominio.com',
    'password' => bcrypt('contraseña_segura'),
    'email_verified_at' => now(),
]);

$user->assignRole('admin');
exit;
```

---

## 11. Probar el Sistema

1. Accede a `https://tudominio.com`
2. Inicia sesión con el admin creado
3. Crea un evento de prueba
4. Registra un proyecto
5. Verifica que lleguen los correos

---

## 12. Configuración de Tareas Programadas (Cron)

```bash
sudo crontab -e -u www-data
```

Agregar:
```cron
* * * * * cd /var/www/WebAtoon && php artisan schedule:run >> /dev/null 2>&1
```

---

## 13. Mantenimiento y Actualización

### Actualizar la aplicación
```bash
cd /var/www/WebAtoon
sudo git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
sudo systemctl restart webatoon-worker
```

### Ver logs en producción
```bash
# Logs de Laravel
tail -f /var/www/WebAtoon/storage/logs/laravel.log

# Logs del worker
sudo journalctl -u webatoon-worker -f

# Logs de Nginx
tail -f /var/log/nginx/error.log
```

---

## 14. Seguridad Adicional

### Firewall
```bash
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

### Fail2ban (protección contra ataques)
```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## Checklist Final de Despliegue

- [ ] Servidor configurado (PHP, MySQL, Nginx)
- [ ] Proyecto clonado y dependencias instaladas
- [ ] Base de datos creada y migrada
- [ ] `.env` configurado correctamente
- [ ] SMTP configurado y probado
- [ ] Permisos correctos en storage/
- [ ] SSL/HTTPS habilitado
- [ ] Worker de colas funcionando
- [ ] Usuario admin creado
- [ ] Cron configurado
- [ ] Firewall activado
- [ ] Sistema probado completamente

---

## Soporte

Si tienes problemas durante el despliegue:

1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica la configuración de Nginx: `sudo nginx -t`
3. Revisa el estado del worker: `sudo systemctl status webatoon-worker`
4. Verifica permisos: `ls -la storage/`

---

**¡Listo! Tu aplicación WebAtoon está en producción.**
