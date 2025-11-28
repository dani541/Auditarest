<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuditaRest - Gestión de Auditorías de Restaurantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">Audita<span class="text-purple-600">Rest</span></span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Características</a>
                    <a href="#how-it-works" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">¿Cómo funciona?</a>
                    <a href="#contact" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Contacto</a>
                    <a href="{{ route('login') }}" class="ml-8 whitespace-nowrap inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Acceder
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-gradient">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                <span class="block">Gestión de Auditorías</span>
                <span class="block text-indigo-200">para Restaurantes</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-indigo-100 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Optimiza los procesos de auditoría en tu cadena de restaurantes con nuestra solución todo en uno.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                        Comenzar ahora
                    </a>
                </div>
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                    <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10">
                        Saber más
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Características principales
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Todo lo que necesitas para gestionar auditorías de manera eficiente
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <i class="fas fa-clipboard-check text-xl"></i>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Auditorías Personalizables</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Crea formularios de auditoría personalizados según las necesidades específicas de cada restaurante.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Reportes en Tiempo Real</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Visualiza los resultados de las auditorías en tiempo real con gráficos y estadísticas detalladas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="pt-6">
                        <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8">
                            <div class="-mt-6">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white">
                                    <i class="fas fa-users-cog text-xl"></i>
                                </div>
                                <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Gestión de Usuarios</h3>
                                <p class="mt-5 text-base text-gray-500">
                                    Controla los accesos con diferentes roles: administradores, auditores y usuarios estándar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div id="how-it-works" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    ¿Cómo funciona?
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Sencillo proceso para optimizar tus auditorías
                </p>
            </div>

            <div class="mt-16">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <div class="relative">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white text-xl">1</div>
                        <p class="mt-4 text-lg leading-6 font-medium text-gray-900">Configura tu cuenta</p>
                        <p class="mt-2 text-base text-gray-500">
                            Crea tu cuenta y configura los parámetros iniciales de tu cadena de restaurantes.
                        </p>
                    </div>

                    <div class="mt-10 lg:mt-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white text-xl">2</div>
                        <p class="mt-4 text-lg leading-6 font-medium text-gray-900">Realiza auditorías</p>
                        <p class="mt-2 text-base text-gray-500">
                            Usa nuestra aplicación móvil o web para completar las auditorías en cada local.
                        </p>
                    </div>

                    <div class="mt-10 lg:mt-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white text-xl">3</div>
                        <p class="mt-4 text-lg leading-6 font-medium text-gray-900">Analiza y mejora</p>
                        <p class="mt-2 text-base text-gray-500">
                            Revisa los informes y métricas para identificar áreas de mejora.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                <span class="block">¿Listo para comenzar?</span>
                <span class="block text-indigo-600">Mejora tus estándares de calidad hoy mismo.</span>
            </h2>
            <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Comenzar ahora
                    </a>
                </div>
                <div class="ml-3 inline-flex rounded-md shadow">
                    <a href="#contact" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50">
                        Contáctanos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="bg-gray-50 py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Contáctanos
                </h2>
                <p class="mt-4 text-lg leading-6 text-gray-500">
                    ¿Tienes preguntas? Estamos aquí para ayudarte.
                </p>
            </div>
            <div class="mt-12">
                <div class="bg-white py-8 px-6 shadow rounded-lg sm:px-10">
                    <form action="#" method="POST" class="mb-0 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Mensaje</label>
                            <div class="mt-1">
                                <textarea id="message" name="message" rows="4" required class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Enviar mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
            <nav class="-mx-5 -my-2 flex flex-wrap justify-center" aria-label="Footer">
                <div class="px-5 py-2">
                    <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                        Sobre nosotros
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                        Términos y condiciones
                    </a>
                </div>
                <div class="px-5 py-2">
                    <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                        Política de privacidad
                    </a>
                </div>
            </nav>
            <p class="mt-8 text-center text-base text-gray-400">
                &copy; 2023 AuditaRest. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
