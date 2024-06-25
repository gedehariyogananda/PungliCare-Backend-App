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
    <x-slot name="pagesInit">Comment Laporan</x-slot>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- modal create --}}
    <div class="modal fade" id="createComment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambahkan Komentar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('comment.create',$laporanId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Masukkan Komentar</label>
                            <textarea class="form-control" rows="4" name="comment_laporan" id="comment" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Tambahkan bukti berupa gambar</label>
                            <input class="form-control" multiple type="file" id="bukti" name="bukti_comments[]">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- akhir modal --}}

    

    <section class="section">
        <button type="button" class="btn btn-primary p-3 mb-4" 
        data-bs-toggle="modal" data-bs-target="#createComment">Tambahkan Komentar</button>
        <div class="row container">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Isi Komentar</th>
                        <th scope="col">User yang komentar</th>
                        <th scope="col">Bukti Komemtar</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($comments->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data</td>
                        </tr>
                    @else
                        @foreach ($comments as $comment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $comment->comment_laporan }}</td>
                                <td>{{ $comment->user->nama }}</td>
                                <td class="flex">
                                    @if ($comment->buktiComment->isEmpty())
                                        Tidak menyertakan bukti
                                    @else
                                        @foreach ($comment->buktiComment as $i)
                                            <button type="button" class="btn btn-success p-1 mb-4 my-auto mx-2"
                                                data-bs-toggle="modal" data-bs-target="#buktiModal{{ $loop->parent->iteration }}{{ $loop->iteration }}">
                                                <span>Bukti {{ $loop->iteration }}</span>
                                            </button>
                                            
                                            <!-- Modal -->
                                            <div class="modal fade" id="buktiModal{{ $loop->parent->iteration }}{{ $loop->iteration }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel"> Bukti</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <img src="{{ asset("storage/".$i->bukti_comment) }}" alt="Bukti" class="w-full h-full"  class="img-fluid">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            @if (!$loop->last)
                                                |
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            
        </div>
    </section>
</x-app-layout>