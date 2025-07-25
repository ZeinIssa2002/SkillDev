<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/reg.css') }}">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .terms-agreement {
            margin: 15px 0;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .terms-agreement a {
            color: #0066cc;
            text-decoration: underline;
            cursor: pointer;
        }
        .terms-agreement a:hover {
            color: #004499;
        }
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
            white-space: pre-line;
        }
        .form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .error-messages {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <form class="form" id="registerForm" action="{{ route('register') }}" method="POST">
        @csrf
        <p class="title">Register</p>
        <p class="message">Signup now and get full access.</p>

        @if($errors->any())
            <div id="errorMessages" class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <label>
            <span>Email</span>
            <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" maxlength="40" required>
        </label>

        <label>
            <span>Password</span>
            <input class="input" type="password" name="password" placeholder="Password" maxlength="40" required>
        </label>

        <label>
            <span>Confirm Password</span>
            <input class="input" type="password" name="password_confirmation" placeholder="Confirm Password" maxlength="40" required>
        </label>

        <div class="account-type">
            <label>
                Register as Instructor
                <input type="checkbox" name="is_instructor" value="1">
            </label>
        </div>

        <div class="terms-agreement">
            <label>
                <input type="checkbox" name="agree_to_terms" id="agreeCheckbox" required>
                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
            </label>
        </div>

        <button type="submit" class="submit">Submit</button>

        <p class="signin">Already have an account? <a href="{{ route('login') }}">Signin</a></p>
    </form>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms & Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="termsContent">
                    Loading terms and conditions...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="acceptTerms" data-bs-dismiss="modal">I Accept</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    
    <script>
    $(document).ready(function() {
        // Load terms content when modal opens
        $('#termsModal').on('show.bs.modal', function () {
            $.get("{{ route('get.terms') }}", function(data) {
                $('#termsContent').html(data.content || 'No terms available');
            }).fail(function() {
                $('#termsContent').html('Failed to load terms and conditions');
            });
        });

        // Auto-check the agreement checkbox when "I Accept" is clicked
        $('#acceptTerms').click(function() {
            $('#agreeCheckbox').prop('checked', true);
        });

        // Prevent form submission if terms are not agreed
        $('#registerForm').submit(function(e) {
            if (!$('#agreeCheckbox').is(':checked')) {
                e.preventDefault();
                alert('You must agree to the terms and conditions');
                $('#termsModal').modal('show');
            }
        });
    });
    </script>
</body>
</html>