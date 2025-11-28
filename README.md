# Auditarest - Sistema de Gestión de Auditorías para Restaurantes

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 🚀 Acerca de Auditarest

Auditarest es una aplicación web para la gestión y realización de auditorías en restaurantes. Permite a los auditores realizar evaluaciones, generar informes en PDF y gestionar los resultados de las auditorías de manera eficiente.

## ✨ Características principales

- Sistema de autenticación de usuarios con roles (administrador, auditor, etc.)
- Gestión completa de restaurantes y sus datos
- Realización de auditorías con diferentes categorías y preguntas
- Generación de informes en PDF
- Envío de informes por correo electrónico
- Panel de administración para gestión de usuarios y restaurantes
- Interfaz intuitiva y responsiva con TailwindCSS

## 🛠️ Requisitos del sistema

- PHP >= 8.1
- Composer
- Node.js >= 16.0.0
- npm o yarn
- Base de datos SQLite (incluida) o MySQL/PostgreSQL

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd Auditarest
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de JavaScript**
   ```bash
   npm install
   ```

4. **Configurar entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos**
   - Configurar el archivo `.env` con los datos de tu base de datos
   - O usar SQLite (ya configurado por defecto)

6. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Compilar assets**
   ```bash
   npm run build
   # O para desarrollo:
   # npm run dev
   ```

8. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

## 👥 Usuarios por defecto

Se crean automáticamente con los seeders:
- **Administrador**: admin@auditarest.com / password
- **Auditor**: auditor@auditarest.com / password

## 📝 Uso

1. Inicia sesión con las credenciales proporcionadas
2. Navega por el panel de administración para gestionar restaurantes y usuarios
3. Crea nuevas auditorías para los restaurantes
4. Completa las preguntas de la auditoría
5. Genera informes en PDF y envíalos por correo electrónico

## 🛠️ Tecnologías utilizadas

- **Backend**: Laravel 10.x
- **Frontend**: 
  - TailwindCSS para estilos
  - Vite como bundler
  - Alpine.js para interacciones
- **Base de datos**: SQLite (configurable a MySQL/PostgreSQL)
- **Generación de PDF**: DomPDF
- **Autenticación**: Laravel Breeze

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más información.

