<x-app-layout>
    @section('namefitur', 'Pendukung')
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
    <x-slot name="pagesInit">Pendukung</x-slot>


    <section class="section">
        <div class="row container">
            <table class="table table-responsive bg-white">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Email Pendukung</th>
                        <th scope="col">Nama Pendukung</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($pendukungs->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Belum ada pendukung</td>
                    </tr>
                    @else
                    @foreach ($pendukungs as $pendukung)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pendukung->user->email }}</td>
                        <td>{{ $pendukung->user->nama }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>

        </div>
    </section>
</x-app-layout>