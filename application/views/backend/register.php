<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<main class="main section mt-3">
  <div class="container mt-5">
    <h2 class="mb-3">Are you a teacher? Be a member!</h2>
    <p>Complete the form below to join SNAT Burial Scheme</p>

    <!-- Progress Steps -->
    <div class="row mb-4">
      <div class="col-12">
        <ul class="nav nav-pills nav-fill" id="step-indicator">
          <li class="nav-item">
            <a class="nav-link active step-link" data-step="1" href="#step1">
              <span class="step-number">1</span> Personal Info
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link step-link" data-step="2" href="#step2">
              <span class="step-number">2</span> Employment Info
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link step-link" data-step="3" href="#step3">
              <span class="step-number">3</span> Beneficiaries
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link step-link" data-step="4" href="#step4">
              <span class="step-number">4</span> Documents
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link step-link" data-step="5" href="#step5">
              <span class="step-number">5</span> Preview & Submit
            </a>
          </li>
        </ul>
      </div>
    </div>

    <form method="post" action="<?= base_url(); ?>index.php?login/submit_application" enctype="multipart/form-data" id="registrationForm" class="row g-3">
      
      <!-- STEP 1: Personal Info -->
      <div class="step-content" id="step1-content">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-person-fill me-2"></i>Step 1: Personal Information</h4>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Surname <span class="text-danger">*</span></label>
                <input type="text" name="surname" id="surname" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">ID Number <span class="text-danger">*</span></label>
                <input type="text" name="idnumber" id="idnumber" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="date" name="dob" id="dob" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="gender" id="gender" class="form-control" required>
                  <option value="">Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Cell Number (MOMO Active) <span class="text-danger">*</span></label>
                <input type="text" name="cellnumber" id="cellnumber" class="form-control" required>
              </div>

              <div class="col-md-12">
                <label class="form-label">Residential Address <span class="text-danger">*</span></label>
                <textarea name="resident" id="resident" class="form-control" required></textarea>
              </div>

              <div class="col-md-6">
                <label class="form-label">Passbook Number</label>
                <input type="text" name="passbook_no" id="passbook_no" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(2)">Next: Employment Info <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
      </div>

      <!-- STEP 2: Employment Info -->
      <div class="step-content" id="step2-content" style="display:none;">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Step 2: Employment Information</h4>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Employee Number <span class="text-danger">*</span></label>
                <input type="text" name="employeeno" id="employeeno" class="form-control" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">TSC Number <span class="text-danger">*</span></label>
                <input type="text" name="tscno" id="tscno" class="form-control" required>
              </div>

              <div class="col-md-12">
                <label class="form-label">School Code <span class="text-danger">*</span></label>
                <input type="text" name="schoolcode" id="schoolcode" class="form-control" required>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-secondary btn-lg" onclick="nextStep(1)"><i class="bi bi-arrow-left me-2"></i>Previous</button>
          <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(3)">Next: Beneficiaries <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
      </div>

      <!-- STEP 3: Beneficiaries -->
      <div class="step-content" id="step3-content" style="display:none;">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Step 3: Beneficiaries</h4>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Register up to 5 beneficiaries now. You can add more if needed.</p>
            
            <!-- Beneficiary Section -->
            <div id="beneficiaries_area">
              <?php for($i=1; $i<=5; $i++): ?>
              <div class="beneficiary-item mb-3 p-3 border rounded">
                <h5 class="mb-3">Beneficiary <?php echo $i; ?></h5>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Beneficiary <?php echo $i; ?> Name</label>
                    <input type="text" name="beneficiaries[]" class="form-control beneficiary-name" placeholder="Enter beneficiary name">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Attach Beneficiary ID</label>
                    <input type="file" name="beneficiary_ids[]" class="form-control beneficiary-file" accept=".pdf,.jpg,.jpeg,.png">
                  </div>
                </div>
              </div>
              <?php endfor; ?>
            </div>

            <button type="button" id="add_more" class="btn btn-secondary mb-3">
              <i class="bi bi-plus-circle me-2"></i>Add More Beneficiaries
            </button>

            <!-- Monthly Cost Preview -->
            <div class="alert alert-info mt-3">
              <strong>Expected Monthly Policy Payment:</strong> 
              <span id="monthly_payment" class="fs-5">E0.00</span>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-secondary btn-lg" onclick="nextStep(2)"><i class="bi bi-arrow-left me-2"></i>Previous</button>
          <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(4)">Next: Documents <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
      </div>

      <!-- STEP 4: Documents -->
      <div class="step-content" id="step4-content" style="display:none;">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-file-earmark-fill me-2"></i>Step 4: Application Documents</h4>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Please upload all required documents. You can add multiple documents as needed.</p>
            
            <!-- Documents Section -->
            <div id="documents_area">
              <div class="document-item mb-3 p-3 border rounded">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Document Type <span class="text-danger">*</span></label>
                    <select name="document_type[]" class="form-control document-type" required>
                      <option value="">Select Type</option>
                      <option value="ID">ID Document</option>
                      <option value="payslip">Payslip</option>
                      <option value="reciept">Receipt</option>
                      <option value="proof_of_payment">Proof of Payment</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Upload Document <span class="text-danger">*</span></label>
                    <input type="file" name="documents[]" class="form-control document-file" accept=".pdf,.jpg,.jpeg,.png" required>
                    <small class="text-muted">PDF, JPG, JPEG, PNG (Max 5MB)</small>
                  </div>
                  <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-document" style="display:none;">
                      <i class="bi bi-trash"></i> Remove
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" id="add_document" class="btn btn-secondary mb-3">
              <i class="bi bi-plus-circle me-2"></i>Add Another Document
            </button>

            <div class="alert alert-info mt-3">
              <strong>Note:</strong> At minimum, please upload your ID document. Additional documents like payslip, receipt, or proof of payment may be required.
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-secondary btn-lg" onclick="nextStep(3)"><i class="bi bi-arrow-left me-2"></i>Previous</button>
          <button type="button" class="btn btn-primary btn-lg" onclick="nextStep(5)">Next: Preview & Submit <i class="bi bi-arrow-right ms-2"></i></button>
        </div>
      </div>

      <!-- STEP 5: Preview & Submit -->
      <div class="step-content" id="step5-content" style="display:none;">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-check-circle-fill me-2"></i>Step 4: Review & Submit</h4>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Please review all your information before submitting. You can go back to make changes if needed.</p>
            
            <!-- Personal Info Preview -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-2"><strong>Surname:</strong> <span id="preview-surname"></span></div>
                  <div class="col-md-6 mb-2"><strong>First Name:</strong> <span id="preview-name"></span></div>
                  <div class="col-md-6 mb-2"><strong>ID Number:</strong> <span id="preview-idnumber"></span></div>
                  <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> <span id="preview-dob"></span></div>
                  <div class="col-md-6 mb-2"><strong>Gender:</strong> <span id="preview-gender"></span></div>
                  <div class="col-md-6 mb-2"><strong>Cell Number:</strong> <span id="preview-cellnumber"></span></div>
                  <div class="col-md-6 mb-2"><strong>Passbook Number:</strong> <span id="preview-passbook_no"></span></div>
                  <div class="col-md-12 mb-2"><strong>Residential Address:</strong> <span id="preview-resident"></span></div>
                </div>
              </div>
            </div>

            <!-- Employment Info Preview -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-briefcase-fill me-2"></i>Employment Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 mb-2"><strong>Employee Number:</strong> <span id="preview-employeeno"></span></div>
                  <div class="col-md-6 mb-2"><strong>TSC Number:</strong> <span id="preview-tscno"></span></div>
                  <div class="col-md-6 mb-2"><strong>School Code:</strong> <span id="preview-schoolcode"></span></div>
                </div>
              </div>
            </div>

            <!-- Documents Preview -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-file-earmark-fill me-2"></i>Documents</h5>
              </div>
              <div class="card-body">
                <div id="preview-documents">
                  <p class="text-muted">No documents uploaded yet.</p>
                </div>
              </div>
            </div>

            <!-- Beneficiaries Preview -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Beneficiaries</h5>
              </div>
              <div class="card-body">
                <div id="preview-beneficiaries">
                  <p class="text-muted">No beneficiaries added yet.</p>
                </div>
              </div>
            </div>

            <!-- Monthly Payment -->
            <div class="alert alert-info">
              <h5><strong>Expected Monthly Policy Payment:</strong> <span id="preview-monthly_payment" class="fs-4">E0.00</span></h5>
            </div>

            <p class="text-muted">
              After submitting your registration, one of our consultants will contact you 
              within <strong>48 hours</strong> to complete your onboarding.
            </p>

            <!-- Terms & Conditions -->
            <div class="form-check mb-3 border p-3 rounded">
              <input class="form-check-input" type="checkbox" id="agreeTerms" required>
              <label class="form-check-label" for="agreeTerms">
                By clicking here, you agree to our 
                <a href="terms.php" target="_blank">Terms & Conditions of the SNAT Burial Scheme</a>.
              </label>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-secondary btn-lg" onclick="nextStep(4)"><i class="bi bi-arrow-left me-2"></i>Previous</button>
          <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
            <i class="bi bi-check-circle me-2"></i>Submit Registration
          </button>
        </div>
      </div>

    </form>

  </div>
