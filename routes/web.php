<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TermsConditionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\CourseFeedbackController;

/*
|--------------------------------------------------------------------------
| صفحة الترحيب والشروط والأحكام
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-terms', function () {
    $terms = DB::table('terms_conditions')->first();
    return response()->json(['content' => $terms->content ?? 'No terms available']);
})->name('get.terms');

/*
|--------------------------------------------------------------------------
| مسارات المصادقة
|--------------------------------------------------------------------------
*/
// تسجيل الدخول والخروج
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// تسجيل حساب جديد
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register');

// الصفحة الرئيسية وتسجيل دخول كضيف
Route::get('/homepage', [AuthController::class, 'Homepage'])->name('homepage');
Route::get('/login/guest', [AuthController::class, 'loginAsGuest'])->name('login.guest');

/*
|--------------------------------------------------------------------------
| مسارات لوحة التحكم (الإدارة)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('admin')->group(function () {
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // إدارة الدورات التدريبية
    Route::get('/courses', [AdminController::class, 'courses'])->name('admin.courses');
    Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse'])->name('courses.destroy');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    
    // إدارة التصنيفات
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // إدارة الشروط والأحكام
    Route::get('/terms', [TermsConditionController::class, 'index'])->name('admin.terms');
    Route::post('/terms', [TermsConditionController::class, 'store'])->name('admin.terms.store');
    
    // إدارة المدربين
    Route::get('/instructors', [AdminController::class, 'instructors'])->name('admin.instructors');
    Route::post('/instructors/toggle/{id}', [AdminController::class, 'toggleConfirmation'])->name('admin.instructors.toggle');
    
    // إدارة البلاغات والتعليقات
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/reports/{id}/details', [AdminController::class, 'reportDetails']);
    Route::put('/reports/{id}', [AdminController::class, 'updateReport'])->name('admin.reports.update');
    Route::delete('/reports/{id}', [AdminController::class, 'deleteReport'])->name('admin.reports.destroy');
    Route::get('/feedbacks', [AdminController::class, 'manageFeedbacks'])->name('admin.feedbacks');
    Route::delete('/feedbacks/{id}', [AdminController::class, 'deleteFeedback'])->name('admin.feedbacks.destroy');
    
    // إدارة المستخدمين
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    
    // إدارة المحادثات
    Route::get('/chats', [ChatController::class, 'adminindex'])->name('admin.chats.index');
    Route::get('/chat/unread-messages', [ChatController::class, 'getUnreadMessages']);
    Route::post('/chat/mark-as-read/{userId}', [ChatController::class, 'markAsRead']);
    Route::get('/chat/unread-count/{userId}', [ChatController::class, 'getUnreadCount'])->name('chat.unread.count');
    Route::get('/chat/all-unread-counts', [ChatController::class, 'getAllUnreadCounts'])->name('chat.all.unread.counts');
});

/*
|--------------------------------------------------------------------------
| مسارات الملف الشخصي
|--------------------------------------------------------------------------
*/
// مسارات الملف الشخصي للمستخدمين والمدربين
Route::middleware(['auth', 'instructor-or-user'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::post('/profile/update/account', [ProfileController::class, 'updateAccount'])->name('profile.updateAccount');
    Route::post('/profile/{profile_id}/toggle-hide', [ProfileController::class, 'toggleHide'])->name('profile.toggleHide');
    Route::delete('/profileinfo/delete', [ProfileController::class, 'deleteProfileInfo'])->name('profileinfo.delete');
    Route::delete('/profile/{profileId}/photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');
});

// مسارات خاصة بالمستخدمين العاديين فقط
Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/favorite-courses', [ProfileController::class, 'FavoriteCourse'])->name('favorite.courses');
    Route::get('/applied-courses', [ProfileController::class, 'AppliedCourses'])->name('applied.courses');
});

// مسارات خاصة بالمدربين
Route::middleware(['auth', 'instructor'])->group(function () {
    
    // ساعات عمل المدرب
    Route::get('/instructor/work-hours', [ProfileController::class, 'showWorkHours'])->name('instructor.workhours');
    Route::post('/instructor/work-hours', [ProfileController::class, 'updateWorkHours'])->name('instructor.workhours.update');
    
    // مسارات ملاحظات المدرب
    Route::get('/profile/feedback', [CourseFeedbackController::class, 'instructorIndex'])->name('instructor.feedback');
    Route::put('/feedback/{id}/status', [CourseFeedbackController::class, 'updateStatus'])->name('feedback.updateStatus');
});

// مسارات الملف الشخصي العامة
Route::get('/profile/{profile_id}', [ProfileController::class, 'profileshowdisplay'])->name('profileshowdisplay');
Route::get('/search', [ProfileController::class, 'search'])->name('search');
Route::get('/instructor-courses/{id}', [ProfileController::class, 'instructorCourses'])->name('instructor.courses');

