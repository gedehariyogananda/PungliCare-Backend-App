<x-app-layout>
    @section('namefitur', 'Dashboard')
    @push('styles')
    <style>
        .status-label {
            position: absolute;
            top: 8px;
            right: 8px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            background-color: #FFFFFF;
            border: 1px solid;
        }

        .status-perlu-dukungan {
            color: #ffc107;
            border-color: #ffeeba;
        }

        .status-perlu-diatasi {
            color: #e51c23;
            border-color: #f5c6cb;
        }

        .status-sedang-diatasi {
            color: #17a2b8;
            border-color: #bee5eb;
        }

        .status-sudah-teratasi {
            color: #28a745;
            border-color: #c3e6cb;
        }

        .status-unknown {
            color: #212529;
            border-color: #ccc;
        }

        .card-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .card-text {
            font-size: 8px;
            margin-bottom: 0.25rem;
        }

        .card-body p i {
            font-size: 8px;
        }

        .img-fluid {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

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
    <x-slot name="pagesInit">Selamat datang, Dishub Surabaya !</x-slot>

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
                <div class="container my-7 rounded-xl">
                    <h4>Laporan Yang Perlu Diatasi</h4>
                    {{-- @foreach ( $laporan as $i ) --}}
                    <div class="container">
                        <div class="row">
                            @foreach($laporan as $data)
                            @php
                            $statusClass = '';
                            $statusText = '';
                            switch ($data['status_laporan']) {
                            case 'perlu-dukungan':
                            $statusClass = 'status-perlu-dukungan';
                            $statusText = 'Perlu Dukungan';
                            break;
                            case 'perlu-diatasi':
                            $statusClass = 'status-perlu-diatasi';
                            $statusText = 'Perlu Diatasi';
                            break;
                            case 'sedang-diatasi':
                            $statusClass = 'status-sedang-diatasi';
                            $statusText = 'Sedang Diatasi';
                            break;
                            case 'sudah-teratasi':
                            $statusClass = 'status-sudah-teratasi';
                            $statusText = 'Sudah Teratasi';
                            break;
                            default:
                            $statusClass = 'status-unknown';
                            $statusText = 'Status Tidak Diketahui';
                            }
                            @endphp
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                <a href="{{ route('laporan.detail',['id'=>$data['id']]) }}">
                                    <div class="card">
                                        <div style="position: relative;">
                                            <img class="card-img-top img-fluid" src="{{ $data['image_laporan'] }}"
                                                alt="Laporan Image">
                                            <span class="status-label {{ $statusClass }}">{{ $statusText }}</span>
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $data['judul_laporan'] }}</h6>
                                            <p class="card-text">{{
                                                \Illuminate\Support\Str::limit($data['deskripsi_laporan'], 50)
                                                }}</p>
                                            <p class="card-text"><i style="color: #FE8235;" class="bi bi-geo-alt"></i>
                                                {{
                                                \Illuminate\Support\Str::limit($data['alamat'], 50) }}</p>
                                            <p class="card-text"><i style="color: #FE8235;" class="bi bi-person"></i> {{
                                                $data['jumlahPendukung'] }} Orang mendukung</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- @endforeach --}}
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