</main>

<style>
.step-link {
  cursor: pointer;
  transition: all 0.3s;
}
.step-link.active {
  background-color: #0d6efd !important;
  color: white !important;
}
.step-link.completed {
  background-color: #198754 !important;
  color: white !important;
}
.step-number {
  display: inline-block;
  width: 30px;
  height: 30px;
  line-height: 30px;
  border-radius: 50%;
  background-color: #e9ecef;
  margin-right: 8px;
}
.step-link.active .step-number,
.step-link.completed .step-number {
  background-color: white;
  color: #0d6efd;
}
.step-link.completed .step-number {
  color: #198754;
}
.step-content {
  animation: fadeIn 0.3s;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
.beneficiary-item {
  background-color: #f8f9fa;
}
.document-item {
  background-color: #f8f9fa;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentStep = 1;
const totalSteps = 5;

// Navigate to step
function nextStep(step) {
  // Validate current step before moving forward
  if (step > currentStep) {
    if (!validateStep(currentStep)) {
      return;
    }
  }

  // Hide all steps
  $('.step-content').hide();
  
  // Show selected step
  $('#step' + step + '-content').show();
  
  // Update step indicators
  updateStepIndicator(step);
  
  // Update preview if step 5
  if (step === 5) {
    updatePreview();
  }
  
  currentStep = step;
  
  // Scroll to top
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Update step indicator
function updateStepIndicator(activeStep) {
  $('.step-link').each(function(index) {
    const stepNum = index + 1;
    $(this).removeClass('active completed');
    
    if (stepNum < activeStep) {
      $(this).addClass('completed');
    } else if (stepNum === activeStep) {
      $(this).addClass('active');
    }
  });
}

// Validate step
function validateStep(step) {
  let isValid = true;
  let firstError = null;

  if (step === 1) {
    const required = ['#surname', '#name', '#idnumber', '#dob', '#gender', '#cellnumber', '#resident'];
    required.forEach(selector => {
      const field = $(selector);
      if (!field.val()) {
        isValid = false;
        field.addClass('is-invalid');
        if (!firstError) firstError = field;
      } else {
        field.removeClass('is-invalid');
      }
    });
  } else if (step === 2) {
    const required = ['#employeeno', '#tscno', '#schoolcode'];
    required.forEach(selector => {
      const field = $(selector);
      if (!field.val()) {
        isValid = false;
        field.addClass('is-invalid');
        if (!firstError) firstError = field;
      } else {
        field.removeClass('is-invalid');
      }
    });
  } else if (step === 4) {
    // Validate at least one document is uploaded
    let hasDocument = false;
    $('.document-file').each(function() {
      if ($(this)[0].files.length > 0) {
        hasDocument = true;
        const type = $(this).closest('.document-item').find('.document-type').val();
        if (!type) {
          isValid = false;
          $(this).closest('.document-item').find('.document-type').addClass('is-invalid');
          if (!firstError) firstError = $(this).closest('.document-item').find('.document-type');
        }
      }
    });
    if (!hasDocument) {
      isValid = false;
      alert('Please upload at least one document.');
    }
  }

  if (!isValid && firstError) {
    alert('Please fill in all required fields before proceeding.');
    firstError.focus();
    if (firstError.is('select')) {
      firstError[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  return isValid;
}

// Add More Beneficiaries
let beneficiaryCount = 5;
$('#add_more').click(function(){
  beneficiaryCount++;
  const newBeneficiary = `
    <div class="beneficiary-item mb-3 p-3 border rounded">
      <h5 class="mb-3">Beneficiary ${beneficiaryCount}</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Beneficiary ${beneficiaryCount} Name</label>
          <input type="text" name="beneficiaries[]" class="form-control beneficiary-name" placeholder="Enter beneficiary name">
        </div>
        <div class="col-md-6">
          <label class="form-label">Attach Beneficiary ID</label>
          <input type="file" name="beneficiary_ids[]" class="form-control beneficiary-file" accept=".pdf,.jpg,.jpeg,.png">
        </div>
      </div>
    </div>
  `;
  $('#beneficiaries_area').append(newBeneficiary);
});

// Add More Documents
let documentCount = 1;
$('#add_document').click(function(){
  documentCount++;
  const newDocument = `
    <div class="document-item mb-3 p-3 border rounded">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Document Type <span class="text-danger">*</span></label>
          <select name="document_type[]" class="form-control document-type" required>
            <option value="">Select Type</option>
            <option value="ID">ID Document</option>
            <option value="payslip">Payslip</option>
            <option value="reciept">Receipt</option>
            <option value="proof_of_payment">Proof of Payment</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Upload Document <span class="text-danger">*</span></label>
          <input type="file" name="documents[]" class="form-control document-file" accept=".pdf,.jpg,.jpeg,.png" required>
          <small class="text-muted">PDF, JPG, JPEG, PNG (Max 5MB)</small>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn btn-danger btn-sm remove-document">
            <i class="bi bi-trash"></i> Remove
          </button>
        </div>
      </div>
    </div>
  `;
  $('#documents_area').append(newDocument);
  
  // Show remove buttons if more than one document
  if ($('.document-item').length > 1) {
    $('.remove-document').show();
  }
});

// Remove Document
$(document).on('click', '.remove-document', function(){
  $(this).closest('.document-item').remove();
  
  // Hide remove buttons if only one document left
  if ($('.document-item').length <= 1) {
    $('.remove-document').hide();
  }
});

// Calculate Monthly Payment
function calculatePayment(){
  let beneficiariesCount = $(".beneficiary-name").filter(function(){ 
    return $(this).val().trim() !== ""; 
  }).length;

  let base = 20;
  let extra = beneficiariesCount > 5 ? (beneficiariesCount - 5) * 15 : 0;
  let total = base + extra;

  $('#monthly_payment').text('E' + total + '.00');
  $('#preview-monthly_payment').text('E' + total + '.00');
}

// Update payment calculation on change
$('#beneficiaries_area').on('input', '.beneficiary-name', function(){
  calculatePayment();
});

setInterval(calculatePayment, 1000);

// Update Preview
function updatePreview() {
  // Personal Info
  $('#preview-surname').text($('#surname').val() || 'N/A');
  $('#preview-name').text($('#name').val() || 'N/A');
  $('#preview-idnumber').text($('#idnumber').val() || 'N/A');
  $('#preview-dob').text($('#dob').val() || 'N/A');
  $('#preview-gender').text($('#gender option:selected').text() || 'N/A');
  $('#preview-cellnumber').text($('#cellnumber').val() || 'N/A');
  $('#preview-passbook_no').text($('#passbook_no').val() || 'N/A');
  $('#preview-resident').text($('#resident').val() || 'N/A');
  
  // Employment Info
  $('#preview-employeeno').text($('#employeeno').val() || 'N/A');
  $('#preview-tscno').text($('#tscno').val() || 'N/A');
  $('#preview-schoolcode').text($('#schoolcode').val() || 'N/A');
  
  // Documents Preview
  const documentsHtml = [];
  $('.document-item').each(function(index) {
    const type = $(this).find('.document-type option:selected').text();
    const file = $(this).find('.document-file')[0].files[0];
    if (file) {
      documentsHtml.push(`
        <div class="mb-2">
          <strong>${type}:</strong> ${file.name}
        </div>
      `);
    }
  });
  
  if (documentsHtml.length > 0) {
    $('#preview-documents').html(documentsHtml.join(''));
  } else {
    $('#preview-documents').html('<p class="text-muted">No documents uploaded yet.</p>');
  }
  
  // Beneficiaries
  const beneficiariesHtml = [];
  $('.beneficiary-name').each(function(index) {
    const name = $(this).val().trim();
    if (name) {
      const file = $(this).closest('.beneficiary-item').find('.beneficiary-file')[0].files[0];
      const fileName = file ? file.name : 'No file uploaded';
      beneficiariesHtml.push(`
        <div class="mb-2">
          <strong>Beneficiary ${index + 1}:</strong> ${name} 
          <small class="text-muted">(ID: ${fileName})</small>
        </div>
      `);
    }
  });
  
  if (beneficiariesHtml.length > 0) {
    $('#preview-beneficiaries').html(beneficiariesHtml.join(''));
  } else {
    $('#preview-beneficiaries').html('<p class="text-muted">No beneficiaries added yet.</p>');
  }
  
  // Update monthly payment
  calculatePayment();
}

// Step link click handlers
$('.step-link').on('click', function(e) {
  e.preventDefault();
  const step = $(this).data('step');
  if (step < currentStep || validateStep(currentStep)) {
    nextStep(step);
  }
});

// Form submission validation
$('#registrationForm').on('submit', function(e) {
  if (!validateStep(5)) {
    e.preventDefault();
    alert('Please review all information and accept the terms & conditions.');
    return false;
  }
  
  // Validate documents
  let hasValidDocument = false;
  $('.document-item').each(function() {
    const type = $(this).find('.document-type').val();
    const file = $(this).find('.document-file')[0].files[0];
    if (type && file) {
      hasValidDocument = true;
    }
  });
  
  if (!hasValidDocument) {
    e.preventDefault();
    alert('Please upload at least one document with a selected document type.');
    return false;
  }
  
  if (!$('#agreeTerms').is(':checked')) {
    e.preventDefault();
    alert('You must agree to the Terms & Conditions to submit.');
    $('#agreeTerms').focus();
    return false;
  }
  
  // Show loading state
  $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
