<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Frontend\DoctorDashboardController;
use App\Http\Controllers\Backend\MedicineController;
use App\Http\Controllers\Frontend\patientController;
use App\Http\Controllers\backend\precriptionController;
use App\Http\Controllers\Frontend\prescriptionController;
use App\Http\Controllers\Backend\MedicinemasterController;
use App\Http\Controllers\Backend\SymptomsController;
use App\Http\Controllers\Backend\onboardingController;
use App\Http\Controllers\middleend\DoctorsController;
use App\Http\Controllers\middleend\ClinicController;
use App\Http\Controllers\Frontend\patientHistory;
use App\Http\Controllers\Frontend\AddmemberController;
use App\Http\Controllers\frontend\Member;
use App\Http\Controllers\frontend\ReceptionistController;
use App\Http\Controllers\frontend\StaffController;
use App\Http\Controllers\frontend\ClinicPortalController;
use App\Http\Controllers\Backend\SubscriptionPlanController;
use App\Http\Controllers\Backend\SubscriptionFeatureController;
use App\Http\Controllers\Backend\SubscriberController;
use App\Http\Controllers\Backend\SystemSettingController;

Route::get('/', function () {
    if (Auth::guard('doctor')->check()) {
        return redirect()->route('nodashboard');
    }
    if (Auth::guard('clinic')->check()) {
        return redirect()->route('clinic.dashboard');
    }
    if (Auth::guard('member')->check()) {
        $member = Auth::guard('member')->user();
        if ($member && $member->role === 'receptionist') {
            return redirect()->route('receptionist.dashboard');
        }
        if ($member && $member->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }
    }
    if (Auth::guard('web')->check()) {
        $user = Auth::guard('web')->user();
        if ($user && $user->role === 'onboarding') {
            return redirect()->route('onboarding.dashboard');
        }
        return redirect()->route('Admindashboard');
    }
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth:web')->group(function () {
    Route::get('/Admindashboard',[AdminController::class, 'index'])->name('Admindashboard');
    Route::get('/adddoctor',[DoctorController::class, 'create'])->name('adddoctor');
    Route::post('/adddoctor',[DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/managedoctor',[DoctorController::class, 'showDoctors'])->name('managedoctor');
    Route::get('/editdoctor/{id}',[DoctorController::class, 'edit'])->name('doctor.edit');
    Route::post('/editdoctor/{id}',[DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/deletedoctor/{id}',[DoctorController::class, 'destroy'])->name('doctor.destroy');
    Route::get('/viewdoctor/{id}',[DoctorController::class, 'view'])->name('viewdoctor');
    Route::get('/promotedoctor/{id}',[DoctorController::class, 'promotedoctor'])->name('promotedoctor');
    Route::get('/verifydoctor/{id}',[DoctorController::class, 'verify'])->name('verifydoctor');
    Route::get('/doctor/{id}/toggle-status',[DoctorController::class, 'toggleStatus'])->name('doctor.toggle_status');
    Route::get('/admin/doctor-requests', [AdminController::class, 'doctorCorrectionRequests'])->name('superadmin.doctor_requests');
    Route::post('/admin/doctor-requests/{id}/approve', [AdminController::class, 'approveDoctorCorrectionRequest'])->name('superadmin.doctor_requests.approve');
    Route::post('/admin/doctor-requests/{id}/reject', [AdminController::class, 'rejectDoctorCorrectionRequest'])->name('superadmin.doctor_requests.reject');
    Route::get('/changepassword/{id}',[AdminController::class, 'changePassword'])->name('changepassword.edit');
    Route::get('/paymentscategory',[DoctorController::class,'paymentcategorys'])->name('paymentscategory');  
    Route::post('/changepassword/{id}',[AdminController::class, 'updatePassword'])->name('changePassword.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
     Route::delete('doctor/destroy/{id}', [DoctorController::class, 'destroy'])->name('doctor.destroy');
     Route::get('/deleteddoctor',[DoctorController::class,'deleteddoctor'])->name('/deleteddoctor');
     Route::get('/patientdetails',[DoctorController::class,'patientdetail'])->name('/patientdetail');
     Route::get('/admin/view-patient/{id}',[DoctorController::class,'viewPatient'])->name('admin.view_patient');
     Route::get('/medicinedetails',[Doctorcontroller::class,'medicinedetails'])->name('/medicinedetails');
     Route::get('/medicinemaster',[MedicinemasterController::class,'index'])->name('/medicinemaster');
     Route::get('/prescription/design',[precriptionController::class,'index'])->name('prescription');
     Route::get('/writeprescriptiondesign1',[precriptionController::class,'Designoneform'])->name('writeprescriptiondesign1');
     Route::get('/Doctor.changePassword',[DoctorController::class,'doctorlist'])->name('Doctor.changePassword');
     Route::post('/doctor/change-password/Doctor', [DoctorController::class, 'updatePassword'])
     ->name('doctor.password.update.admin');

    Route::get('/medicinemaster',[MedicinemasterController::class,'index'])->name('/medicinemaster');
    Route::post('medicinemaster.update',[MedicinemasterController::class,'mediupdate'])->name('medicinemaster.update');
    Route::get('medicine',[MedicineController::class,'create'])->name('medicine');
    Route::get('Addmedicine',[MedicineController::class,'viewform'])->name('Addmedicine');
    Route::post('Addmedicine.store',[MedicineController::class,'store'])->name('Addmedicine.store');
    Route::get('deletemedicine\{id}',[MedicineController::class,'deletemedicine'])->name('deletemedicine');
    Route::delete('/medicine/delete/{id}', [MedicineController::class, 'medicinedestroy'])
     ->name('medicine.delete');
    Route::get('editmedicine\{id}',[MedicineController::class,'editmedicine'])->name('editmedicine');
    Route::post('medicine.update\{id}',[MedicineController::class,'updatemedicine'])->name('medicine.update');

    //category
    Route::get('category', [MedicinemasterController::class, 'category'])
    ->name('category');
    Route::post('category.store', [MedicinemasterController::class, 'store'])
    ->name('category.store');
    Route::get('editcategory/{id}',[MedicinemasterController::class,'edit'])->name('editcategory');
    Route::get('deletecategory/{id}',[MedicinemasterController::class,'delete'])->name('deletecategory');
    Route::post('category.update', [MedicinemasterController::class, 'update'])
    ->name('category.update');

    //dosage
    Route::get('dosage', [MedicinemasterController::class, 'dosage'])->name('dosage');
    Route::post('dosage.add',[MedicinemasterController::class,'DosageAdd'])->name('dosage.add');
    Route::get('deletedosage/{id}',[MedicinemasterController::class,'deletedosage'])->name('deletedosage');
    Route::get('editdosage/{id}',[MedicinemasterController::class,'editdose'])->name('editdosage');
    Route::post('search.dosage',[MedicinemasterController::class,'Searchdosage'])->name('search.dosage');

    //interval
    Route::get('interval', [MedicinemasterController::class, 'interval'])
    ->name('interval');
    Route::post('interval.add', [MedicinemasterController::class, 'add'])
    ->name('interval.add');
    Route::get('deleteinterval/{id}',[MedicinemasterController::class,'deleteinterval'])->name('deleteinterval');
    Route::get('editinterval/{id}',[MedicinemasterController::class,'editinterval'])->name('editinterval');
    Route::post('interval.update',[MedicinemasterController::class,'intervalupdate'])->name('interval.update');

    //duration
     Route::get('duration', [MedicinemasterController::class, 'duration'])
    ->name('duration');
    Route::post('duration.insert', [MedicinemasterController::class, 'insert'])
    ->name('duration.insert');
    Route::get('deleteduration/{id}',[MedicinemasterController::class,'deleteduration'])->name('deleteduration');
    Route::post('duration.update',[MedicinemasterController::class,'durationupdate'])->name('duration.update');

    //unit
    Route::get('unit', [MedicinemasterController::class, 'unit'])         
    ->name('unit');
    Route::post('unit.unitinsert', [MedicinemasterController::class, 'unitinsert'])
    ->name('unit.unitinsert');
    Route::get('deleteunit/{id}',[MedicinemasterController::class,'deleteunit'])->name('deleteunit');
    Route::get('editunit/{id}',[MedicinemasterController::class,'editunit'])->name('editunit');
    Route::post('unit.update',[MedicinemasterController::class,'unitupdate'])->name('unit.update');

    //Symptoms
    Route::get('admin.symptoms',[SymptomsController::class,'index'])->name('admin.symptoms');
    Route::post('symptoms.add',[SymptomsController::class,'add'])->name('symptoms.add');
    Route::delete('deletesymptom/{id}',[SymptomsController::class,'delete'])->name('deletesymptom');
    Route::post('symptoms.masterupdate',[SymptomsController::class,'update'])->name('symptoms.masterupdate');
      // Diagnosis Test
    Route::get('diagnosistest',[SymptomsController::class,'indexTest'])->name('diagnosistest');
    Route::post('diagnosistest.add',[SymptomsController::class,'addTest'])->name('diagnosistest.add');
    Route::delete('deletediagnosistest/{id}',[SymptomsController::class,'deleteTest'])->name('deletediagnosistest');
    Route::put('diagnosistest.update/{id}',[SymptomsController::class,'updateTest'])->name('diagnosistest.update');

    //company
    Route::get('company', [MedicinemasterController::class, 'company'])
    ->name('company');
    Route::post('company.companyadd', [MedicinemasterController::class, 'companyadd'])
    ->name('company.companyadd');
    Route::get('deletecompany/{id}',[MedicinemasterController::class,'deletecompany'])->name('deletecompany');
    Route::post('company.update',[MedicinemasterController::class,'companyupdate'])->name('company.update');

    //Suggestion
    Route::get('/suggestion',[MedicineController::class,'sugestion'])->name('/suggestion');
    Route::post('suggestion.add',[MedicineController::class,'Addsuggestion'])->name('suggestion.add');
    Route::post('/suggestion/update/{id}', [MedicineController::class, 'updatesuggestion'])->name('suggestion.update');
    Route::get('/suggestion.delete/{id}', [MedicineController::class, 'suggestiondestroy'])
     ->name('suggestion.delete');

     //onboarding
    Route::get('onboarding/dashboard', [onboardingController::class, 'dashboard'])->name('onboarding.dashboard');
    Route::get('onboarding', [onboardingController::class, 'Onboardingindex'])->name('admin.onboarding');
    Route::post('onboarding/add', [onboardingController::class, 'Onboardingstore'])->name('onboarding.add');
    Route::put('onboarding/update/{id}', [onboardingController::class, 'updateonboarding'])->name('onboarding.update');
    Route::put('onboarding/deactivate/{id}', [onboardingController::class, 'deactivate'])->name('onboarding.deactivate');
    Route::delete('onboarding/delete/{id}', [onboardingController::class, 'destroy'])->name('onboarding.delete');
    Route::match(['get', 'post'], '/deletedonboarding', [onboardingController::class, 'deletedonboarding'])->name('onboarding.deleted');
    Route::match(['get', 'post'], '/onboarding/restore/{id}', [onboardingController::class, 'restore'])->name('onboarding.restore');

    //onboarding Add doctor
    Route::get('/adddoctor.onboarding',[DoctorsController::class, 'create'])->name('adddoctor.onboarding');
    Route::post('/adddoctor.onboarding.add',[DoctorsController::class,'store'])->name('adddoctor.onboarding.add');
    Route::get('/manage.doctor',[DoctorsController::class,'showdoctor'])->name('/manage.doctor');
    Route::get('/doctor-sidebar-permissions', [DoctorsController::class, 'sidebarPermissions'])->name('doctor.sidebar.permissions');
    Route::get('/prescrpt_header_footer',[DoctorsController::class,'prescrdesign'])->name('/prescrpt_header_footer');
    Route::post('/prescriptionstore',[DoctorsController::class,'prescriptiondocstore'])->name('prescriptionstore');
    Route::get('doctor.onboarding.edit/{id}',[DoctorsController::class,'editdoctors'])->name('doctor.onboarding.edit');
    Route::post('onboarding.doctor.update/{id}',[DoctorsController::class,'updatedoctors'])->name('onboarding.doctor.update');
    Route::get('onboarding.view.doctor/{id}',[DoctorsController::class,'viewdoctor'])->name('onboarding.view.doctor');
    Route::get('/prescription.type/{id?}', [DoctorsController::class, 'showPrescriptionType'])->name('prescription.type');
    Route::post('/doctor/prescription-type/update', [DoctorsController::class, 'updatePrescriptionType'])
    ->name('doctor.prescriptionType.update');
    Route::get('/payment.method', [DoctorsController::class, 'PaymentMethod'])->name('payment.method');
    Route::post('/payment-category/store', [DoctorsController::class, 'paymentstore'])->name('payment-category.store');
    Route::post('/payment-category/{id}/toggle-status', [DoctorsController::class, 'toggleStatus'])
    ->name('payment-category.toggle-status');
    Route::post('/doctor/{id}/toggle-member-access', [DoctorsController::class, 'toggleMemberAccess'])
    ->name('doctor.toggle-member-access');
    Route::post('/doctor/{id}/toggle-payment-category-access', [DoctorsController::class, 'togglePaymentCategoryAccess'])
    ->name('doctor.toggle-payment-category-access');
    Route::post('/doctor/{id}/toggle-deleted-staff-access', [DoctorsController::class, 'toggleDeletedStaffAccess'])
    ->name('doctor.toggle-deleted-staff-access');

    // Onboarding Clinic Management
    Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics.index');
    Route::get('/clinics/permissions', [ClinicController::class, 'permissions'])->name('clinics.permissions');
    Route::post('/clinics/store', [ClinicController::class, 'store'])->name('clinics.store');
    Route::put('/clinics/update/{id}', [ClinicController::class, 'update'])->name('clinics.update');
    Route::delete('/clinics/delete/{id}', [ClinicController::class, 'destroy'])->name('clinics.delete');
    Route::get('/clinics/{id}/verify', [ClinicController::class, 'verify'])->name('clinics.verify');
    Route::post('/clinics/{id}/toggle-verify', [ClinicController::class, 'verify'])->name('clinics.toggle-verify');
    Route::get('/clinics/{id}/toggle-status', [ClinicController::class, 'toggleStatus'])->name('clinics.toggle-status');
    Route::post('/clinic/{id}/toggle-member-access', [ClinicController::class, 'toggleMemberAccess'])->name('clinic.toggle-member-access');
    Route::post('/clinic/{id}/toggle-payment-category-access', [ClinicController::class, 'togglePaymentCategoryAccess'])->name('clinic.toggle-payment-category-access');
    Route::post('/clinic/{id}/toggle-deleted-staff-access', [ClinicController::class, 'toggleDeletedStaffAccess'])->name('clinic.toggle-deleted-staff-access');

    // Subscriptions & Plans Management
    Route::prefix('admin/subscriptions')->name('admin.subscriptions.')->group(function () {
        // Plans
        Route::get('/plans', [SubscriptionPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [SubscriptionPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [SubscriptionPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [SubscriptionPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [SubscriptionPlanController::class, 'update'])->name('plans.update');
        Route::post('/plans/{plan}/toggle-status', [SubscriptionPlanController::class, 'toggleStatus'])->name('plans.toggle-status');
        Route::delete('/plans/{plan}', [SubscriptionPlanController::class, 'destroy'])->name('plans.destroy');

        // Features Catalog
        Route::get('/features', [SubscriptionFeatureController::class, 'index'])->name('features.index');
        Route::post('/features', [SubscriptionFeatureController::class, 'store'])->name('features.store');
        Route::put('/features/{feature}', [SubscriptionFeatureController::class, 'update'])->name('features.update');
        Route::post('/features/{feature}/toggle-status', [SubscriptionFeatureController::class, 'toggleStatus'])->name('features.toggle-status');
        Route::delete('/features/{feature}', [SubscriptionFeatureController::class, 'destroy'])->name('features.destroy');

        // Practice Subscribers
        Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
        Route::post('/subscribers', [SubscriberController::class, 'store'])->name('subscribers.store');
        Route::post('/subscribers/{subscription}/extend', [SubscriberController::class, 'extend'])->name('subscribers.extend');
        Route::post('/subscribers/{subscription}/status', [SubscriberController::class, 'updateStatus'])->name('subscribers.status');
    });

    // System Settings
    Route::get('/admin/settings', [SystemSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/admin/settings', [SystemSettingController::class, 'update'])->name('admin.settings.update');
});
Route::middleware(['auth:doctor', 'subscription.active'])->prefix('doctor')->group(function () {
    Route::get('/profile', [DoctorDashboardController::class, 'viewProfile'])->name('frontend.profile.view');
    Route::post('/profile/correction-request', [DoctorDashboardController::class, 'storeCorrectionRequest'])->name('frontend.profile.correction_request');
    Route::post('/notifications/mark-read', [DoctorDashboardController::class, 'markNotificationsRead'])->name('doctor.notifications.mark_read');
    Route::get('/profile/update', [DoctorDashboardController::class, 'editProfile'])->name('frontend.profile.edit');
    Route::post('/profile/update', [DoctorDashboardController::class, 'updateProfile'])->name('frontend.profile.update');
    Route::get('/changepassword', [DoctorDashboardController::class, 'changepassword'])->name('changepassword');
    Route::post('/change-password', [DoctorDashboardController::class, 'updatePassword'])->name('doctor.password.update');
    Route::get('/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('nodashboard');
    Route::get('/UploadDocument', [DoctorDashboardController::class, 'display'])->name('UploadDocument');
    Route::post('/UploadDocument', [DoctorDashboardController::class, 'insert'])->name('uploadDocument');
    Route::get('/Addpatient', [patientController::class, 'index'])->name('Addpatient');
    Route::post('/Addpatient', [patientController::class, 'store'])->name('Addpatient.store');
    Route::get('/Addpatientdetails', [patientController::class, 'show'])->name('Addpatientdetails');
    Route::get('/patient/edit/{id}', [patientController::class, 'edit'])->name('patient.edit');
    Route::match(['post', 'put'], '/patient/update/{id}', [patientController::class, 'update'])->name('patient.update');
    Route::get('/patient/history/{id}', [patientHistory::class, 'history'])->name('patient.history');
    Route::get('/patient/writeprescription/{id}', [prescriptionController::class, 'index'])->name('patient.writeprescription');
    Route::get('/patient/view/{id}', [patientController::class, 'view'])->name('patient.view');
    Route::get('/patient/delete/{id}', [patientController::class, 'destroy'])->name('patient.delete');
    
    // Symptoms & Diagnosis
    Route::get('/symptoms', [patientController::class, 'symptomsview'])->name('symptoms');
    Route::get('/addsymptoms/{id}', [patientController::class, 'symptomsadd'])->name('addsymptoms');
    Route::post('/symptoms/store', [patientController::class, 'symptomsstore'])->name('symptoms.store');
    Route::post('/prescription/medicine/add', [patientController::class, 'addPrescriptionMedicine'])->name('doctor.prescription.medicine.add');
    Route::delete('/prescription/medicine/{id}', [patientController::class, 'deletePrescriptionMedicine'])->name('doctor.prescription.medicine.delete');
    Route::post('/medicine/quick-add', [patientController::class, 'quickAddMedicine'])->name('doctor.medicine.quick_add');
    
    // Download PDF & Prescription
    Route::get('/downloadpdf', [PrescriptionController::class, 'downloadpdf'])->name('downloadpdf');
    Route::get('/prescription/show/{id}', [PrescriptionController::class, 'index'])->name('prescription.show');
    
    // Staff Management
    Route::get('/addmember', [AddmemberController::class, 'index'])->name('addmember');
    Route::get('/deleted-staff', [\App\Http\Controllers\frontend\AddmemberController::class, 'deletedStaff'])->name('doctor.deleted.staff');
    Route::post('/restore-member/{id}', [\App\Http\Controllers\frontend\AddmemberController::class, 'restore'])->name('doctor.restore.member');
    Route::delete('/member/delete/{id}', [\App\Http\Controllers\frontend\AddmemberController::class, 'destroy'])->name('member.delete');
    Route::get('/member/edit/{id}', [\App\Http\Controllers\frontend\AddmemberController::class, 'edit'])->name('member.edit');
    Route::post('/member/update/{id}', [\App\Http\Controllers\frontend\AddmemberController::class, 'update'])->name('member.update');
    Route::patch('/member/toggle-status/{id}', [\App\Http\Controllers\frontend\AddmemberController::class, 'toggleStatus'])->name('member.toggleStatus');
    Route::post('/member/store', [AddmemberController::class, 'store'])->name('member.store');
    
    // Medicines
    Route::get('/listofmedicine', [MedicineController::class, 'show'])->name('listofmedicine');
    Route::get('/patient/list', [patientController::class, 'symptomsview'])->name('patient.list');

    // Clinic Documents & Payments
    Route::get('/clinicdocument', [DoctorController::class, 'clinicDocuments'])->name('clinic.documents');
    Route::get('/paymentcategory', [DoctorController::class, 'paymentcategory'])->name('paymentcategory');
    Route::post('/payment-category/update-price', [DoctorController::class, 'updatePrice'])->name('doctor.payment-category.update-price');
});

// Receptionist aur Staff (members table) ka login area
Route::middleware(['auth:member', 'subscription.active'])->group(function () {
    Route::get('/receptionist/dashboard', [ReceptionistController::class, 'index'])->name('receptionist.dashboard');
    Route::get('/receptionist/patients', [ReceptionistController::class, 'patients'])->name('receptionist.patients');
    Route::post('/receptionist/patients', [ReceptionistController::class, 'storePatient'])->name('receptionist.patients.store');
    Route::get('/receptionist/payment-categories/{doctorId}', [ReceptionistController::class, 'getDoctorPaymentCategories'])->name('receptionist.doctor.payment_categories');
    Route::get('/receptionist/all-patients', [ReceptionistController::class, 'allPatients'])->name('receptionist.all_patients');
    Route::get('/receptionist/viewhistory/{id}', [ReceptionistController::class, 'viewHistory'])->name('receptionist.viewhistory');
    Route::post('/receptionist/re-register/{id}', [ReceptionistController::class, 'reRegisterForToday'])->name('receptionist.re_register');
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/all-patients', [StaffController::class, 'allPatients'])->name('staff.all_patients');
    Route::get('/staff/physical-exam/{id}', [StaffController::class, 'physicalExam'])->name('staff.physical_exam');
    Route::post('/staff/physical-exam/{id}', [StaffController::class, 'storePhysicalExam'])->name('staff.physical_exam.store');
});

// Clinic (clinics table) ka login area
Route::middleware(['auth:clinic', 'subscription.active'])->prefix('clinic')->name('clinic.')->group(function () {
    Route::get('/dashboard', [ClinicPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/doctors', [ClinicPortalController::class, 'doctors'])->name('doctors');
    Route::post('/doctors/store', [ClinicPortalController::class, 'storeDoctor'])->name('doctors.store');
    Route::get('/doctors/{id}/toggle-status', [ClinicPortalController::class, 'toggleDoctorStatus'])->name('doctors.toggle_status');
    Route::get('/doctors/{id}/verify', [ClinicPortalController::class, 'verifyDoctor'])->name('doctors.verify');
    Route::delete('/doctors/{id}', [ClinicPortalController::class, 'destroyDoctor'])->name('doctors.destroy');
    Route::post('/doctors/{id}/upload-documents', [ClinicPortalController::class, 'uploadDoctorDocuments'])->name('doctors.upload_documents');
    Route::get('/patients', [ClinicPortalController::class, 'patients'])->name('patients');
    Route::get('/payment-categories', [ClinicPortalController::class, 'paymentCategories'])->name('payment_categories');
    Route::post('/payment-categories/update-price', [ClinicPortalController::class, 'updatePrice'])->name('payment_categories.update_price');
    Route::get('/revisit-rule', [ClinicPortalController::class, 'revisitRule'])->name('revisit_rule');
    Route::post('/revisit-rule/update', [ClinicPortalController::class, 'updateRevisitRule'])->name('revisit_rule.update');
    // Staff Management
    Route::get('/staff', [ClinicPortalController::class, 'staff'])->name('staff');
    Route::post('/staff/store', [ClinicPortalController::class, 'storeStaff'])->name('staff.store');
    Route::post('/staff/update/{id}', [ClinicPortalController::class, 'updateStaff'])->name('staff.update');
    Route::patch('/staff/toggle-status/{id}', [ClinicPortalController::class, 'toggleStaffStatus'])->name('staff.toggleStatus');
    Route::match(['get', 'post'], '/staff/{id}/verify', [ClinicPortalController::class, 'verifyStaff'])->name('staff.verify');
    Route::post('/staff/{id}/toggle-verify', [ClinicPortalController::class, 'verifyStaff'])->name('staff.toggle-verify');
    Route::delete('/staff/delete/{id}', [ClinicPortalController::class, 'destroyStaff'])->name('staff.destroy');
    Route::get('/deleted-staff', [ClinicPortalController::class, 'deletedStaff'])->name('deleted_staff');
    Route::post('/restore-staff/{id}', [ClinicPortalController::class, 'restoreStaff'])->name('restore_staff');

    // Prescription Design & Branding Assets
    Route::get('/prescription-design', [ClinicPortalController::class, 'prescriptionDesign'])->name('prescription_design');

    // Doctor Profile Correction Requests
    Route::get('/doctor-requests', [ClinicPortalController::class, 'correctionRequests'])->name('doctor_requests');
    Route::post('/doctor-requests/{id}/approve', [ClinicPortalController::class, 'approveCorrectionRequest'])->name('doctor_requests.approve');
    Route::post('/doctor-requests/{id}/reject', [ClinicPortalController::class, 'rejectCorrectionRequest'])->name('doctor_requests.reject');

    Route::get('/profile', [ClinicPortalController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ClinicPortalController::class, 'updateProfile'])->name('profile.update');
    Route::post('/change-password', [ClinicPortalController::class, 'updatePassword'])->name('password.update');
});

require __DIR__.'/auth.php';