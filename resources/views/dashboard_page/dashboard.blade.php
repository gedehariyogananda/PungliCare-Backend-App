<x-app-layout>
    @section('namefitur', 'Dashboard')
    @push('styles')
    <style>
        .box {
            display: flex;
            align-items: center;
            overflow: hidden;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 12px;
        }

        .custom-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 5px;
            width: 40px;
            height: 40px;
            background-color: #F4F7FE;
            border-radius: 50%;
            margin-right: 10px;
        }

        
        .custom-card-icon i {
            color: #ff6f61;
            line-height: 1;
        }

        .custom-card-title {
            font-size: 9px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .custom-card-number {
            font-size: 10px;
            color: #000;
            font-weight: bold;
        }
    </style>
    @endpush

    <x-slot name="pages">Dashboard</x-slot>
    <x-slot name="pagesInit">Selamat datang Admin!</x-slot>

    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="box">
                        <div class="custom-card-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="custom-card-body">
                            <div class="custom-card-title">Semua Laporan</div>
                            <div class="custom-card-number">{{ $semuaLaporan }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="custom-card-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="custom-card-body">
                            <div class="custom-card-title">Laporan yang perlu diatasi</div>
                            <div class="custom-card-number">{{ $perluDiatasi }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box">
                        <div class="custom-card-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="custom-card-body">
                            <div class="custom-card-title">Laporan yang sudah diatasi</div>
                            <div class="custom-card-number">{{ $sudahDiatasi }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container my-5">
               <div class="row rounded-lg">
                <div class="col-lg-6 bg-white">
                    <canvas id="laporanChart" width="400" height="400"></canvas>
                </div>
                <div class="col-lg-6 bg-white">
                    <canvas id="statistik" width="400" height="400"></canvas>
                </div>
               </div>
               <div class="container my-7 rounded-xl bg-white p-4" >
                    <h4>Laporan Yang Perlu Diatasi</h4>
                    @foreach ( $laporan as $i )
                    <div class="row my-3">
                        <div class="col-lg-3 w-72 h-64 rounded-lg object-cover" style="background-image: url({{ asset("storage/".$i->BuktiLaporan[0]->bukti_laporan) }}); position: relative;">
                            <span class="position-absolute top-2 end-2 bg-white text-red-600 border-red-600 border-2 p-2 rounded-full font-semibold">Perlu Diatasi</span>
                        </div>
                    </div>                 
                    @endforeach
               </div>
            </div>
        </div>
    </section>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($months->pluck('nama_bulan'));
        const data = @json($months->pluck('jumlah_laporan'));

        const perluDukungan = @json($perluDukungan);
        const perluDiatasi = @json($perluDiatasi);
        const sedangDiatasi = @json($sedangDiatasi);
        const sudahDiatasi = @json($sudahDiatasi);

        const ctx = document.getElementById('laporanChart').getContext('2d');
        const chart = document.getElementById('statistik').getContext('2d');

        const chartStatistik = new Chart(chart, {
            type: 'bar',
            data: {
                labels: ["Perlu Dukungan", "Perlu Diatasi", "Sedang Diatasi", "Sudah Teratasi"],
                datasets: [{
                    label: "Statistik Laporan",
                    data: [perluDukungan, perluDiatasi, sedangDiatasi, sudahDiatasi],
                    backgroundColor: [
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const laporanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return index % 2 === 0 ? this.getLabelForValue(value) : '';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>