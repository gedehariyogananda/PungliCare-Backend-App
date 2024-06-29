<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/PungliCare.png')">
    <!-- Sidebar Menu Items -->
    <x-maz-sidebar-item name="Dashboard" :link="route('dashboard')" icon="bi bi-grid-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item name="Pemantauan Map" :link="route('pemantauan')" icon="bi bi-map-fill"></x-maz-sidebar-item>
    <x-maz-sidebar-item name="Semua Laporan" :link="route('laporan')" icon="bi bi-file-earmark-text-fill">
    </x-maz-sidebar-item>
    <x-maz-sidebar-item name="Logout" :link="route('logout')" icon="bi bi-gear-fill"></x-maz-sidebar-item>
    {{-- <x-maz-sidebar-item name="Keluar" :link="route('logout')" icon="bi bi-box-arrow-right"></x-maz-sidebar-item>
    --}}
</x-maz-sidebar>