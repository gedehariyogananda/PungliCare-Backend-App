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
    </style>
    @endpush

    <x-slot name="pages">Laporan</x-slot>
    <x-slot name="pagesInit">Semua Laporan</x-slot>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <section class="section">
        <div class="container">
            <div class="row">
                @foreach($semuaData as $data)
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
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($data['deskripsi_laporan'], 50)
                                    }}</p>
                                <p class="card-text"><i style="color: #FE8235;" class="bi bi-geo-alt"></i> {{
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
    </section>
</x-app-layout>