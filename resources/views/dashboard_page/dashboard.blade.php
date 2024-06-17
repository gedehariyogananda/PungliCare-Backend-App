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
    <x-slot name="pagesInit">Selamat datang, polsek Sukolilo!</x-slot>

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
        </div>
    </section>
</x-app-layout>