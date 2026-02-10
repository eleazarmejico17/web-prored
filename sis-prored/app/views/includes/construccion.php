<div class="min-h-[70vh] flex flex-col items-center justify-center text-center p-6 animate-fade-in-up">

    <div class="relative mb-8 group">
        <div class="absolute inset-0 bg-blue-100 rounded-full animate-ping opacity-75 duration-1000"></div>

        <div
            class="relative bg-white p-8 rounded-full shadow-xl border-4 border-gray-50 flex items-center justify-center w-32 h-32">
            <i class="fas fa-laptop-code text-5xl text-primary drop-shadow-sm"></i>
        </div>

        <div
            class="absolute -top-2 -right-2 bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full shadow-md transform rotate-12 border-2 border-white">
            PRONTO
        </div>
        <div
            class="absolute bottom-0 -left-2 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-md transform -rotate-12 border-2 border-white">
            <i class="fas fa-code"></i> v1.0
        </div>
    </div>

    <h2 class="text-3xl font-bold text-gray-800 mb-3 tracking-tight">
        Estamos trabajando en esto
    </h2>

    <p class="text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">
        Esta funcionalidad del sistema <strong>ProRed</strong> está actualmente en desarrollo y pruebas de calidad.
        Estará disponible en la próxima actualización.
    </p>

    <div class="w-full max-w-sm mb-8">
        <div class="flex justify-between text-xs font-bold text-gray-400 mb-1">
            <span>Diseño</span>
            <span>Desarrollo</span>
            <span>Pruebas</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden relative shadow-inner">
            <div class="h-full bg-gradient-to-r from-primary to-blue-400 w-2/3 rounded-full relative">
                <div
                    class="absolute top-0 left-0 bottom-0 right-0 bg-gradient-to-r from-transparent via-white/30 to-transparent w-full -translate-x-full animate-[shimmer_2s_infinite]">
                </div>
            </div>
            <div class="absolute inset-0 w-full h-full"
                style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.2) 10px, rgba(255,255,255,0.2) 20px);">
            </div>
        </div>
        <p class="text-xs text-primary font-bold mt-2 text-right">Progreso: 75%</p>
    </div>

    <div class="flex flex-col sm:flex-row gap-4">
        <button onclick="history.back()" class="btn-outline px-6 py-2.5">
            <i class="fas fa-arrow-left mr-2"></i> Volver Atrás
        </button>

        <a href="index.php?view=home"
            class="btn-primary px-6 py-2.5 shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all">
            <i class="fas fa-home mr-2"></i> Ir al Dashboard
        </a>
    </div>

</div>

<style>
    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }
</style>