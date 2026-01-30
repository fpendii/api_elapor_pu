<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinas PUPR Kabupaten Tanah Laut | Tuntung Pandang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .pu-yellow { background-color: #FFD500; }
        .pu-blue { background-color: #003366; }
        .text-pu-blue { color: #003366; }
        .bg-tala { background-color: #f8fafc; }
    </style>
</head>
<body class="bg-tala font-sans">

    <header class="bg-white shadow-md sticky top-0 z-50 border-b-4 border-yellow-400">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-12 bg-gray-200 flex items-center justify-center text-[10px] text-center font-bold text-gray-500 rounded">
                    LOGO TALA
                </div>
                <div>
                    <h1 class="font-bold text-lg leading-none text-pu-blue">DINAS PUPR</h1>
                    <p class="text-xs font-semibold tracking-tighter text-gray-600 uppercase">Kabupaten Tanah Laut</p>
                </div>
            </div>
            <nav class="hidden md:flex space-x-6 font-medium text-gray-700 text-sm">
                <a href="#" class="hover:text-blue-900 transition">Beranda</a>
                <a href="#struktur" class="hover:text-blue-900 transition">Struktur Organisasi</a>
                <a href="#" class="hover:text-blue-900 transition">Program Kerja</a>
                <a href="#" class="hover:text-blue-900 transition">Pelaporan</a>
            </nav>
            <div class="hidden md:block">
                <span class="text-xs font-bold bg-blue-100 text-blue-800 px-3 py-1 rounded-full">#TalaKuat</span>
            </div>
        </div>
    </header>

    <section class="relative h-[500px] flex items-center text-white">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('image/kantor-pu.jpeg') }}" class="w-full h-full object-cover brightness-[0.4]" alt="Infrastruktur Tanah Laut">
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center md:text-left">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">Membangun Infrastruktur <br>Bumi <span class="text-yellow-400">Tuntung Pandang</span></h2>
            <p class="text-lg mb-8 max-w-2xl text-gray-200">Melayani pembangunan jalan, jembatan, dan tata ruang yang berkelanjutan di Kabupaten Tanah Laut, Kalimantan Selatan.</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center md:justify-start">
                <a href="#struktur" class="pu-yellow text-blue-900 px-8 py-3 rounded-md font-bold hover:bg-yellow-400 transition text-center text-sm">Lihat Struktur</a>
                <a href="#" class="bg-white/10 backdrop-blur-md border border-white/30 px-8 py-3 rounded-md font-bold hover:bg-white/20 transition text-center text-sm text-white">Portal Layanan</a>
            </div>
        </div>
    </section>

    <section id="struktur" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-yellow-600 font-bold tracking-widest uppercase text-sm">Manajemen</span>
                <h2 class="text-3xl font-bold text-pu-blue mt-2">Struktur Organisasi</h2>
                <div class="h-1 w-16 bg-yellow-400 mx-auto mt-4"></div>
            </div>

            <div class="max-w-5xl mx-auto">
                <div class="flex justify-center mb-12">
                    <div class="bg-pu-blue text-white p-6 rounded-xl shadow-xl w-72 text-center border-t-8 border-yellow-400">
                        <div class="w-20 h-20 bg-gray-300 rounded-full mx-auto mb-4 border-2 border-white overflow-hidden">
                            <i class="fas fa-user-tie text-4xl mt-4"></i>
                        </div>
                        <h4 class="font-bold text-lg">Nama Kepala Dinas</h4>
                        <p class="text-yellow-400 text-sm">Kepala Dinas PUPR</p>
                    </div>
                </div>

                <div class="flex justify-center mb-12 relative">
                     <div class="bg-slate-100 p-5 rounded-lg border border-slate-200 w-64 text-center">
                        <h5 class="font-bold text-pu-blue">Sekretariat</h5>
                        <p class="text-xs text-gray-500">Sub Bagian Perencanaan & Keuangan</p>
                     </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white p-5 rounded-lg border-l-4 border-blue-500 shadow-sm hover:shadow-md transition">
                        <h5 class="font-bold text-pu-blue text-sm mb-1">Bidang Bina Marga</h5>
                        <p class="text-xs text-gray-600 italic">Pembangunan Jalan & Jembatan</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border-l-4 border-cyan-500 shadow-sm hover:shadow-md transition">
                        <h5 class="font-bold text-pu-blue text-sm mb-1">Bidang SDA</h5>
                        <p class="text-xs text-gray-600 italic">Irigasi & Drainase</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border-l-4 border-orange-500 shadow-sm hover:shadow-md transition">
                        <h5 class="font-bold text-pu-blue text-sm mb-1">Bidang Cipta Karya</h5>
                        <p class="text-xs text-gray-600 italic">Tata Bangunan & Lingkungan</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border-l-4 border-green-500 shadow-sm hover:shadow-md transition">
                        <h5 class="font-bold text-pu-blue text-sm mb-1">Bidang Tata Ruang</h5>
                        <p class="text-xs text-gray-600 italic">Pemanfaatan Ruang Daerah</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-white py-12">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h3 class="font-bold text-xl mb-4">Dinas PUPR Tanah Laut</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Jl. Jend. Sudirman No. XX, Pelaihari<br>
                    Kabupaten Tanah Laut, Kalimantan Selatan 70814<br>
                    <span class="block mt-2 font-bold"><i class="fas fa-phone mr-2"></i> (0512) XXX XXX</span>
                </p>
            </div>
            <div>
                <h3 class="font-bold text-xl mb-4">Aplikasi & Layanan</h3>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><a href="#" class="hover:text-yellow-400"><i class="fas fa-chevron-right text-[10px] mr-2"></i>Sistem Informasi Jalan & Jembatan</a></li>
                    <li><a href="#" class="hover:text-yellow-400"><i class="fas fa-chevron-right text-[10px] mr-2"></i>E-Planning Kabupaten</a></li>
                    <li><a href="#" class="hover:text-yellow-400"><i class="fas fa-chevron-right text-[10px] mr-2"></i>Lapor Tala!</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-xl mb-4">Kalsel Bergerak</h3>
                <p class="text-xs text-gray-500 mb-4 italic">Bersama membangun infrastruktur daerah yang lebih tangguh dan nyaman bagi masyarakat Pelaihari dan sekitarnya.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-400 hover:text-blue-900 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-yellow-400 hover:text-blue-900 transition"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-12 pt-8 border-t border-gray-800 text-center text-gray-500 text-xs">
            &copy; 2026 Dinas PUPR Kabupaten Tanah Laut. Semua Hak Dilindungi.
        </div>
    </footer>

</body>
</html>
