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

        .comment-container {
            display: flex;
            flex-direction: column;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
            position: relative;
        }

        .comment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .comment-header-left {
            display: flex;
            align-items: center;
        }

        .comment-header img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .comment-body {
            margin-top: 10px;
            margin-left: 50px;
        }

        .comment-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-left: 50px;
        }

        .comment-images {
            display: flex;
            flex-wrap: wrap;
        }

        .comment-images img {
            margin-right: 10px;
            margin-top: 10px;
            cursor: pointer;
            max-width: 100px;
            max-height: 100px;
        }

        .modal-img {
            max-width: 100%;
            max-height: 100%;
        }

        .pin-icon {
            color: #ffc107;
            font-size: 20px;
        }

        .comment-box {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .comment-box textarea {
            width: 100%;
            resize: none;
            margin-right: 10px;
        }

        .comment-box .btn-add-photo {
            margin-right: 10px;
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


    {{-- Comment Box for the logged-in user --}}
    <div class="comment-box">
        <form action="{{ route('comment.create', $laporanId) }}" method="POST" enctype="multipart/form-data"
            class="d-flex w-100">
            @csrf
            <textarea class="form-control" rows="1" name="comment_laporan" id="comment"
                placeholder="Tambahkan Komentar..." required></textarea>
            <input type="file" name="bukti_comments[]" multiple>
        </form>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Post</button>
    <br><br>

    {{-- modal create --}}
    <div class="modal fade" id="createComment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambahkan Komentar</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('comment.create', $laporanId) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Masukkan Komentar</label>
                            <textarea class="form-control" rows="4" name="comment_laporan" id="comment"
                                required></textarea>
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
        <div class="container">
            @if ($comments->isEmpty())
            <div class="text-center">Belum ada data</div>
            @else
            {{-- Filter and separate comments --}}
            @php
            $pinnedComments = $comments->filter(function ($comment) {
            return $comment->user_id == auth()->id();
            });
            $otherComments = $comments->filter(function ($comment) {
            return $comment->user_id != auth()->id();
            });
            @endphp

            {{-- Pinned comments --}}
            @foreach ($pinnedComments as $comment)
            <div class="comment-container">
                <div class="comment-header">
                    <div class="comment-header-left">
                        <img src="{{ asset('images/dishub/dishub.png') }}" alt="User Image">
                        <div>
                            <h5>{{ $comment->user->nama }}</h5>
                            <small>{{ $comment->created_at->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                    <i class="pin-icon bi bi-pin-angle-fill"></i>
                </div>
                <div class="comment-body">
                    <p>{{ $comment->comment_laporan }}</p>
                </div>
                <div class="comment-footer">
                    <div class="comment-images">
                        @foreach ($comment->buktiComment as $i)
                        <img src="{{ asset('storage/' . $i->bukti_comment) }}" alt="Bukti" data-bs-toggle="modal"
                            data-bs-target="#buktiModal{{ $comment->id }}{{ $loop->iteration }}">
                        <!-- Modal -->
                        <div class="modal fade" id="buktiModal{{ $comment->id }}{{ $loop->iteration }}" tabindex="-1"
                            aria-labelledby="buktiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="buktiModalLabel">Bukti</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/' . $i->bukti_comment) }}" alt="Bukti"
                                            class="modal-img">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Other comments --}}
            @foreach ($otherComments as $comment)
            <div class="comment-container">
                <div class="comment-header">
                    <div class="comment-header-left">
                        <img src="{{ $comment->user->profile_photo_path ? asset('storage/' . $comment->user->profile_photo_path) : asset('images/default/default.png') }}"
                            alt="User Image">
                        <div>
                            <h5>{{ $comment->user->nama }}</h5>
                            <small>{{ $comment->created_at->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                </div>
                <div class="comment-body">
                    <p>{{ $comment->comment_laporan }}</p>
                </div>
                <div class="comment-footer">
                    <div class="comment-images">
                        @foreach ($comment->buktiComment as $i)
                        <img src="{{ asset('storage/' . $i->bukti_comment) }}" alt="Bukti" data-bs-toggle="modal"
                            data-bs-target="#buktiModal{{ $comment->id }}{{ $loop->iteration }}">
                        <!-- Modal -->
                        <div class="modal fade" id="buktiModal{{ $comment->id }}{{ $loop->iteration }}" tabindex="-1"
                            aria-labelledby="buktiModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="buktiModalLabel">Bukti</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/' . $i->bukti_comment) }}" alt="Bukti"
                                            class="modal-img">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <form action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </section>

    {{-- modal add photo --}}
    <div class="modal fade" id="addPhotoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addPhotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPhotoModalLabel">Tambahkan Foto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPhotoForm">
                        <div class="mb-3">
                            <label for="bukti" class="form-label">Tambahkan bukti berupa gambar</label>
                            <input class="form-control" multiple type="file" id="bukti" name="bukti_comments[]">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- akhir modal --}}
</x-app-layout>