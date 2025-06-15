<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Course</title>
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
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
                <h1 class="form-title">Create Course</h1>
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

            <form class="form" action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data" id="course-form">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="title">Course Title</label>
                    <input type="text" id="title" name="title" class="form-input" 
                           placeholder="Enter course title" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <select id="category" name="category_id" class="form-select" required>
                        <option value="">Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="prerequisite_id">Prerequisite Course (Optional)</label>
                    <select id="prerequisite_id" name="prerequisite_id" class="form-select">
                        <option value="">No Prerequisite</option>
                        @foreach ($instructorCourses as $course)
                            <option value="{{ $course->id }}" {{ old('prerequisite_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Placement Test Section -->
                <div id="placement-test-section" style="display: none;">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="enable_placement_test" id="enable_placement_test" value="1">
                            <label class="form-check-label" for="enable_placement_test">
                                Enable Placement Test
                            </label>
                        </div>
                    </div>

                    <div id="placement-test-questions" style="display: none;">
                        <div class="form-group">
                            <label class="form-label">Passing Score (%)</label>
                            <input type="number" name="placement_pass_score" class="form-input" min="1" max="100" value="70">
                        </div>

                        <div id="placement-questions-container">
                            <!-- Questions will be added here dynamically -->
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
                </script>

                <div class="form-group">
                    <label class="form-label" for="difficulty_level">Course Difficulty</label>
                    <select class="form-select" id="difficulty_level" name="difficulty_level" required>
                        <option value="">Select difficulty level</option>
                        <option value="beginner" {{ old('difficulty_level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="coursepreview">Course Preview</label>
                    <textarea class="form-textarea" id="coursepreview" 
                              name="coursepreview" placeholder="Enter course preview" required>{{ old('coursepreview') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Course Description</label>
                    <textarea class="form-textarea" id="content" 
                              name="content" placeholder="Enter course description" required>{{ old('content') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Thumbnail Image</label>
                    <div class="file-upload">
                        <label for="photo">
                            <i class="fas fa-image"></i> Upload Thumbnail
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <small>Upload image in JPG, PNG format (max 2MB)</small>
                    <div class="images-preview-container" id="thumbnail-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Course Video (Optional)</label>
                    <div class="file-upload">
                        <label for="video">
                            <i class="fas fa-video"></i> Upload Video
                        </label>
                        <input type="file" id="video" name="video" accept="video/*">
                    </div>
                    <small>Upload video in MP4 format (max 10MB)</small>
                    <div class="images-preview-container" id="video-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Course Images (Multiple)</label>
                    <div class="file-upload">
                        <label for="course_images">
                            <i class="fas fa-images"></i> Upload Images
                        </label>
                        <input type="file" id="course_images" name="course_images[]" 
                               accept="image/*" multiple>
                    </div>
                    <small>You can upload multiple images (JPG, PNG format)</small>
                    
                    <div class="images-preview-container" id="images-preview"></div>
                </div>

                <!-- Course Files Section -->
                <div class="form-group">
                    <label class="form-label">Course Files (PDF, DOC, XLS, PPT, ZIP, RAR)</label>
                    
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
                    <div id="levels-container"></div>
                    <button type="button" class="btn btn-primary" id="add-level-btn">
                        <i class="fas fa-plus"></i> Add Level
                    </button>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Create Course
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
                <label class="form-label">Passing Score (%)</label>
                <input type="number" name="levels[0][passing_score]" class="form-input" min="1" max="100" value="70" required>
                <small class="text-muted">Minimum percentage of correct answers needed to pass this level</small>
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
                
                <!-- حقول النصوص -->
                <div class="content-fields text-fields active" id="text-fields-0">
                    <div class="form-group">
                        <label class="form-label">Text Contents</label>
                        <div class="text-contents-container" id="text-contents-0">
                            <div class="text-content-item">
                                <textarea name="levels[0][text_contents][]" class="form-textarea" placeholder="Enter text content"></textarea>
                                <button type="button" class="btn btn-danger remove-text-btn">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-2" onclick="addTextContent(0)">
                            <i class="fas fa-plus"></i> Add Text
                        </button>
                    </div>
                </div>
                
                <!-- حقول الصور -->
                <div class="content-fields image-fields" id="image-fields-0">
                    <div class="form-group">
                        <label class="form-label">Images</label>
                        <div class="file-upload">
                            <label for="level-images-0">
                                <i class="fas fa-images"></i> Upload Images
                            </label>
                            <input type="file" id="level-images-0" name="levels[0][images][]" class="form-control-file level-images" multiple accept="image/*">
                        </div>
                        <div class="preview-container" id="image-preview-0"></div>
                    </div>
                </div>
                
                <!-- حقول الفيديوهات -->
                <div class="content-fields video-fields" id="video-fields-0">
                    <div class="form-group">
                        <label class="form-label">Videos</label>
                        <div class="file-upload">
                            <label for="level-videos-0">
                                <i class="fas fa-video"></i> Upload Videos
                            </label>
                            <input type="file" id="level-videos-0" name="levels[0][videos][]" class="form-control-file level-videos" multiple accept="video/*">
                        </div>
                        <div class="preview-container" id="video-preview-0"></div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const levelsContainer = document.getElementById('levels-container');
            const addLevelBtn = document.getElementById('add-level-btn');
            const levelTemplate = document.getElementById('level-template');
            const questionTemplate = document.getElementById('question-template');
            const imagesInput = document.getElementById('course_images');
            const imagesPreview = document.getElementById('images-preview');
            const courseForm = document.getElementById('course-form');
            let levelCounter = 0;

            // Add new level
            addLevelBtn.addEventListener('click', function() {
                const newLevel = levelTemplate.content.cloneNode(true);
                const levelElement = newLevel.querySelector('.level-container');
                const levelIndex = levelCounter;
                levelCounter++;
                
                levelElement.dataset.levelIndex = levelIndex;
                levelElement.querySelector('.level-number').textContent = levelIndex + 1;
                updateElementNames(levelElement, levelIndex);
                levelsContainer.appendChild(newLevel);
                
                // Add first question automatically
                const addQuestionBtn = levelElement.querySelector('.add-question-btn');
                if (addQuestionBtn) {
                    addQuestionBtn.click();
                }
            });

            // Remove level
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-level-btn')) {
                    if (confirm('Are you sure you want to remove this level?')) {
                        e.target.closest('.level-container').remove();
                        renumberLevels();
                    }
                }
                
                // Manejar la eliminación de textos
                if (e.target.closest('.remove-text-btn')) {
                    const textItem = e.target.closest('.text-content-item');
                    const textContainer = textItem.parentElement;
                    
                    // Solo eliminar si hay más de un elemento de texto (mantener al menos uno)
                    if (textContainer.querySelectorAll('.text-content-item').length > 1) {
                        textItem.remove();
                    } else {
                        alert('Debe mantener al menos un elemento de texto en cada nivel');
                    }
                }
            });

            // Add new question
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

            // Remove question
            levelsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-question-btn')) {
                    if (confirm('Are you sure you want to remove this question?')) {
                        e.target.closest('.question-container').remove();
                    }
                }
            });

// Add option to question
levelsContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-option-btn')) {
        const questionContainer = e.target.closest('.question-container');
        const optionsList = questionContainer.querySelector('.options-list');
        const optionCount = optionsList.querySelectorAll('.option-input').length;
        
        if (optionCount >= 5) {
            alert('Maximum 5 options allowed per question');
            return;
        }
        
        // الحصول على مستوى السؤال ورقمه
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

            // Initialize file upload functionality
            const filesInput = document.getElementById('course_files');
            const filesPreview = document.getElementById('files-preview');
            
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
            
            // Reset file input when clicking to allow selecting the same file again
            if (filesInput) {
                filesInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                    this.value = ''; // Reset to allow selecting the same file again if needed
                });
            }
            
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
            
            // Course images preview
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
            
            // Handle image removal from preview
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

            // Form validation before submit
            courseForm.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Check if at least one level exists
                if (levelsContainer.querySelectorAll('.level-container').length === 0) {
                    alert('Please add at least one level');
                    isValid = false;
                }
                
                // Check each level has at least one question
                levelsContainer.querySelectorAll('.level-container').forEach(level => {
                    if (level.querySelectorAll('.question-container').length === 0) {
                        alert('Each level must have at least one question');
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Add first level automatically
            addLevelBtn.click();

            function updateElementNames(container, levelIndex) {
                const elements = container.querySelectorAll('[name]');
                elements.forEach(el => {
                    const name = el.getAttribute('name')
                        .replace(/levels\[\d+\]/, `levels[${levelIndex}]`);
                    el.setAttribute('name', name);
                });
                
                const fileInput = container.querySelector('.level-content-file');
                const fileLabel = container.querySelector('.file-upload label');
                if (fileInput && fileLabel) {
                    const newId = `level-content-${levelIndex}`;
                    fileInput.id = newId;
                    fileLabel.setAttribute('for', newId);
                }
                
                const orderInput = container.querySelector('input[name$="[order]"]');
                if (orderInput) {
                    orderInput.value = levelIndex + 1;
                }

                // Update content fields
                container.querySelectorAll('[name^="levels[0][text_contents]"]').forEach(el => {
                    el.name = el.name.replace(/levels\[\d+\]/, `levels[${levelIndex}]`);
                });
                
                container.querySelectorAll('[name^="levels[0][images]"]').forEach(el => {
                    el.name = el.name.replace(/levels\[\d+\]/, `levels[${levelIndex}]`);
                });
                
                container.querySelectorAll('[name^="levels[0][videos]"]').forEach(el => {
                    el.name = el.name.replace(/levels\[\d+\]/, `levels[${levelIndex}]`);
                });
                
                // Update IDs for content fields
                const textContainer = container.querySelector('.text-contents-container');
                if (textContainer) {
                    textContainer.id = `text-contents-${levelIndex}`;
                }
                
                const imageInput = container.querySelector('.level-images');
                const imageLabel = container.querySelector('label[for^="level-images-"]');
                const imagePreview = container.querySelector('#image-preview-0');
                
                if (imageInput && imageLabel && imagePreview) {
                    imageInput.id = `level-images-${levelIndex}`;
                    imageLabel.setAttribute('for', `level-images-${levelIndex}`);
                    imagePreview.id = `image-preview-${levelIndex}`;
                }
                
                const videoInput = container.querySelector('.level-videos');
                const videoLabel = container.querySelector('label[for^="level-videos-"]');
                const videoPreview = container.querySelector('#video-preview-0');
                
                if (videoInput && videoLabel && videoPreview) {
                    videoInput.id = `level-videos-${levelIndex}`;
                    videoLabel.setAttribute('for', `level-videos-${levelIndex}`);
                    videoPreview.id = `video-preview-${levelIndex}`;
                }
                
                // Update the "Add Text" button onclick attribute
                const addTextBtn = container.querySelector('button[onclick^="addTextContent("]');
                if (addTextBtn) {
                    addTextBtn.setAttribute('onclick', `addTextContent(${levelIndex})`);
                }
            }

            function updateQuestionNames(container, levelIndex, questionIndex) {
    // Update question text input
    const questionTextInput = container.querySelector('input[name="question_text"]');
    questionTextInput.name = `levels[${levelIndex}][questions][${questionIndex}][question_text]`;
    
    // Update correct answer radio buttons
    const radioButtons = container.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(radio => {
        radio.name = `levels[${levelIndex}][questions][${questionIndex}][correct_answer]`;
    });
    
    // Update options - هذه هي التعديلات الرئيسية
    const optionInputs = container.querySelectorAll('.option-input');
    optionInputs.forEach((optionDiv, index) => {
        const textInput = optionDiv.querySelector('input[type="text"]');
        textInput.name = `levels[${levelIndex}][questions][${questionIndex}][options][${index}]`;
        
        // Update corresponding radio button value
        const radio = optionDiv.querySelector('input[type="radio"]');
        if (radio) {
            radio.value = index;
        }
    });
}

            function renumberLevels() {
                const levels = levelsContainer.querySelectorAll('.level-container');
                levels.forEach((level, index) => {
                    const newIndex = index;
                    level.dataset.levelIndex = newIndex;
                    level.querySelector('.level-number').textContent = newIndex + 1;
                    updateElementNames(level, newIndex);
                    
                    // Renumber questions in this level
                    const questions = level.querySelectorAll('.question-container');
                    questions.forEach((question, qIndex) => {
                        updateQuestionNames(question, newIndex, qIndex);
                    });
                });
                levelCounter = levels.length;
            }
        });

        function toggleContentType(element, levelIndex) {
            const type = element.dataset.type;
            const container = element.closest('.level-container');
            
            // Toggle active class for the option
            container.querySelectorAll('.content-type-option').forEach(opt => {
                opt.classList.remove('active');
            });
            element.classList.add('active');
            
            // Toggle content fields
            container.querySelectorAll('.content-fields').forEach(field => {
                field.classList.remove('active');
            });
            container.querySelector(`.${type}-fields`).classList.add('active');
        }
        
        function addTextContent(levelIndex) {
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
        }
        
        // معاينة الصور والفيديوهات قبل الرفع
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('level-images')) {
                const levelIndex = e.target.id.split('-')[2];
                const previewContainer = document.getElementById(`image-preview-${levelIndex}`);
                previewContainer.innerHTML = '';
                
                Array.from(e.target.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'preview-item';
                        previewItem.innerHTML = `
                            <img src="${event.target.result}" alt="Preview">
                            <button type="button" class="remove-preview" onclick="removePreviewItem(this, 'image', ${levelIndex}, ${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        previewContainer.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                });
            }
            
            if (e.target.classList.contains('level-videos')) {
                const levelIndex = e.target.id.split('-')[2];
                const previewContainer = document.getElementById(`video-preview-${levelIndex}`);
                previewContainer.innerHTML = '';
                
                Array.from(e.target.files).forEach((file, index) => {
                    const videoUrl = URL.createObjectURL(file);
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <video controls>
                            <source src="${videoUrl}" type="${file.type}">
                        </video>
                        <button type="button" class="remove-preview" onclick="removePreviewItem(this, 'video', ${levelIndex}, ${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(previewItem);
                });
            }
        });
        
        function removePreviewItem(button, type, levelIndex, fileIndex) {
            const container = button.closest('.preview-item');
            container.remove();
            
            // إزالة الملف من input
            const input = type === 'image' 
                ? document.querySelector(`#level-images-${levelIndex}`)
                : document.querySelector(`#level-videos-${levelIndex}`);
            
            const files = Array.from(input.files);
            files.splice(fileIndex, 1);
            
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }
    </script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
</body>
</html>