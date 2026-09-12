// Maps each document type to its display label, number-field label,
// whether the number field applies, and whether issue/expiry dates apply.
const DOC_CONFIG = {
  aadhar: {
    label: 'Aadhar Card',
    numberLabel: 'Aadhar number',
    showNumber: true,
    showDates: false,
  },
  registration: {
    label: 'Registration Document',
    numberLabel: 'Registration number',
    showNumber: true,
    showDates: true,
  },
  license: {
    label: 'License',
    numberLabel: 'License number',
    showNumber: true,
    showDates: true,
  },
  degree: {
    label: 'Degree Certificate',
    numberLabel: 'Degree / registration number',
    showNumber: true,
    showDates: false,
  },
  photo: {
    label: 'Photo',
    numberLabel: 'Document number',
    showNumber: false,
    showDates: false,
  },
};

const typeSelect     = document.getElementById('document_type');
const dynamicFields  = document.getElementById('dynamicFields');
const fieldDocNumber = document.getElementById('fieldDocNumber');
const labelDocNumber = document.getElementById('labelDocNumber');
const fieldDates     = document.getElementById('fieldDates');
const labelFileUpload= document.getElementById('labelFileUpload');
const docFileLabel   = document.getElementById('docFileLabel');
const docFileTile    = document.getElementById('docFileTile');
const docFileInput   = document.getElementById('document_file');
const docFileBody    = document.getElementById('docFileBody');
const submitBar      = document.getElementById('submitBar');

typeSelect.addEventListener('change', () => {
  const config = DOC_CONFIG[typeSelect.value];
  if (!config) {
    dynamicFields.classList.add('doc-hidden');
    submitBar.style.display = 'none';
    return;
  }

  dynamicFields.classList.remove('doc-hidden');
  submitBar.style.display = 'flex';

  // Update the file field label to reflect the chosen document type.
  labelFileUpload.textContent = 'Upload ' + config.label;
  docFileLabel.textContent = 'Choose ' + config.label + ' file';

  // Show/hide the document number field.
  if (config.showNumber) {
    fieldDocNumber.classList.remove('doc-hidden');
    labelDocNumber.textContent = config.numberLabel;
  } else {
    fieldDocNumber.classList.add('doc-hidden');
  }

  // Show/hide issue/expiry dates (relevant for license & registration).
  if (config.showDates) {
    fieldDates.classList.remove('doc-hidden');
  } else {
    fieldDates.classList.add('doc-hidden');
  }

  // Reset the file tile preview when switching document type.
  docFileInput.value = '';
  docFileTile.classList.remove('filled');
  docFileBody.innerHTML =
    '<div class="upload-icon" style="margin:0 auto 9px;">⬆</div>' +
    '<div class="upload-tile-label" id="docFileLabel">Choose ' + config.label + ' file</div>' +
    '<div class="upload-tile-sub">JPG, PNG or PDF · max 3MB</div>';
});

docFileInput.addEventListener('change', () => {
  const file = docFileInput.files[0];
  if (!file) return;

  docFileTile.classList.add('filled');

  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = (e) => {
      docFileBody.innerHTML =
        '<img class="upload-preview" src="' + e.target.result + '" alt="preview">' +
        '<div class="upload-filename">' + file.name + '</div>';
    };
    reader.readAsDataURL(file);
  } else {
    docFileBody.innerHTML =
      '<div class="upload-icon" style="margin:0 auto 9px;">📄</div>' +
      '<div class="upload-filename">' + file.name + '</div>';
  }
});
