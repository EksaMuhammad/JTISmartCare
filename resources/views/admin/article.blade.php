@extends('appadmin')

@section('title', 'Kelola Artikel')

@section('styles')
<style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family:'Poppins', sans-serif;
    }

    body{
        background:#f4f5f9;
    }

    .article-wrapper{
        padding:32px;
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    .article-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:28px;
        gap:20px;
    }

    .article-title h2{
        font-size:38px;
        font-weight:700;
        color:#1f1f2e;
        margin-bottom:6px;
    }

    .article-title p{
        color:#7a7a8c;
        font-size:15px;
    }

    .btn-add{
        background:#355872;
        color:white;
        border:none;
        padding:14px 24px;
        border-radius:12px;
        font-size:15px;
        font-weight:600;
        display:flex;
        align-items:center;
        gap:10px;
        cursor:pointer;
        transition:.3s;
        box-shadow:355872;
    }

    .btn-add:hover{
        background:#2c4a5e;
        transform:translateY(-2px);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE CARD
    |--------------------------------------------------------------------------
    */

    .article-card{
        background:white;
        border-radius:20px;
        overflow:hidden;
        box-shadow:0 8px 24px rgba(0,0,0,.04);
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    thead th{
        padding:22px 28px;
        text-transform:uppercase;
        font-size:13px;
        color:#777;
        font-weight:700;
        background:#fafafa;
    }

    tbody td{
        padding:22px 28px;
        border-top:1px solid #f1f1f1;
        vertical-align:middle;
    }

    /*
    |--------------------------------------------------------------------------
    | ARTICLE INFO
    |--------------------------------------------------------------------------
    */

    .article-info{
        display:flex;
        align-items:center;
        gap:16px;
    }

    .article-info img{
        width:100px;
        height:60px;
        object-fit:cover;
        border-radius:10px;
    }

    .article-info h4{
        font-size:15px;
        font-weight:600;
        color:#324b67;
        line-height:1.5;
        margin:0;
    }

    /*
    |--------------------------------------------------------------------------
    | BADGE
    |--------------------------------------------------------------------------
    */

    .badge{
        padding:7px 14px;
        border-radius:999px;
        font-size:12px;
        font-weight:600;
    }

    .badge-edukasi{
        background:#eef2ff;
        color:#4f46e5;
    }

    .badge-tips{
        background:#f5e8ff;
        color:#355872;
    }

    .status-publish{
        background:#e9f9ee;
        color:#16a34a;
    }

    .status-draft{
        background:#fff5dd;
        color:#d97706;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTION BUTTON
    |--------------------------------------------------------------------------
    */

    .action-group{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .btn-action{
        width:38px;
        height:38px;
        border-radius:10px;
        border:1px solid #ececec;
        display:flex;
        align-items:center;
        justify-content:center;
        background:white;
        cursor:pointer;
        transition:.3s;
    }

    .btn-action:hover{
        transform:scale(1.08);
    }

    .btn-action i{
        font-size:16px;
    }

    .btn-edit{
        color:#4b5563;
    }

    .btn-delete{
        color:#ef4444;
    }

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    .custom-input{
        height:55px;
        border-radius:14px;
        border:1px solid #d8dbe7;
        padding:0 18px;
        box-shadow:none;
    }

    textarea.custom-input{
        height:auto;
        padding:18px;
    }

    .custom-input:focus{
        border-color:#4f46e5;
        box-shadow:none;
    }

    .btn-save{
        background:#355872;
        color:white;
        border:none;
        padding:12px 28px;
        border-radius:999px;
        font-weight:600;
    }

    .btn-save:hover{
        background:#2c4a5e;
    }

    /*
    |--------------------------------------------------------------------------
    | FOOTER
    |--------------------------------------------------------------------------
    */

    .table-footer{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:20px 24px;
        border-top:1px solid #f1f1f1;
    }

    .table-footer p{
        margin:0;
        color:#888;
        font-size:14px;
    }

    .pagination{
        display:flex;
        gap:10px;
    }

    .pagination button{
        border:1px solid #e5e7eb;
        background:white;
        padding:8px 14px;
        border-radius:8px;
        cursor:pointer;
    }

    .pagination .active{
        background:#355872;
        color:white;
        border:none;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media(max-width:992px){

        .article-header{
            flex-direction:column;
            align-items:flex-start;
        }

        .article-card{
            overflow-x:auto;
        }

        table{
            min-width:900px;
        }

    }

</style>
@endsection


@section('content')

@php

    $articles = [

        [
            'id' => 1,
            'title' => 'Ciri-Ciri Burnout dan Cara Mengatasinya',
            'category' => 'Edukasi',
            'date' => '28 Mei 2024',
            'status' => 'Publish',
            'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200',
            'content' => 'Artikel burnout mahasiswa.'
        ],

        [
            'id' => 2,
            'title' => 'Tips Jaga Work-Life Balance',
            'category' => 'Tips',
            'date' => '25 Mei 2024',
            'status' => 'Publish',
            'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=1200',
            'content' => 'Tips menjaga work life balance.'
        ],

        [
            'id' => 3,
            'title' => '5 Tips Jitu Atasi Burnout Mahasiswa',
            'category' => 'Edukasi',
            'date' => '20 Mei 2024',
            'status' => 'Draft',
            'image' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=1200',
            'content' => 'Tips mengatasi burnout.'
        ],

    ];

@endphp


<div class="article-wrapper">

    {{-- HEADER --}}
    <div class="article-header">

        <div class="article-title">
            <h2>Artikel Edukasi & Tips</h2>
            <p>
                Kelola artikel edukasi yang ditampilkan pada halaman landing page.
            </p>
        </div>

        {{-- BUTTON ADD --}}
        <button
            class="btn-add" id="addArticleBtn" data-bs-toggle="modal" data-bs-target="#articleModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Artikel
        </button>

    </div>

    {{-- TABLE --}}
    <div class="article-card">

        <table>

            <thead>

                <tr>
                    <th>Artikel</th>
                    <th>Kategori</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>

            <tbody>

                @foreach($articles as $article)

                    <tr>

                        {{-- ARTICLE --}}
                        <td>

                            <div class="article-info">

                                <img src="{{ $article['image'] }}" alt="">

                                <h4>
                                    {{ $article['title'] }}
                                </h4>

                            </div>

                        </td>

                        {{-- CATEGORY --}}
                        <td>

                            <span class="badge
                                {{ $article['category'] == 'Edukasi'
                                    ? 'badge-edukasi'
                                    : 'badge-tips' }}">

                                {{ $article['category'] }}

                            </span>

                        </td>

                        {{-- DATE --}}
                        <td>
                            {{ $article['date'] }}
                        </td>

                        {{-- STATUS --}}
                        <td>

                            <span class="badge
                                {{ $article['status'] == 'Publis'
                                    ? 'status-publish'
                                    : 'status-draft' }}">

                                {{ $article['status'] }}

                            </span>

                        </td>

                        {{-- ACTION --}}
                        <td>

                            <div class="action-group">

                                {{-- EDIT --}}
                                <button
                                    class="btn-action btn-edit editArticleBtn"

                                    data-id="{{ $article['id'] }}"
                                    data-title="{{ $article['title'] }}"
                                    data-category="{{ $article['category'] }}"
                                    data-content="{{ $article['content'] }}"

                                    data-bs-toggle="modal"
                                    data-bs-target="#articleModal">

                                    <i class="bi bi-pencil"></i>

                                </button>

                                {{-- DELETE --}}
                                <button
                                    class="btn-action btn-delete"

                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        {{-- FOOTER --}}
        <div class="table-footer">

            <p>
                MENAMPILKAN {{ count($articles) }} ARTIKEL
            </p>

            <div class="pagination">

                <button>Previous</button>

                <button class="active">1</button>

                <button>Next</button>

            </div>

        </div>

    </div>

</div>


{{-- MODAL ADD / EDIT --}}
<div class="modal fade" id="articleModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content rounded-4 border-0">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4">

                <div>

                    <h4 class="fw-bold mb-1 modal-title-custom">
                        Tambah Artikel
                    </h4>

                    <p class="text-muted mb-0">
                        Lengkapi informasi artikel.
                    </p>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            {{-- FORM --}}
            <form
                id="articleForm"
                action="{{ route('admin.articles.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <input
                    type="hidden"
                    id="article_id"
                    name="article_id">

                <div class="modal-body px-4">

                    <div class="row">

                        {{-- TITLE --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-semibold">
                                Judul
                            </label>

                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="form-control custom-input"
                                placeholder="Masukkan judul artikel...">

                        </div>

                        {{-- CATEGORY --}}
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-semibold">
                                Kategori
                            </label>

                            <select
                                id="category"
                                name="category"
                                class="form-select custom-input">

                                <option value="">
                                    Pilih Kategori
                                </option>

                                <option value="Edukasi">
                                    Edukasi
                                </option>

                                <option value="Tips">
                                    Tips
                                </option>

                            </select>

                        </div>

                        {{-- LINK --}}
                        <div class="col-12 mb-4">

                            <label class="form-label fw-semibold">
                                Link Artikel
                            </label>

                            <input
                                type="text"
                                id="link"
                                name="link"
                                class="form-control custom-input"
                                placeholder="https://domain.com/artikel">

                        </div>

                        {{-- IMAGE --}}
                        <div class="col-12 mb-4">

                            <label class="form-label fw-semibold">
                                Upload Gambar
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control custom-input">

                        </div>

                        {{-- CONTENT --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Isi Artikel
                            </label>

                            <textarea
                                id="content"
                                name="content"
                                rows="6"
                                class="form-control custom-input"
                                placeholder="Tulis isi artikel..."></textarea>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn-save">

                        Simpan Artikel

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- DELETE MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <div class="modal-body text-center p-5">

                <i class="bi bi-trash text-danger"
                   style="font-size:55px"></i>

                <h4 class="fw-bold mt-4">
                    Hapus Artikel?
                </h4>

                <p class="text-muted">
                    Artikel yang dihapus tidak dapat dikembalikan.
                </p>

                <div class="d-flex justify-content-center gap-3 mt-4">

                    <button
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        class="btn btn-danger rounded-pill px-4">

                        Hapus

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const addArticleBtn =
        document.getElementById('addArticleBtn');

    const editButtons =
        document.querySelectorAll('.editArticleBtn');

    const modalTitle =
        document.querySelector('.modal-title-custom');

    /*
    |--------------------------------------------------------------------------
    | INPUT
    |--------------------------------------------------------------------------
    */

    const articleId =
        document.getElementById('article_id');

    const titleInput =
        document.getElementById('title');

    const categoryInput =
        document.getElementById('category');

    const contentInput =
        document.getElementById('content');

    /*
    |--------------------------------------------------------------------------
    | ADD ARTICLE
    |--------------------------------------------------------------------------
    */

    addArticleBtn.addEventListener('click', () => {

        modalTitle.innerHTML =
            'Tambah Artikel';

        articleId.value = '';

        titleInput.value = '';
        categoryInput.value = '';
        contentInput.value = '';

    });

    /*
    |--------------------------------------------------------------------------
    | EDIT ARTICLE
    |--------------------------------------------------------------------------
    */

    editButtons.forEach(button => {

        button.addEventListener('click', function () {

            modalTitle.innerHTML =
                'Edit Artikel';

            articleId.value =
                this.dataset.id;

            titleInput.value =
                this.dataset.title;

            categoryInput.value =
                this.dataset.category;

            contentInput.value =
                this.dataset.content;

        });

    });

</script>

@endsection