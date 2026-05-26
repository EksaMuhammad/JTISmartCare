@extends('appadmin')

@section('title', 'Kelola Knowledge Base')

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

    .kb-wrapper{
        padding:32px;
    }

    /* HEADER */
    .kb-header{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:28px;
        gap:20px;
    }

    .kb-title h2{
        font-size:38px;
        font-weight:700;
        color:#1f1f2e;
        margin-bottom:6px;
    }

    .kb-title p{
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
    }

    .btn-add:hover{
        background:#2c4a5e;
        transform:translateY(-2px);
    }

    /* TABLE CARD */
    .kb-card{
        background:white;
        border-radius:20px;
        overflow:hidden;
        box-shadow:0 8px 24px rgba(0,0,0,.04);
        overflow-x: auto;
    }

    table{
        width:100%;
        border-collapse:collapse;
        min-width: 1000px;
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
        color: #4b5563;
    }

    /* BADGE */
    .badge-code{
        background:#eef2ff;
        color:#4f46e5;
        padding:7px 14px;
        border-radius:8px;
        font-size:13px;
        font-weight:600;
    }
    
    .badge-risk{
        background:#f5e8ff;
        color:#9333ea;
        padding:7px 14px;
        border-radius:8px;
        font-size:13px;
        font-weight:600;
    }

    /* ACTION BUTTON */
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

    .btn-edit{
        color:#4b5563;
    }

    .btn-delete{
        color:#ef4444;
    }

    /* FORM */
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

    /* FOOTER */
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

    @media(max-width:992px){
        .kb-header{
            flex-direction:column;
            align-items:flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="kb-wrapper">

    {{-- HEADER --}}
    <div class="kb-header">
        <div class="kb-title">
            <h2>Knowledge Base</h2>
            <p>
                Kelola aturan IF-THEN untuk Sistem Pakar Diagnosis Burnout.
            </p>
        </div>

        {{-- BUTTON ADD --}}
        <button class="btn-add" id="addRuleBtn" data-bs-toggle="modal" data-bs-target="#ruleModal">
            <i class="bi bi-plus-lg"></i>
            Tambah Rule
        </button>
    </div>

    {{-- TABLE --}}
    <div class="kb-card">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kondisi (IF)</th>
                    <th>Hasil (THEN)</th>
                    <th>CF</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules ?? [] as $rule)
                    <tr>
                        <td>
                            <span class="badge-code">{{ $rule->rule_code }}</span>
                        </td>
                        <td style="max-width: 300px;">
                            <strong>Teks:</strong> {{ $rule->kondisi }} <br>
                            <small class="text-muted"><strong>JSON:</strong> {{ json_encode($rule->kondisi_json) }}</small>
                        </td>
                        <td>
                            <span class="badge-risk">{{ $rule->hasil_risiko }}</span>
                        </td>
                        <td>
                            <strong>{{ $rule->certainty_factor }}</strong>
                        </td>
                        <td>
                            <div class="action-group">
                                {{-- EDIT --}}
                                <button class="btn-action btn-edit editRuleBtn"
                                    data-id="{{ $rule->id }}"
                                    data-code="{{ $rule->rule_code }}"
                                    data-kondisi="{{ $rule->kondisi }}"
                                    data-json="{{ json_encode($rule->kondisi_json) }}"
                                    data-hasil="{{ $rule->hasil_risiko }}"
                                    data-cf="{{ $rule->certainty_factor }}"
                                    data-rekomendasi="{{ $rule->rekomendasi }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ruleModal">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                {{-- DELETE --}}
                                <button class="btn-action btn-delete deleteRuleBtn"
                                    data-id="{{ $rule->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada aturan (rule) yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- FOOTER --}}
        <div class="table-footer">
            <p>
                MENAMPILKAN {{ count($rules ?? []) }} ATURAN
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
<div class="modal fade" id="ruleModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4">
                <div>
                    <h4 class="fw-bold mb-1 modal-title-custom">Tambah Rule</h4>
                    <p class="text-muted mb-0">Lengkapi informasi aturan IF-THEN.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- FORM --}}
            <form id="ruleForm" action="{{ route('admin.knowledge.store') }}" method="POST">
                @csrf
                <input type="hidden" id="rule_id" name="rule_id">

                <div class="modal-body px-4">
                    <div class="row">
                        {{-- RULE CODE --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Kode Rule</label>
                            <input type="text" id="rule_code" name="rule_code" class="form-control custom-input" placeholder="Contoh: R01" required>
                        </div>

                        {{-- HASIL RISIKO --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Hasil Risiko (THEN)</label>
                            <input type="text" id="hasil_risiko" name="hasil_risiko" class="form-control custom-input" placeholder="Contoh: Burnout Berat" required>
                        </div>
                        
                        {{-- CERTAINTY FACTOR --}}
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Certainty Factor (CF)</label>
                            <input type="number" step="0.01" id="certainty_factor" name="certainty_factor" class="form-control custom-input" placeholder="Contoh: 0.8" required>
                        </div>

                        {{-- KONDISI TEKS --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Kondisi Teks (IF)</label>
                            <textarea id="kondisi" name="kondisi" rows="4" class="form-control custom-input" placeholder="Contoh: Jika Kelelahan Tinggi dan Depersonalisasi Tinggi..." required></textarea>
                        </div>
                        
                        {{-- KONDISI JSON --}}
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Kondisi JSON</label>
                            <textarea id="kondisi_json" name="kondisi_json" rows="4" class="form-control custom-input" placeholder='Contoh: {"Kelelahan":"Tinggi", "Depersonalisasi":"Tinggi"}' required></textarea>
                            <small class="text-muted">Gunakan format JSON yang valid.</small>
                        </div>

                        {{-- REKOMENDASI --}}
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">Rekomendasi</label>
                            <textarea id="rekomendasi" name="rekomendasi" rows="4" class="form-control custom-input" placeholder="Tuliskan rekomendasi untuk hasil diagnosis ini..." required></textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-save">Simpan Rule</button>
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
                <i class="bi bi-trash text-danger" style="font-size:55px"></i>
                <h4 class="fw-bold mt-4">Hapus Rule?</h4>
                <p class="text-muted">Aturan yang dihapus tidak dapat dikembalikan dan mungkin memengaruhi sistem diagnosis.</p>
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const addRuleBtn = document.getElementById('addRuleBtn');
    const editButtons = document.querySelectorAll('.editRuleBtn');
    const deleteButtons = document.querySelectorAll('.deleteRuleBtn');
    const modalTitle = document.querySelector('.modal-title-custom');
    
    const ruleForm = document.getElementById('ruleForm');
    const deleteForm = document.getElementById('deleteForm');

    const ruleId = document.getElementById('rule_id');
    const ruleCode = document.getElementById('rule_code');
    const hasilRisiko = document.getElementById('hasil_risiko');
    const certaintyFactor = document.getElementById('certainty_factor');
    const kondisi = document.getElementById('kondisi');
    const kondisiJson = document.getElementById('kondisi_json');
    const rekomendasi = document.getElementById('rekomendasi');

    addRuleBtn.addEventListener('click', () => {
        modalTitle.innerHTML = 'Tambah Rule';
        ruleForm.action = "{{ route('admin.knowledge.store') }}";
        
        ruleId.value = '';
        ruleCode.value = '';
        hasilRisiko.value = '';
        certaintyFactor.value = '';
        kondisi.value = '';
        kondisiJson.value = '';
        rekomendasi.value = '';
    });

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            modalTitle.innerHTML = 'Edit Rule';
            
            const id = this.getAttribute('data-id');
            ruleForm.action = `/admin/knowledge/${id}/update`;
            
            ruleId.value = id;
            ruleCode.value = this.getAttribute('data-code');
            hasilRisiko.value = this.getAttribute('data-hasil');
            certaintyFactor.value = this.getAttribute('data-cf');
            kondisi.value = this.getAttribute('data-kondisi');
            kondisiJson.value = this.getAttribute('data-json');
            rekomendasi.value = this.getAttribute('data-rekomendasi');
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            deleteForm.action = `/admin/knowledge/${id}/delete`;
        });
    });
</script>
@endsection