// مسارات تتطلب تسجيل الدخول (ليس كضيف)
Route::middleware(['auth', 'no-guest'])->group(function () {
    Route::put('/profile/{profileId}', [ProfileController::class, 'photoProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| مسارات الدورات التدريبية
|--------------------------------------------------------------------------
*/
// مسارات الدورات العامة
Route::get('/course', [CourseController::class, 'index'])->name('course.index');

// مسارات الدورات للمدربين
Route::middleware(['auth', 'instructor'])->group(function () {
    Route::get('/course/create', [CourseController::class, 'create'])->name('course.create');
    Route::post('/course', [CourseController::class, 'store'])->name('course.store');
    Route::delete('/course/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
    Route::get('/course/{id}/edit', [CourseController::class, 'edit'])->name('course.edit');
    Route::put('/course/{id}', [CourseController::class, 'update'])->name('course.update');
});

// مسار عرض الدورة (يجب أن يكون بعد المسارات المحددة)
Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');

// مسارات الدورات التي تتطلب تسجيل الدخول (ليس كضيف)
Route::middleware(['auth', 'no-guest'])->group(function () {
    Route::post('/toggle-favorite', [CourseController::class, 'toggleFavorite'])->name('toggle.favorite');
});

// مسارات خاصة بالمستخدمين العاديين
Route::middleware(['auth', 'user'])->group(function () {
    Route::post('/course/apply', [CourseController::class, 'applyCourse'])->name('course.applyCourse');
    Route::post('/apply-course', [CourseController::class, 'applyCourse']);
    Route::post('/course/{id}/placement-test', [CourseController::class, 'submitPlacementTest'])->name('course.submitPlacementTest');
    Route::post('/course/{id}/submit-test', [CourseController::class, 'submitTest'])->name('course.submitTest');
    Route::get('/course/{id}/level/{level}', [CourseController::class, 'showLevel'])->name('course.level');
    
    // مسارات الدورات المكتملة والجارية
    Route::get('/completed-courses', [CourseController::class, 'completedCourses'])
        ->name('completed-courses')
        ->middleware('verified');
    Route::get('/in-progress-courses', [CourseController::class, 'inProgressCourses'])
        ->name('in-progress-courses')
        ->middleware('verified');
});

// مسارات الدورات التي تتطلب تسجيل الدخول
Route::middleware('auth')->group(function () {
    // Individual course status routes have been removed
    // Now using a single endpoint for all course statuses
    Route::get('/courses/all-statuses', [CourseController::class, 'getAllCoursesStatuses']);
});

// مسارات تقارير الدورات
Route::post('/course/report', [CourseController::class, 'report'])->name('course.report');
Route::post('/course/feedback', [CourseFeedbackController::class, 'store'])->name('course.feedback');
/*
|--------------------------------------------------------------------------
| مسارات التعليقات والتقييمات
|--------------------------------------------------------------------------
*/
// مسارات التعليقات
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/reply', [CommentController::class, 'storeReply'])->name('comments.reply');

// مسارات التعليقات التي تتطلب تسجيل الدخول
Route::middleware('auth')->group(function () {
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.delete');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

// مسارات التقييمات
Route::get('/rating', [RatingController::class, 'getRating'])->name('rating.get');
Route::post('/rate', [RatingController::class, 'submitRating'])->name('rate.submit');
Route::get('/ratings/average', [RatingController::class, 'calculateWebsiteRating'])->name('ratings.website_average');
Route::post('/rate/{courseId}', [RatingController::class, 'rateCourse'])->name('rate.course');
Route::get('/ratings/{courseId}', [RatingController::class, 'getUserRating']);
Route::get('/ratings/average/{courseId}', [RatingController::class, 'getAverageRating']);
Route::post('profile/{profileId}/rate', [RatingController::class, 'rateProfile']);
Route::get('profile/{profileId}/rating', [RatingController::class, 'getInstructorRating']);
Route::get('profile/{profileId}/average-rating', [RatingController::class, 'getInstructorAverageRating']);

/*
|--------------------------------------------------------------------------
| مسارات الملاحظات والتغذية الراجعة
|--------------------------------------------------------------------------
*/
// مسارات الملاحظات العامة
Route::post('/feedback', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');
Route::get('/feedback/list', [FeedbackController::class, 'listFeedback'])->name('feedback.list');

// مسارات ملاحظات الدورات
Route::post('/course/feedback', [CourseFeedbackController::class, 'store'])->name('course.feedback');

/*
|--------------------------------------------------------------------------
| مسارات المحادثات والأصدقاء
|--------------------------------------------------------------------------
*/
// مسارات المحادثات
Route::middleware(['auth', 'no-guest'])->group(function () {
    Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/update', [ChatController::class, 'updateMessage'])->name('chat.update');
    Route::post('/chat/delete', [ChatController::class, 'deleteMessage'])->name('chat.delete');
    Route::get('/chat/messages/{id}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send-image', [ChatController::class, 'sendImage'])->name('chat.send.image');
    Route::post('/chat/send-file', [ChatController::class, 'sendFile'])->name('chat.send.file');
    Route::post('/chat/mark-as-read/{senderId}', [ChatController::class, 'markAsRead'])->name('chat.markAsRead');
});

// مسارات الأصدقاء
Route::middleware(['auth', 'instructor-or-user'])->group(function () {
    Route::post('/friend/request/{id}', [FriendController::class, 'sendRequest'])->name('friend.request');
    Route::get('/friends', [FriendController::class, 'showFriends'])->name('friends.list');
    Route::delete('/friend/remove/{id}', [FriendController::class, 'removeFriend'])->name('friend.remove');
    Route::get('/friend-requests', [FriendController::class, 'showFriendRequests'])->name('friend.requests');
    Route::post('/friend/accept/{id}', [FriendController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/friend/reject/{id}', [FriendController::class, 'rejectRequest'])->name('friend.reject');
});
