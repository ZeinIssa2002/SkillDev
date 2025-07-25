<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Course - {{ $course->title }}</title>
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --danger-color: #dc2626;
            --danger-hover: #b91c1c;
            --success-color: #16a34a;
            --success-hover: #15803d;
            --secondary-color: #6b7280;
            --secondary-hover: #4b5563;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            line-height: 1.5;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .form-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 2rem;
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-title {
            font-size: 1.875rem;
            font-weight: 600;
            color: #111827;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
            background-color: white;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
            width: 100%;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--secondary-hover);
        }

        .file-upload {
            position: relative;
            margin-top: 1rem;
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-upload label {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #f3f4f6;
            border: 1px dashed #d1d5db;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #374151;
            transition: all 0.2s;
        }

        .file-upload label:hover {
            background-color: #e5e7eb;
            border-color: #9ca3af;
        }

        .level-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px dashed #ccc;
        }
        
        .level-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .question-container {
            background: white;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .remove-btn {
            color: var(--danger-color);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .add-question-btn {
            margin-top: 1rem;
        }

        .questions-list {
            margin-top: 1rem;
        }

        .content-upload-container {
            margin-top: 1rem;
        }

        .option-input {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            gap: 0.5rem;
        }

        .option-input input[type="radio"] {
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
        }

        .add-option-btn {
            margin-top: 0.5rem;
        }

        .images-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .image-preview-item {
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            aspect-ratio: 1;
        }
        
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-image-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .remove-text-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 0.5rem;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: var(--danger-color) !important;
        }

        .server-errors {
            background-color: #fee2e2;
            color: var(--danger-color);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger-color);
        }

        .server-errors ul {
            margin-left: 1.5rem;
        }

        .content-type-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .content-type-option {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        
        .text-content-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .content-type-option.active {
            border-color: var(--primary-color);
            background-color: rgba(79, 70, 229, 0.1);
        }
        .content-fields {
            display: none;
        }
        .content-fields.active {
            display: block;
        }
        .preview-container {
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .preview-item {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .preview-item img, .preview-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .remove-preview {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .text-contents-container textarea {
            margin-bottom: 0.5rem;
        }
        .mt-2 {
            margin-top: 0.5rem;
        }

        .current-media {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        .current-media-item {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .current-media-item img,
        .current-media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .remove-current-media {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: var(--danger-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .file-preview {
            max-width: 100px;
            max-height: 100px;
            margin-right: 10px;
        }
        
        .file-item {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }
        
        .file-item i {
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .file-item .fa-file-pdf { color: #dc3545; }
        .file-item .fa-file-word { color: #0d6efd; }
        .file-item .fa-file-archive { color: #fd7e14; }
        
        #files-preview {
            max-height: 200px;
            overflow-y: auto;
        }

        @media (max-width: 640px) {
            .container {
                margin: 1rem auto;
            }

            .form-container {
                padding: 1.5rem;
            }

            .images-preview-container {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">Edit Course: {{ $course->title }}</h1>
            </div>

            @if ($errors->any())
                <div class="server-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="form" action="{{ route('course.update', $course->id) }}" method="POST" enctype="multipart/form-data" id="course-form">
                @csrf
                @method('PUT')

                <input type="hidden" name="deleted_levels" id="deleted-levels" value="[]">
                <input type="hidden" name="deleted_questions" id="deleted-questions" value="[]">
                <input type="hidden" name="deleted_images" id="deleted-images" value="[]">
                <input type="hidden" name="deleted_files" id="deleted-files" value="[]">
                <input type="hidden" name="deleted_thumbnail" id="deleted-thumbnail" value="0">
                <input type="hidden" name="deleted_video" id="deleted-video" value="0">

                <div class="form-group">
                    <label class="form-label" for="title">Course Title</label>
                    <input type="text" id="title" name="title" class="form-input" 
                           placeholder="Enter course title" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <select id="category" name="category_id" class="form-select" required>
                        <option value="">Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="prerequisite_id">Prerequisite Course (Optional)</label>
                    <select id="prerequisite_id" name="prerequisite_id" class="form-select">
                        <option value="">No Prerequisite</option>
                        @foreach ($instructorCourses->where('id', '!=', $course->id) as $prerequisiteCourse)
                            <option value="{{ $prerequisiteCourse->id }}" {{ old('prerequisite_id', $course->prerequisite_id) == $prerequisiteCourse->id ? 'selected' : '' }}>
                                {{ $prerequisiteCourse->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Placement Test Section -->
                <div id="placement-test-section" style="display: none;">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="enable_placement_test" id="enable_placement_test" value="1" {{ $course->placement_test ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_placement_test">
                                Enable Placement Test
                            </label>
                        </div>
                    </div>

                    <div id="placement-test-questions" style="display: {{ $course->placement_test ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="placement_pass_score" class="form-input" min="1" max="100" value="{{ $course->placement_pass_score ?? 70 }}">
                        </div>

                        <div id="placement-questions-container">
                            @if($course->placement_test)
                                @foreach(json_decode($course->placement_test, true) as $idx => $question)
                                    <div class="placement-question mb-4 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0">Question {{ $idx + 1 }}</h5>
                                            <button type="button" class="btn btn-danger btn-sm remove-question">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Question Text</label>
                                            <input type="text" name="placement_questions[{{ $idx }}][text]" class="form-input" value="{{ $question['text'] }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Options (Mark correct answer)</label>
                                            <div id="options-container-{{ $idx }}">
                                                @foreach($question['options'] as $oidx => $option)
                                                    <div class="option-row d-flex align-items-center mb-2">
                                                        <input type="text" name="placement_questions[{{ $idx }}][options][{{ $oidx }}]" 
                                                               class="form-input me-2" value="{{ $option }}" required>
                                                        <input type="radio" name="placement_questions[{{ $idx }}][correct]" 
                                                               value="{{ $oidx }}" class="me-2" {{ $question['correct'] == $oidx ? 'checked' : '' }}>
                                                        <label>Correct</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-secondary btn-sm add-option" data-question="{{ $idx }}">
                                                <i class="fas fa-plus me-1"></i> Add Option
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-placement-question">
                            <i class="fas fa-plus me-2"></i> Add Question
                        </button>
                    </div>
                </div>

                <script>
                    // Show placement test section only if prerequisite is selected
                    document.getElementById('prerequisite_id').addEventListener('change', function() {
                        const placementTestSection = document.getElementById('placement-test-section');
                        placementTestSection.style.display = this.value ? 'block' : 'none';
                    });

                    // Show questions when placement test is enabled
                    document.getElementById('enable_placement_test').addEventListener('change', function() {
                        document.getElementById('placement-test-questions').style.display = this.checked ? 'block' : 'none';
                    });

                    // Add new placement question
                    document.getElementById('add-placement-question').addEventListener('click', function() {
                        const questionIndex = document.querySelectorAll('.placement-question').length;
                        const questionDiv = document.createElement('div');
                        questionDiv.className = 'placement-question mb-4 p-3 border rounded';
                        questionDiv.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Question ${questionIndex + 1}</h5>
                                <button type="button" class="btn btn-danger btn-sm remove-question">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Question Text</label>
                                <input type="text" name="placement_questions[${questionIndex}][text]" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Options (Mark correct answer)</label>
                                <div id="options-container-${questionIndex}">
                                    <!-- Options will be added here -->
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm add-option" data-question="${questionIndex}">
                                    <i class="fas fa-plus me-1"></i> Add Option
                                </button>
                            </div>
                        `;
                        document.getElementById('placement-questions-container').appendChild(questionDiv);

                        // Add initial 4 options
                        for (let i = 0; i < 4; i++) {
                            addOption(questionIndex);
                        }
                    });

                    // Function to add option to a question
                    function addOption(questionIndex) {
                        const optionsContainer = document.getElementById(`options-container-${questionIndex}`);
                        const optionIndex = optionsContainer.querySelectorAll('.option-row').length;
                        
                        const optionDiv = document.createElement('div');
                        optionDiv.className = 'option-row d-flex align-items-center mb-2';
                        optionDiv.innerHTML = `
                            <input type="text" name="placement_questions[${questionIndex}][options][${optionIndex}]" 
                                   class="form-input me-2" placeholder="Option ${optionIndex + 1}" required>
                            <input type="radio" name="placement_questions[${questionIndex}][correct]" 
                                   value="${optionIndex}" class="me-2" ${optionIndex === 0 ? 'checked' : ''}>
                            <label>Correct</label>
                        `;
                        optionsContainer.appendChild(optionDiv);
                    }

                    // Delegated event for adding options
                    document.addEventListener('click', function(e) {
                        if (e.target && e.target.classList.contains('add-option')) {
                            const questionIndex = e.target.getAttribute('data-question');
                            addOption(questionIndex);
                        }
                        
                        // Remove question
                        if (e.target && e.target.closest('.remove-question')) {
                            e.target.closest('.placement-question').remove();
                        }
                    });

                    // Initialize placement test section if prerequisite is selected
                    if (document.getElementById('prerequisite_id').value) {
                        document.getElementById('placement-test-section').style.display = 'block';
                        if (document.getElementById('enable_placement_test').checked) {
                            document.getElementById('placement-test-questions').style.display = 'block';
                        }
                    }
                </script>

                <div class="form-group">
                    <label class="form-label" for="difficulty_level">Course Difficulty</label>
                    <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                        <option value="">Select difficulty level</option>
                        <option value="beginner" {{ old('difficulty_level', $course->difficulty_level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('difficulty_level', $course->difficulty_level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('difficulty_level', $course->difficulty_level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="coursepreview">Course Preview</label>
                    <textarea class="form-textarea" id="coursepreview" 
                              name="coursepreview" placeholder="Enter course preview" required>{{ old('coursepreview', $course->coursepreview) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Course Description</label>
                    <textarea class="form-textarea" id="content" 
                              name="content" placeholder="Enter course description" required>{{ old('content', $course->content) }}</textarea>
                </div>

<!-- Thumbnail Section -->
<div class="form-group">
    <label class="form-label">Thumbnail Image</label>
    
    @if($course->photo)
        <div class="current-media" id="current-thumbnail">
            <div class="current-media-item" data-thumbnail-id="1">
                <img src="{{ asset('storage/' . $course->photo) }}" alt="Current Thumbnail">
                <button type="button" class="remove-current-media" onclick="removeThumbnail()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    
    <div class="file-upload">
        <label for="photo">
            <i class="fas fa-image"></i> {{ $course->photo ? 'Change Thumbnail' : 'Upload Thumbnail' }}
        </label>
        <input type="file" id="photo" name="photo" accept="image/*">
    </div>
    <div class="images-preview-container" id="thumbnail-preview"></div>
</div>

<!-- Video Section -->
<div class="form-group">
    <label class="form-label">Course Video</label>
    
    @if($course->video)
        <div class="current-media" id="current-video">
            <div class="current-media-item" data-video-id="1">
                <video controls>
                    <source src="{{ asset('storage/' . $course->video) }}" type="video/mp4">
                </video>
                <button type="button" class="remove-current-media" onclick="removeVideo()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif
    
    <div class="file-upload">
        <label for="video">
            <i class="fas fa-video"></i> {{ $course->video ? 'Change Video' : 'Upload Video' }}
        </label>
        <input type="file" id="video" name="video" accept="video/*">
    </div>
    <div class="images-preview-container" id="video-preview"></div>
</div>

                <div class="form-group">
                    <label class="form-label">Course Images (Multiple)</label>
                    
                    @if($course->images->count() > 0)
                        <div class="current-media" id="current-course-images">
                            @foreach($course->images as $image)
                                <div class="current-media-item" data-image-id="{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Course Image">
                                    <button type="button" class="remove-current-media" onclick="removeCourseImage({{ $image->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="file-upload">
                        <label for="course_images">
                            <i class="fas fa-images"></i> Add More Images
                        </label>
                        <input type="file" id="course_images" name="course_images[]" 
                               accept="image/*" multiple>
                    </div>
                    
                    <div class="images-preview-container" id="images-preview"></div>
                </div>

                <!-- Course Files Section -->
                <div class="form-group">
                    <label class="form-label">Course Files (PDF, DOC, XLS, PPT, ZIP, RAR)</label>
                    
                    @php
                        $courseFiles = is_array($course->files) ? $course->files : [];
                    @endphp
                    
                    @if(count($courseFiles) > 0)
                        <div class="current-media mb-3" id="current-course-files">
                            @foreach($courseFiles as $index => $file)
                                <div class="file-item" data-file-index="{{ $index }}">
                                    @php
                                        $fileIcon = 'fa-file';
                                        $fileName = is_array($file) ? ($file['name'] ?? basename($file['path'] ?? '')) : basename($file);
                                        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                        
                                        if (in_array($fileExt, ['pdf'])) {
                                            $fileIcon = 'fa-file-pdf';
                                        } elseif (in_array($fileExt, ['doc', 'docx'])) {
                                            $fileIcon = 'fa-file-word';
                                        } elseif (in_array($fileExt, ['xls', 'xlsx'])) {
                                            $fileIcon = 'fa-file-excel';
                                        } elseif (in_array($fileExt, ['ppt', 'pptx'])) {
                                            $fileIcon = 'fa-file-powerpoint';
                                        } elseif (in_array($fileExt, ['zip', 'rar'])) {
                                            $fileIcon = 'fa-file-archive';
                                        }
                                    @endphp
                                    <i class="fas {{ $fileIcon }}"></i>
                                    <span class="file-name">{{ $fileName }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-file-btn" onclick="removeCourseFile('{{ $index }}')">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="file-upload mb-2">
                        <label for="course_files" class="btn btn-outline-primary">
                            <i class="fas fa-file-upload"></i> Choose Files
                        </label>
                        <input type="file" id="course_files" name="course_files[]" 
                               style="display: none;"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" 
                               multiple>
                    </div>
                    
                    <div id="files-preview" class="mb-3"></div>
                    <small class="form-text text-muted">Maximum file size: 20MB per file. Allowed formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR</small>
                </div>
                
                <style>
                .file-item {
                    background: #f8f9fa;
                    border: 1px solid #dee2e6;
                    border-radius: 5px;
                    padding: 10px 15px;
                    margin-bottom: 10px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .file-item i {
                    font-size: 1.5rem;
                    color: #6c757d;
                    min-width: 30px;
                    text-align: center;
                }
                .file-name {
                    flex-grow: 1;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .file-size {
                    color: #6c757d;
                    font-size: 0.9em;
                    white-space: nowrap;
                }
                .remove-file-btn {
                    margin-left: auto;
                }
                </style>

                <div class="form-group">
                    <label class="form-label">Course Levels</label>
                    <div id="levels-container">
                        @foreach($course->levels as $levelIndex => $level)
                            <div class="level-container" data-level-index="{{ $levelIndex }}" data-level-id="{{ $level->id }}">
                                <div class="level-header">
                                    <h3>Level <span class="level-number">{{ $levelIndex + 1 }}</span></h3>
                                    <button type="button" class="remove-btn remove-level-btn">
                                        <i class="fas fa-trash"></i> Remove Level
                                    </button>
                                </div>
                                
                                <div class="form-group">
                    <label class="form-label">Level Title</label>
                    <input type="text" name="levels[{{ $levelIndex }}][title]" class="form-input" 
                           value="{{ old("levels.$levelIndex.title", $level->title) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Passing Score (%)</label>
                    <input type="number" name="levels[{{ $levelIndex }}][passing_score]" class="form-input" 
                           min="0" max="100" value="{{ old("levels.$levelIndex.passing_score", $level->passing_score ?? 70) }}" required>
                    <small class="text-muted">Minimum percentage of correct answers needed to pass this level</small>
                </div>
                                
                                <input type="hidden" name="levels[{{ $levelIndex }}][id]" value="{{ $level->id }}">
                                <input type="hidden" name="levels[{{ $levelIndex }}][order]" value="{{ $levelIndex + 1 }}">
                                
                                <div class="form-group">
                                    <label class="form-label">Content Types</label>
                                    <div class="content-type-options">
                                        <div class="content-type-option active" data-type="text" onclick="toggleContentType(this, {{ $levelIndex }})">
                                            <i class="fas fa-align-left"></i> Text
                                        </div>
                                        <div class="content-type-option" data-type="image" onclick="toggleContentType(this, {{ $levelIndex }})">
                                            <i class="fas fa-image"></i> Images
                                        </div>
                                        <div class="content-type-option" data-type="video" onclick="toggleContentType(this, {{ $levelIndex }})">
                                            <i class="fas fa-video"></i> Videos
                                        </div>
                                    </div>
                                    
                                    <div class="content-fields text-fields active" id="text-fields-{{ $levelIndex }}">
                                        <div class="form-group">
                                            <label class="form-label">Text Contents</label>
                                            <div class="text-contents-container" id="text-contents-{{ $levelIndex }}">
                                                @if(!empty($level->text_contents))
                                                    @foreach($level->text_contents as $textIndex => $textContent)
                                                        <div class="text-content-item">
                                                            <textarea name="levels[{{ $levelIndex }}][text_contents][]" class="form-textarea">{{ $textContent }}</textarea>
                                                            <button type="button" class="btn btn-danger remove-text-btn" onclick="removeLevelText({{ $levelIndex }}, {{ $textIndex }}, {{ $level->id }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-content-item">
                                                        <textarea name="levels[{{ $levelIndex }}][text_contents][]" class="form-textarea" placeholder="Enter text content"></textarea>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-secondary mt-2" onclick="addTextContent({{ $levelIndex }})">
                                                <i class="fas fa-plus"></i> Add Text
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="content-fields image-fields" id="image-fields-{{ $levelIndex }}">
                                        <div class="form-group">
                                            <label class="form-label">Images</label>
                                            
                                            @if(!empty($level->images))
                                                <div class="current-media" id="level-images-preview-{{ $levelIndex }}">
                                                    @foreach($level->images as $index => $imagePath)
                                                        <div class="current-media-item" data-level-image-id="{{ $index }}" data-level-id="{{ $level->id }}" data-level-index="{{ $levelIndex }}">
                                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Level Image">
                                                            <button type="button" class="remove-current-media" onclick="removeLevelImage({{ $levelIndex }}, {{ $index }}, {{ $level->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <div class="file-upload">
                                                <label for="level-images-{{ $levelIndex }}">
                                                    <i class="fas fa-images"></i> Add More Images
                                                </label>
                                                <input type="file" id="level-images-{{ $levelIndex }}" 
                                                       name="levels[{{ $levelIndex }}][images][]" 
                                                       class="form-control-file level-images" 
                                                       multiple accept="image/*">
                                            </div>
                                            <div class="images-preview-container" id="level-image-preview-{{ $levelIndex }}"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="content-fields video-fields" id="video-fields-{{ $levelIndex }}">
                                        <div class="form-group">
                                            <label class="form-label">Videos</label>
                                            
                                            @if(!empty($level->videos))
                                                <div class="current-media" id="level-videos-preview-{{ $levelIndex }}">
                                                    @foreach($level->videos as $index => $videoPath)
                                                        <div class="current-media-item" data-level-video-id="{{ $index }}" data-level-id="{{ $level->id }}" data-level-index="{{ $levelIndex }}">
                                                            <video controls>
                                                                <source src="{{ asset('storage/' . $videoPath) }}" type="video/mp4">
                                                            </video>
                                                            <button type="button" class="remove-current-media" onclick="removeLevelVideo({{ $levelIndex }}, {{ $index }}, {{ $level->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            <div class="file-upload">
                                                <label for="level-videos-{{ $levelIndex }}">
                                                    <i class="fas fa-video"></i> Add More Videos
                                                </label>
                                                <input type="file" id="level-videos-{{ $levelIndex }}" 
                                                       name="levels[{{ $levelIndex }}][videos][]" 
                                                       class="form-control-file level-videos" 
                                                       multiple accept="video/*">
                                            </div>
                                            <div class="images-preview-container" id="level-video-preview-{{ $levelIndex }}"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="questions-container">
                                    <h4>Test Questions</h4>
                                    <div class="questions-list">
                                        @foreach($level->questions as $questionIndex => $question)
                                            <div class="question-container" data-question-id="{{ $question->id }}">
                                                <div class="question-header">
                                                    <button type="button" class="remove-btn remove-question-btn">
                                                        <i class="fas fa-trash"></i> Remove Question
                                                    </button>
                                                </div>
                                                
                                                <input type="hidden" name="levels[{{ $levelIndex }}][questions][{{ $questionIndex }}][id]" value="{{ $question->id }}">
                                                
                                                <div class="form-group">
                                                    <label class="form-label">Question Text</label>
                                                    <input type="text" 
                                                           name="levels[{{ $levelIndex }}][questions][{{ $questionIndex }}][question_text]" 
                                                           class="form-input" 
                                                           value="{{ $question->question_text }}" 
                                                           required>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label">Options (Mark correct answer with radio button)</label>
                                                    <div class="options-list">
                                                        @foreach($question->options as $optionIndex => $option)
                                                            <div class="option-input">
                                                                <input type="radio" 
                                                                       name="levels[{{ $levelIndex }}][questions][{{ $questionIndex }}][correct_answer]" 
                                                                       value="{{ $optionIndex }}" 
                                                                       {{ $question->correct_answer == $optionIndex ? 'checked' : '' }}>
                                                                <input type="text" 
                                                                       name="levels[{{ $levelIndex }}][questions][{{ $questionIndex }}][options][{{ $optionIndex }}]" 
                                                                       class="form-input" 
                                                                       placeholder="Option {{ $optionIndex + 1 }}" 
                                                                       value="{{ $option }}" 
                                                                       required>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-option-btn">Add Option</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-secondary add-question-btn">
                                        <i class="fas fa-plus"></i> Add Question
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary" id="add-level-btn">
                        <i class="fas fa-plus"></i> Add Level
                    </button>
                </div>

                <button type="button" class="btn btn-success" id="show-confirm-modal">
                    <i class="fas fa-save"></i> Update Course
                </button>
            </form>
        </div>
    </div>

    <template id="level-template">
        <div class="level-container" data-level-index="0">
            <div class="level-header">
                <h3>Level <span class="level-number">1</span></h3>
                <button type="button" class="remove-btn remove-level-btn">
                    <i class="fas fa-trash"></i> Remove Level
                </button>
            </div>
            
            <div class="form-group">
                <label class="form-label">Level Title</label>
                <input type="text" name="levels[0][title]" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Content Types</label>
                <div class="content-type-options">
                    <div class="content-type-option active" data-type="text" onclick="toggleContentType(this, 0)">
                        <i class="fas fa-align-left"></i> Text
                    </div>
                    <div class="content-type-option" data-type="image" onclick="toggleContentType(this, 0)">
                        <i class="fas fa-image"></i> Images
                    </div>
                    <div class="content-type-option" data-type="video" onclick="toggleContentType(this, 0)">
                        <i class="fas fa-video"></i> Videos
                    </div>
                </div>
                
                <div class="content-fields text-fields active" id="text-fields-0">
                    <div class="form-group">
                        <label class="form-label">Text Contents</label>
                        <div class="text-contents-container" id="text-contents-0">
                            <div class="text-content-item">
                                <textarea name="levels[0][text_contents][]" class="form-textarea" placeholder="Enter text content"></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" onclick="addTextContent(0)">
                            <i class="fas fa-plus"></i> Add Text
                        </button>
                    </div>
                </div>
                
                <div class="content-fields image-fields" id="image-fields-0">
                    <div class="form-group">
                        <label class="form-label">Images</label>
                        <div class="file-upload">
                            <label for="level-images-0">
                                <i class="fas fa-images"></i> Upload Images
                            </label>
                            <input type="file" id="level-images-0" name="levels[0][images][]" class="form-control-file level-images" multiple accept="image/*">
                        </div>
                        <div class="images-preview-container" id="level-image-preview-0"></div>
                    </div>
                </div>
                
                <div class="content-fields video-fields" id="video-fields-0">
                    <div class="form-group">
                        <label class="form-label">Videos</label>
                        <div class="file-upload">
                            <label for="level-videos-0">
                                <i class="fas fa-video"></i> Upload Videos
                            </label>
                            <input type="file" id="level-videos-0" name="levels[0][videos][]" class="form-control-file level-videos" multiple accept="video/*">
                        </div>
                        <div class="images-preview-container" id="level-video-preview-0"></div>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="levels[0][order]" value="1">
            
            <div class="questions-container">
                <h4>Test Questions</h4>
                <div class="questions-list"></div>
                <button type="button" class="btn btn-secondary add-question-btn">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
        </div>
    </template>

    <template id="question-template">
        <div class="question-container">
            <div class="question-header">
                <button type="button" class="remove-btn remove-question-btn">
                    <i class="fas fa-trash"></i> Remove Question
                </button>
            </div>
            
            <div class="form-group">
                <label class="form-label">Question Text</label>
                <input type="text" name="question_text" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Options (Mark correct answer with radio button)</label>
                <div class="options-list">
                    <div class="option-input">
                        <input type="radio" name="correct_answer" value="0" checked>
                        <input type="text" name="options[]" class="form-input" placeholder="Option 1" required>
                    </div>
                    <div class="option-input">
                        <input type="radio" name="correct_answer" value="1">
                        <input type="text" name="options[]" class="form-input" placeholder="Option 2" required>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary add-option-btn">Add Option</button>
            </div>
        </div>
    </template>

    <style>
        /* Ensure textarea has no scrollbar */
        textarea[name="content"],
        textarea[name="description"] {
            overflow: hidden !important;
            resize: none !important;
            min-height: 100px;
            max-height: none !important;
        }
    </style>
    <script>
        // Auto-resize textarea function
        function autoResizeTextarea(textarea) {
            // Reset height to auto to get the correct scrollHeight
            textarea.style.height = 'auto';
            // Set height to scrollHeight to show all content
            textarea.style.height = textarea.scrollHeight + 'px';
            // Ensure the page doesn't jump by scrolling to maintain position
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            window.scrollTo(0, scrollTop);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-resize content textarea
            const contentTextarea = document.querySelector('textarea[name="content"]');
            if (contentTextarea) {
                // Initial resize with a small delay to ensure all content is loaded
                setTimeout(() => autoResizeTextarea(contentTextarea), 100);
                
                // Auto-resize on input
                contentTextarea.addEventListener('input', function() {
                    autoResizeTextarea(this);
                });
                
                // Also resize on window resize
                window.addEventListener('resize', () => autoResizeTextarea(contentTextarea));
            }
            
            // تعريف المتغيرات العامة
            let deletedImages = [];
            let deletedThumbnail = false;
            let deletedVideo = false;
            let deletedFiles = [];
            let levelCounter = {{ $course->levels->count() }};
            const levelsContainer = document.getElementById('levels-container');
            const addLevelBtn = document.getElementById('add-level-btn');
            const levelTemplate = document.getElementById('level-template');
            const questionTemplate = document.getElementById('question-template');
            const imagesInput = document.getElementById('course_images');
            const imagesPreview = document.getElementById('images-preview');
            const courseForm = document.getElementById('course-form');
            
            // تعريف الدوال العامة
            window.toggleContentType = function(element, levelIndex) {
                const type = element.dataset.type;
                const container = element.closest('.level-container');
                
                container.querySelectorAll('.content-type-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                element.classList.add('active');
                
                container.querySelectorAll('.content-fields').forEach(field => {
                    field.classList.remove('active');
                });
                container.querySelector(`.${type}-fields`).classList.add('active');
            };
            
            window.addTextContent = function(levelIndex) {
                const container = document.getElementById(`text-contents-${levelIndex}`);
                
                // إنشاء عنصر div لحاوية النص
                const textContentItem = document.createElement('div');
                textContentItem.className = 'text-content-item';
                
                // إنشاء textarea للنص
                const newTextarea = document.createElement('textarea');
                newTextarea.className = 'form-textarea';
                newTextarea.name = `levels[${levelIndex}][text_contents][]`;
                newTextarea.placeholder = 'Enter text content';
                
                // إنشاء زر الحذف
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger remove-text-btn';
                removeButton.innerHTML = '<i class="fas fa-trash"></i>';
                removeButton.onclick = function() {
                    // حذف العنصر من الواجهة فقط لأنه عنصر جديد
                    textContentItem.remove();
                };
                
                // إضافة العناصر إلى الحاوية
                textContentItem.appendChild(newTextarea);
                textContentItem.appendChild(removeButton);
                container.appendChild(textContentItem);
            };
            
            window.removeLevelText = function(levelIndex, textIndex, levelId = null) {
                console.log(`Removing text at level ${levelIndex}, index ${textIndex}, level ID ${levelId}`);
                
                // البحث عن حاوية المستوى
                const levelContainer = document.querySelector(`.level-container[data-level-index="${levelIndex}"]`);
                if (!levelContainer) {
                    console.error(`Level container not found for index ${levelIndex}`);
                    return;
                }
                
                // إنشاء حقل الإدخال المخفي للنصوص المحذوفة
                const removedInput = document.createElement('input');
                removedInput.type = 'hidden';
                removedInput.name = `levels[${levelIndex}][removed_texts][]`;
                removedInput.value = textIndex;
                removedInput.className = 'removed-text-input';
                
                // إضافة الحقل إلى النموذج
                const levelForm = document.querySelector(`#course-form`);
                levelForm.appendChild(removedInput);
                
                console.log(`Added hidden input for removed text: ${removedInput.name}=${removedInput.value}`);
                
                // إضافة حقل معرف المستوى إذا لم يكن موجودًا
                if (levelId) {
                    // التحقق من وجود حقل معرف المستوى في النموذج
                    const existingIdInput = document.querySelector(`input[name="levels[${levelIndex}][id]"][value="${levelId}"]`);
                    
                    if (!existingIdInput) {
                        const levelIdInput = document.createElement('input');
                        levelIdInput.type = 'hidden';
                        levelIdInput.name = `levels[${levelIndex}][id]`;
                        levelIdInput.value = levelId;
                        levelForm.appendChild(levelIdInput);
                        console.log(`Added level ID input: ${levelIdInput.name}=${levelIdInput.value}`);
                    }
                }
                
                // إزالة العنصر من الواجهة
                const textContainer = document.querySelector(`#text-contents-${levelIndex}`);
                const textItems = textContainer.querySelectorAll('.text-content-item');
                if (textItems.length > textIndex && textItems[textIndex]) {
                    textItems[textIndex].remove();
                    console.log('Removed text element from UI');
                } else {
                    console.warn(`Text element not found for level ${levelIndex}, index ${textIndex}`);
                }
            };
            
            window.removeThumbnail = function() {
    document.getElementById('photo').value = '';
    const thumbnailElement = document.querySelector('[data-thumbnail-id="1"]');
    if (thumbnailElement) thumbnailElement.remove();
    document.getElementById('deleted-thumbnail').value = '1'; // Mark as deleted
    document.getElementById('thumbnail-preview').innerHTML = '';
};
            
window.removeVideo = function() {
    document.getElementById('video').value = '';
    const videoElement = document.querySelector('[data-video-id="1"]');
    if (videoElement) videoElement.remove();
    document.getElementById('deleted-video').value = '1'; // Mark as deleted
    document.getElementById('video-preview').innerHTML = '';
};
            
            window.removeCourseImage = function(imageId) {
                deletedImages.push(parseInt(imageId));
                document.getElementById('deleted-images').value = JSON.stringify(deletedImages);
                const imageElement = document.querySelector(`[data-image-id="${imageId}"]`);
                if (imageElement) imageElement.remove();
            };
            
            window.removeCourseFile = function(fileIndex) {
                deletedFiles.push(fileIndex);
                document.getElementById('deleted-files').value = JSON.stringify(deletedFiles);
                const fileElement = document.querySelector(`[data-file-index="${fileIndex}"]`);
                if (fileElement) fileElement.remove();
            };
            
            // Initialize file upload functionality
            const filesInput = document.getElementById('course_files');
            const filesPreview = document.getElementById('files-preview');
            const addMoreFilesBtn = document.getElementById('add-more-files');
            
            // Handle file selection
            if (filesInput) {
                filesInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        Array.from(this.files).forEach(file => {
                            const fileType = file.name.split('.').pop().toLowerCase();
                            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                            
                            const filePreview = document.createElement('div');
                            filePreview.className = 'file-item';
                            
                            // Determine icon based on file extension
                            let fileIcon = 'fa-file';
                            if (fileType === 'pdf') fileIcon = 'fa-file-pdf';
                            else if (['doc', 'docx'].includes(fileType)) fileIcon = 'fa-file-word';
                            else if (['xls', 'xlsx'].includes(fileType)) fileIcon = 'fa-file-excel';
                            else if (['ppt', 'pptx'].includes(fileType)) fileIcon = 'fa-file-powerpoint';
                            else if (['zip', 'rar'].includes(fileType)) fileIcon = 'fa-file-archive';
                            
                            filePreview.innerHTML = `
                                <i class="fas ${fileIcon}"></i>
                                <span class="file-name">${file.name}</span>
                                <span class="file-size">${fileSizeMB} MB</span>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-file-btn">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            `;
                            filesPreview.appendChild(filePreview);
                        });
                    }
                });
            }
            
            // Handle remove file button clicks for newly added files
            if (filesPreview) {
                filesPreview.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-file-btn')) {
                        const fileItem = e.target.closest('.file-item');
                        const fileName = fileItem.querySelector('.file-name').textContent;
                        
                        // Remove from the file input
                        const dataTransfer = new DataTransfer();
                        const inputFiles = Array.from(filesInput.files);
                        const fileIndex = Array.from(filesPreview.children).indexOf(fileItem);
                        
                        if (fileIndex > -1) {
                            inputFiles.splice(fileIndex, 1);
                            inputFiles.forEach(file => dataTransfer.items.add(file));
                            filesInput.files = dataTransfer.files;
                        }
                        
                        // Remove from the preview
                        fileItem.remove();
                    }
                });
            }
            
            // Add more files button
            if (addMoreFilesBtn) {
                addMoreFilesBtn.addEventListener('click', function() {
                    filesInput.click();
                });
            }
            
            window.removeLevelImage = function(levelIndex, imageIndex, levelId = null) {
                console.log(`Removing image at level ${levelIndex}, index ${imageIndex}, level ID ${levelId}`);
                
                // البحث عن حاوية المستوى
                const levelContainer = document.querySelector(`.level-container[data-level-index="${levelIndex}"]`);
                if (!levelContainer) {
                    console.error(`Level container not found for index ${levelIndex}`);
                    return;
                }
                
                // إنشاء حقل الإدخال المخفي للصور المحذوفة
                const removedInput = document.createElement('input');
                removedInput.type = 'hidden';
                removedInput.name = `levels[${levelIndex}][removed_images][]`;
                removedInput.value = imageIndex;
                removedInput.className = 'removed-image-input';
                
                // إضافة الحقل إلى حاوية المستوى
                const levelForm = document.querySelector(`#course-form`);
                levelForm.appendChild(removedInput);
                
                console.log(`Added hidden input for removed image: ${removedInput.name}=${removedInput.value}`);
                
                // إضافة حقل معرف المستوى إذا لم يكن موجودًا
                if (levelId) {
                    // التحقق من وجود حقل معرف المستوى في النموذج
                    const existingIdInput = document.querySelector(`input[name="levels[${levelIndex}][id]"][value="${levelId}"]`);
                    
                    if (!existingIdInput) {
                        const levelIdInput = document.createElement('input');
                        levelIdInput.type = 'hidden';
                        levelIdInput.name = `levels[${levelIndex}][id]`;
                        levelIdInput.value = levelId;
                        levelForm.appendChild(levelIdInput);
                        console.log(`Added level ID input: ${levelIdInput.name}=${levelIdInput.value}`);
                    }
                }
                
                // إزالة العنصر من الواجهة
                const imageElement = document.querySelector(`[data-level-image-id="${imageIndex}"][data-level-index="${levelIndex}"]`);
                if (imageElement) {
                    imageElement.remove();
                    console.log('Removed image element from UI');
                } else {
                    console.warn(`Image element not found for level ${levelIndex}, index ${imageIndex}`);
                }
                
                // طباعة محتوى النموذج للتأكد من إضافة الحقول
                console.log('Form inputs for removed images:', document.querySelectorAll('input[name$="[removed_images][]"]').length);
                
                // طباعة كل الحقول المتعلقة بالصور المحذوفة
                const inputs = document.querySelectorAll('input[name$="[removed_images][]"]');
                inputs.forEach(input => {
                    console.log(`Form input: ${input.name} = ${input.value}`);
                });
            };
            
            window.removeLevelVideo = function(levelIndex, videoIndex, levelId = null) {
                console.log(`Removing video at level ${levelIndex}, index ${videoIndex}, level ID ${levelId}`);
                
                // البحث عن حاوية المستوى
                const levelContainer = document.querySelector(`.level-container[data-level-index="${levelIndex}"]`);
                if (!levelContainer) {
                    console.error(`Level container not found for index ${levelIndex}`);
                    return;
                }
                
                // إنشاء حقل الإدخال المخفي للفيديوهات المحذوفة
                const removedInput = document.createElement('input');
                removedInput.type = 'hidden';
                removedInput.name = `levels[${levelIndex}][removed_videos][]`;
                removedInput.value = videoIndex;
                removedInput.className = 'removed-video-input';
                
                // إضافة الحقل إلى حاوية المستوى
                const levelForm = document.querySelector(`#course-form`);
                levelForm.appendChild(removedInput);
                
                console.log(`Added hidden input for removed video: ${removedInput.name}=${removedInput.value}`);
                
                // إضافة حقل معرف المستوى إذا لم يكن موجودًا
                if (levelId) {
                    // التحقق من وجود حقل معرف المستوى في النموذج
                    const existingIdInput = document.querySelector(`input[name="levels[${levelIndex}][id]"][value="${levelId}"]`);
                    
                    if (!existingIdInput) {
                        const levelIdInput = document.createElement('input');
                        levelIdInput.type = 'hidden';
                        levelIdInput.name = `levels[${levelIndex}][id]`;
                        levelIdInput.value = levelId;
                        levelForm.appendChild(levelIdInput);
                        console.log(`Added level ID input: ${levelIdInput.name}=${levelIdInput.value}`);
                    }
                }
                
                // إزالة العنصر من الواجهة
                const videoElement = document.querySelector(`[data-level-video-id="${videoIndex}"][data-level-index="${levelIndex}"]`);
                if (videoElement) {
                    videoElement.remove();
                    console.log('Removed video element from UI');
                } else {
                    console.warn(`Video element not found for level ${levelIndex}, index ${videoIndex}`);
                }
                
                // طباعة محتوى النموذج للتأكد من إضافة الحقول
                console.log('Form inputs for removed videos:', document.querySelectorAll('input[name$="[removed_videos][]"]').length);
                
                // طباعة كل الحقول المتعلقة بالفيديوهات المحذوفة
                const inputs = document.querySelectorAll('input[name$="[removed_videos][]"]');
                inputs.forEach(input => {
                    console.log(`Form input: ${input.name} = ${input.value}`);
                });
            };
            
            // إضافة مستوى جديد
            addLevelBtn.addEventListener('click', function() {
                const newLevel = levelTemplate.content.cloneNode(true);
                const levelElement = newLevel.querySelector('.level-container');
                const levelIndex = levelCounter;
                levelCounter++;
                
                levelElement.dataset.levelIndex = levelIndex;
                levelElement.querySelector('.level-number').textContent = levelIndex + 1;
                updateElementNames(levelElement, levelIndex);
                levelsContainer.appendChild(newLevel);
                
                // إضافة سؤال تلقائي عند إنشاء مستوى جديد
                const addQuestionBtn = levelElement.querySelector('.add-question-btn');
                if (addQuestionBtn) {
                    addQuestionBtn.click();
                }
            });
            
            // إزالة مستوى
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-level-btn')) {
                    const levelContainer = e.target.closest('.level-container');
                    const levelId = levelContainer.dataset.levelId;
                    
                    if (levelId) {
                        const deletedLevelsInput = document.getElementById('deleted-levels');
                        const deletedLevels = JSON.parse(deletedLevelsInput.value);
                        deletedLevels.push(parseInt(levelId));
                        deletedLevelsInput.value = JSON.stringify(deletedLevels);
                    }
                    
                    if (confirm('Are you sure you want to remove this level?')) {
                        levelContainer.remove();
                        renumberLevels();
                    }
                }
            });
            
            // إضافة سؤال جديد
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.add-question-btn')) {
                    const levelContainer = e.target.closest('.level-container');
                    const questionsList = levelContainer.querySelector('.questions-list');
                    const levelIndex = levelContainer.dataset.levelIndex;
                    const questionCount = questionsList.querySelectorAll('.question-container').length;
                    
                    const newQuestion = questionTemplate.content.cloneNode(true);
                    const questionElement = newQuestion.querySelector('.question-container');
                    updateQuestionNames(questionElement, levelIndex, questionCount);
                    questionsList.appendChild(newQuestion);
                }
            });
            
            // إزالة سؤال
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-question-btn')) {
                    const questionContainer = e.target.closest('.question-container');
                    const questionId = questionContainer.dataset.questionId;
                    
                    if (questionId) {
                        const deletedQuestionsInput = document.getElementById('deleted-questions');
                        const deletedQuestions = JSON.parse(deletedQuestionsInput.value);
                        deletedQuestions.push(parseInt(questionId));
                        deletedQuestionsInput.value = JSON.stringify(deletedQuestions);
                    }
                    
                    if (confirm('Are you sure you want to remove this question?')) {
                        questionContainer.remove();
                    }
                }
            });
            
            // إضافة خيار لسؤال
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-option-btn')) {
                    const questionContainer = e.target.closest('.question-container');
                    const optionsList = questionContainer.querySelector('.options-list');
                    const optionCount = optionsList.querySelectorAll('.option-input').length;
                    
                    if (optionCount >= 5) {
                        alert('Maximum 5 options allowed per question');
                        return;
                    }
                    
                    const levelContainer = questionContainer.closest('.level-container');
                    const levelIndex = levelContainer.dataset.levelIndex;
                    const questionIndex = Array.from(levelContainer.querySelectorAll('.question-container')).indexOf(questionContainer);
                    
                    const newOption = document.createElement('div');
                    newOption.className = 'option-input';
                    newOption.innerHTML = `
                        <input type="radio" name="levels[${levelIndex}][questions][${questionIndex}][correct_answer]" value="${optionCount}">
                        <input type="text" name="levels[${levelIndex}][questions][${questionIndex}][options][${optionCount}]" class="form-input" placeholder="Option ${optionCount + 1}" required>
                    `;
                    optionsList.appendChild(newOption);
                }
            });
            
            // معاينة صور الكورس
            imagesInput.addEventListener('change', function() {
                imagesPreview.innerHTML = '';
                
                if (this.files && this.files.length > 0) {
                    if (this.files.length > 10) {
                        alert('Maximum 10 images allowed');
                        this.value = '';
                        return;
                    }
                    
                    Array.from(this.files).forEach((file, index) => {
                        if (!file.type.match('image.*')) {
                            alert(`File ${file.name} is not an image`);
                            return;
                        }
                        
                        if (file.size > 2 * 1024 * 1024) {
                            alert(`Image ${file.name} is too large (max 2MB)`);
                            return;
                        }
                        
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item';
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image-btn" data-index="${index}">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            imagesPreview.appendChild(previewItem);
                        };
                        
                        reader.readAsDataURL(file);
                    });
                }
            });
            
            // معاينة صورة الغلاف (Thumbnail)
            const photoInput = document.getElementById('photo');
            const thumbnailPreview = document.getElementById('thumbnail-preview');
            
            photoInput.addEventListener('change', function() {
                thumbnailPreview.innerHTML = '';
                
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    
                    if (!file.type.match('image.*')) {
                        alert(`File ${file.name} is not an image`);
                        this.value = '';
                        return;
                    }
                    
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`Image ${file.name} is too large (max 2MB)`);
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'image-preview-item';
                        previewItem.innerHTML = `
                            <img src="${e.target.result}" alt="Thumbnail Preview">
                            <button type="button" class="remove-image-btn" data-type="thumbnail">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        thumbnailPreview.appendChild(previewItem);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
            
            // معاينة الفيديو
            const videoInput = document.getElementById('video');
            const videoPreview = document.getElementById('video-preview');
            
            videoInput.addEventListener('change', function() {
                videoPreview.innerHTML = '';
                
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    
                    if (!file.type.match('video.*')) {
                        alert(`File ${file.name} is not a video`);
                        this.value = '';
                        return;
                    }
                    
                    if (file.size > 10 * 1024 * 1024) {
                        alert(`Video ${file.name} is too large (max 10MB)`);
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'image-preview-item';
                        previewItem.style.height = 'auto';
                        previewItem.innerHTML = `
                            <video controls style="width: 100%; height: auto;">
                                <source src="${e.target.result}" type="${file.type}">
                            </video>
                            <button type="button" class="remove-image-btn" data-type="video">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        videoPreview.appendChild(previewItem);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
            
            // إزالة صورة من المعاينة
            imagesPreview.addEventListener('click', function(e) {
                if (e.target.closest('.remove-image-btn')) {
                    const index = e.target.closest('.remove-image-btn').dataset.index;
                    const files = Array.from(imagesInput.files);
                    files.splice(index, 1);
                    
                    const dataTransfer = new DataTransfer();
                    files.forEach(file => dataTransfer.items.add(file));
                    imagesInput.files = dataTransfer.files;
                    imagesInput.dispatchEvent(new Event('change'));
                }
            });
            
            // إزالة صورة الغلاف من المعاينة
            thumbnailPreview.addEventListener('click', function(e) {
                if (e.target.closest('.remove-image-btn')) {
                    photoInput.value = '';
                    thumbnailPreview.innerHTML = '';
                }
            });
            
            // إزالة الفيديو من المعاينة
            videoPreview.addEventListener('click', function(e) {
                if (e.target.closest('.remove-image-btn')) {
                    videoInput.value = '';
                    videoPreview.innerHTML = '';
                }
            });
            
            // معاينة صور وفيديوهات المستوى (دعم ديناميكي)
            document.getElementById('levels-container').addEventListener('change', function(e) {
                // صور المستوى
                if (e.target && e.target.classList.contains('level-images')) {
                    const levelIndex = e.target.id.split('-')[2];
                    const previewContainer = document.getElementById(`level-image-preview-${levelIndex}`);
                    previewContainer.innerHTML = '';
                    if (e.target.files && e.target.files.length > 0) {
                        Array.from(e.target.files).forEach((file, index) => {
                            if (!file.type.match('image.*')) {
                                alert(`File ${file.name} is not an image`);
                                return;
                            }
                            if (file.size > 2 * 1024 * 1024) {
                                alert(`Image ${file.name} is too large (max 2MB)`);
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                const previewItem = document.createElement('div');
                                previewItem.className = 'image-preview-item';
                                previewItem.innerHTML = `
                                    <img src="${ev.target.result}" alt="Level Image Preview">
                                    <button type="button" class="remove-image-btn" data-level-index="${levelIndex}" data-file-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                previewContainer.appendChild(previewItem);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                }
                // فيديوهات المستوى
                if (e.target && e.target.classList.contains('level-videos')) {
                    const levelIndex = e.target.id.split('-')[2];
                    const previewContainer = document.getElementById(`level-video-preview-${levelIndex}`);
                    previewContainer.innerHTML = '';
                    if (e.target.files && e.target.files.length > 0) {
                        Array.from(e.target.files).forEach((file, index) => {
                            if (!file.type.match('video.*')) {
                                alert(`File ${file.name} is not a video`);
                                return;
                            }
                            if (file.size > 10 * 1024 * 1024) {
                                alert(`Video ${file.name} is too large (max 10MB)`);
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                const previewItem = document.createElement('div');
                                previewItem.className = 'image-preview-item';
                                previewItem.style.height = 'auto';
                                previewItem.innerHTML = `
                                    <video controls style="width: 100%; height: auto;">
                                        <source src="${ev.target.result}" type="${file.type}">
                                    </video>
                                    <button type="button" class="remove-video-btn" data-level-index="${levelIndex}" data-file-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `;
                                previewContainer.appendChild(previewItem);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                }
            });
            // ملاحظة: لم يعد هناك حاجة لإعادة ربط الأحداث بعد إضافة مستوى جديد، لأن event delegation سيعمل دائماً.
            
            // إزالة صورة من معاينة المستوى
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-image-btn');
                if (removeBtn && removeBtn.dataset.levelIndex && removeBtn.dataset.fileIndex) {
                    const levelIndex = removeBtn.dataset.levelIndex;
                    const fileIndex = removeBtn.dataset.fileIndex;
                    const input = document.getElementById(`level-images-${levelIndex}`);
                    
                    if (input && input.files.length > 0) {
                        const files = Array.from(input.files);
                        files.splice(fileIndex, 1);
                        
                        const dataTransfer = new DataTransfer();
                        files.forEach(file => dataTransfer.items.add(file));
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    }
                }
            });
            
            // (تم نقل معاينة فيديوهات المستوى إلى event delegation أعلاه)
            
            // إزالة فيديو من معاينة المستوى
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-video-btn');
                if (removeBtn && removeBtn.dataset.levelIndex && removeBtn.dataset.fileIndex) {
                    const levelIndex = removeBtn.dataset.levelIndex;
                    const fileIndex = removeBtn.dataset.fileIndex;
                    const input = document.getElementById(`level-videos-${levelIndex}`);
                    
                    if (input && input.files.length > 0) {
                        const files = Array.from(input.files);
                        files.splice(fileIndex, 1);
                        
                        const dataTransfer = new DataTransfer();
                        files.forEach(file => dataTransfer.items.add(file));
                        input.files = dataTransfer.files;
                        input.dispatchEvent(new Event('change'));
                    }
                }
            });
            
            // التحقق قبل الإرسال
            courseForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                // التحقق من وجود مستويات
                if (levelsContainer.querySelectorAll('.level-container').length === 0) {
                    alert('Please add at least one level');
                    isValid = false;
                }
                
                // التحقق من وجود أسئلة في كل مستوى
                levelsContainer.querySelectorAll('.level-container').forEach(level => {
                    if (level.querySelectorAll('.question-container').length === 0) {
                        alert('Each level must have at least one question');
                        isValid = false;
                    }
                });
                
                // طباعة بيانات النموذج للتأكد من إرسال البيانات المحذوفة
                console.log('Form submission data:');
                console.log('Removed images:', document.querySelectorAll('input[name$="[removed_images][]"]').length);
                console.log('Removed videos:', document.querySelectorAll('input[name$="[removed_videos][]"]').length);
                
                const formData = new FormData(courseForm);
                for (let [key, value] of formData.entries()) {
                    if (key.includes('removed_images') || key.includes('removed_videos')) {
                        console.log(`${key}: ${value}`);
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
            
            // دوال مساعدة
            function updateElementNames(container, levelIndex) {
                const elements = container.querySelectorAll('[name]');
                elements.forEach(el => {
                    const name = el.getAttribute('name')
                        .replace(/levels\[\d+\]/, `levels[${levelIndex}]`);
                    el.setAttribute('name', name);
                });
                
                // تحديث IDs لحقول المحتوى
                ['images', 'videos'].forEach(type => {
                    const input = container.querySelector(`.level-${type}`);
                    const label = container.querySelector(`label[for^="level-${type}-"]`);
                    const preview = container.querySelector(`[id$="-preview-0"]`);
                    
                    if (input && label) {
                        input.id = `level-${type}-${levelIndex}`;
                        label.setAttribute('for', `level-${type}-${levelIndex}`);
                    }
                    
                    // تحديث معرفات حاويات المعاينة
                    if (type === 'images') {
                        const imagePreview = container.querySelector('#level-image-preview-0');
                        if (imagePreview) {
                            imagePreview.id = `level-image-preview-${levelIndex}`;
                        }
                    } else if (type === 'videos') {
                        const videoPreview = container.querySelector('#level-video-preview-0');
                        if (videoPreview) {
                            videoPreview.id = `level-video-preview-${levelIndex}`;
                        }
                    }
                });
                
                // تحديث order
                const orderInput = container.querySelector('input[name$="[order]"]');
                if (orderInput) {
                    orderInput.value = levelIndex + 1;
                }
            }
            
            function updateQuestionNames(container, levelIndex, questionIndex) {
                // نص السؤال
                const questionInput = container.querySelector('input[name="question_text"]');
                questionInput.name = `levels[${levelIndex}][questions][${questionIndex}][question_text]`;
                
                // الخيارات
                const options = container.querySelectorAll('.option-input');
                options.forEach((optionDiv, index) => {
                    const textInput = optionDiv.querySelector('input[type="text"]');
                    textInput.name = `levels[${levelIndex}][questions][${questionIndex}][options][${index}]`;
                    
                    const radio = optionDiv.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.name = `levels[${levelIndex}][questions][${questionIndex}][correct_answer]`;
                        radio.value = index;
                    }
                });
            }
            
            function renumberLevels() {
                const levels = levelsContainer.querySelectorAll('.level-container');
                levels.forEach((level, index) => {
                    const newIndex = index;
                    const oldIndex = parseInt(level.dataset.levelIndex);
                    
                    // تحديث فهرس المستوى
                    level.dataset.levelIndex = newIndex;
                    level.querySelector('.level-number').textContent = newIndex + 1;
                    
                    // تحديث أسماء العناصر
                    updateElementNames(level, newIndex);
                    
                    // تحديث أسماء حقول الإدخال المخفية للعناصر المحذوفة
                    if (oldIndex !== newIndex) {
                        // تحديث أسماء حقول الصور المحذوفة
                        level.querySelectorAll('input.removed-image-input').forEach(input => {
                            input.name = input.name.replace(`levels[${oldIndex}]`, `levels[${newIndex}]`);
                            console.log(`Updated removed image input: ${input.name}`);
                        });
                        
                        // تحديث أسماء حقول الفيديوهات المحذوفة
                        level.querySelectorAll('input.removed-video-input').forEach(input => {
                            input.name = input.name.replace(`levels[${oldIndex}]`, `levels[${newIndex}]`);
                            console.log(`Updated removed video input: ${input.name}`);
                        });
                    }
                    
                    // إعادة ترقيم الأسئلة
                    const questions = level.querySelectorAll('.question-container');
                    questions.forEach((question, qIndex) => {
                        updateQuestionNames(question, newIndex, qIndex);
                    });
                    
                    // تحديث سمات البيانات للصور والفيديوهات
                    level.querySelectorAll('[data-level-index]').forEach(el => {
                        el.dataset.levelIndex = newIndex;
                    });
                });
                
                levelCounter = levels.length;
                console.log(`Renumbered ${levels.length} levels`);
            }
        });
    </script>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
<!-- Confirmation Modal -->
<div id="confirmModal" style="display:none;position:fixed;z-index:9999;left:0;top:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem 2.5rem;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.2);max-width:90vw;min-width:320px;text-align:center;">
        <h2 style="margin-bottom:1rem;font-size:1.3rem;">Are you sure you want to save the changes?</h2>
        <div style="display:flex;gap:1rem;justify-content:center;">
            <button id="confirmSaveBtn" style="background:#16a34a;color:#fff;padding:0.5rem 1.5rem;border:none;border-radius:5px;font-size:1rem;cursor:pointer;">Confirm Save</button>
            <button id="cancelChangesBtn" style="background:#dc2626;color:#fff;padding:0.5rem 1.5rem;border:none;border-radius:5px;font-size:1rem;cursor:pointer;">Cancel Changes</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showModalBtn = document.getElementById('show-confirm-modal');
        const modal = document.getElementById('confirmModal');
        const confirmBtn = document.getElementById('confirmSaveBtn');
        const cancelBtn = document.getElementById('cancelChangesBtn');
        const form = document.getElementById('course-form');

        showModalBtn.addEventListener('click', function(e) {
            modal.style.display = 'flex';
        });

        confirmBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            form.submit();
        });

        cancelBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Optional: close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modal.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>