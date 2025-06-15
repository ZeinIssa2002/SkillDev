@extends('admin.layouts.app')

@section('title', 'Admin Panel - Terms and Conditions')

@section('styles')
<style>
    .terms-content {
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .ck-editor__editable {
        min-height: 400px;
    }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Terms and Conditions</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#previewModal">
        <i class="fas fa-eye me-1"></i> Preview
    </button>
</div>

<div class="terms-content">
    <form action="{{ route('admin.terms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="editor" class="form-label">Terms Content</label>
            <textarea id="editor" name="content" class="form-control">{{ $terms->content ?? '' }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save Changes
        </button>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Terms & Conditions Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($terms && $terms->content)
                    {!! $terms->content !!}
                @else
                    <div class="alert alert-warning">No content available for preview</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- CKEditor JS -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', 'undo', 'redo', '|',
                    'htmlEmbed'
                ]
            },
            language: 'en'
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script src="{{ asset('js/libs/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
</script>
@endsection