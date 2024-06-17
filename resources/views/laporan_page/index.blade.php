<x-app-layout>
    @section('namefitur', 'Dashboard')
    @push('styles')
    <style>
        .img-fluid {
            width: 308px;
            height: 181px;
            object-fit: cover;
            position: relative;
        }

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
    </style>
    @endpush

    <x-slot name="pages">Laporan</x-slot>
    <x-slot name="pagesInit">Semua Laporan</x-slot>

    <section class="section">
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div style="position: relative;">
                            <img class="img-fluid" src="{{ $data['image_laporan'] }}" alt="Laporan Image">
                            <span class="status-label {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <h6>{{ $data['judul_laporan'] }}</h6>
                        <span style="color: #4A4A4A; font-size: 10px; max-height: 50px; overflow: hidden;">
                            {{ \Illuminate\Support\Str::limit($data['deskripsi_laporan'], 50) }}
                        </span>
                        <br>
                        <span style="font-size: 8px;">
                            <i style="color: #FE8235;" class="bi bi-geo-alt"></i>
                            {{ \Illuminate\Support\Str::limit($data['alamat'], 50) }}
                        </span>
                        <br>
                        <span style="font-size: 8px;">
                            <i style="color: #FE8235;" class="bi bi-person"></i>
                            {{ $data['jumlahPendukung'] }} Orang mendukung
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</x-app-layout>