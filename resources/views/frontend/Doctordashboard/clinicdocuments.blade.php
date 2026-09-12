@extends("frontend.include.layout")
@section('title', 'Clinic Documents & Branding - Doctor Portal')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-sm-0">Doctor Clinic Documents</h4>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px;">S.No</th>
                                    <th>Doctor</th>
                                    <th>Document Name</th>
                                    <th style="width:120px;">Image</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Updated By</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sno = 1; @endphp

                                @forelse ($documents as $doc)
                                    @php
                                        $docTypes = [
                                            'Photo'  => $doc->photo,
                                            'Sign'   => $doc->doctor_sign,
                                            'Stamp'  => $doc->clinic_stamp,
                                            'Header' => $doc->header,
                                            'Footer' => $doc->footer,
                                        ];

                                        $memberName = $doc->doctor->member->name ?? '-';
                                    @endphp

                                    @foreach ($docTypes as $label => $file)
                                        <tr>
                                            <td>{{ $sno++ }}</td>

                                            <td class="text-start">
                                                {{ $doc->doctor->name ?? 'Unknown Doctor' }}
                                            </td>

                                            <td>{{ $label }}</td>

                                            <td>
                                                @if($file)
                                                    <img src="{{ asset($file) }}"
                                                         class="img-fluid rounded border img-preview"
                                                         style="max-height:70px; cursor:pointer;"
                                                         alt="{{ $label }}"
                                                         data-img-src="{{ asset($file) }}"
                                                         data-img-title="{{ $doc->doctor->name ?? 'Unknown Doctor' }} - {{ $label }}">
                                                @else
                                                    <div class="border rounded d-flex align-items-center justify-content-center text-muted mx-auto"
                                                         style="height:60px; width:60px;">
                                                        <i class="ri-image-line fs-4"></i>
                                                    </div>
                                                @endif
                                            </td>

                                            <td>{{ $memberName }}</td>
                                            <td>{{ $doc->created_at?->format('d M Y, h:i A') ?? '-' }}</td>

                                            <td>{{ $memberName }}</td>
                                            <td>{{ $doc->updated_at?->format('d M Y, h:i A') ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="text-center py-5">
                                                <i class="ri-file-list-3-line fs-1 text-muted"></i>
                                                <p class="text-muted mt-2 mb-0">No documents have been uploaded yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white" id="imagePreviewTitle">Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center">
                    <img src="" id="imagePreviewSrc" class="img-fluid" style="max-height:90vh; max-width:100%; object-fit:contain;" alt="Preview">
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var previewModalEl = document.getElementById('imagePreviewModal');
        var previewModal = new bootstrap.Modal(previewModalEl);

        document.querySelectorAll('.img-preview').forEach(function (img) {
            img.addEventListener('click', function () {
                var src = this.getAttribute('data-img-src');
                var title = this.getAttribute('data-img-title');

                document.getElementById('imagePreviewSrc').src = src;
                document.getElementById('imagePreviewTitle').innerText = title;

                previewModal.show();
            });
        });
    });
</script>
@endsection