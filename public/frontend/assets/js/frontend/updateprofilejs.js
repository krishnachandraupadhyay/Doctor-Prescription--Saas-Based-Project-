document.getElementById('pwToggle').addEventListener('click', (e) => {
  const input = document.getElementById('password');
  const show = input.type === 'password';
  input.type = show ? 'text' : 'password';
  e.target.textContent = show ? 'Hide' : 'Show';
});

['logo','signature','clinic_stamp'].forEach(id => {
  const input = document.getElementById(id);
  const tile = document.getElementById('tile-' + id);
  input.addEventListener('change', () => {
    const file = input.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      tile.classList.add('filled');
      tile.querySelector('.upload-body').innerHTML =
        '<img class="upload-preview" src="' + e.target.result + '" alt="' + id + ' preview">' +
        '<div class="upload-filename">' + file.name + '</div>';
    };
    reader.readAsDataURL(file);
  });
});

document.getElementById('regForm').addEventListener('submit', (e) => {
  const fields = document.querySelectorAll('input[required], textarea[required]');
  for(const f of fields){
    if(!f.checkValidity()){ f.reportValidity(); f.scrollIntoView({behavior:'smooth', block:'center'}); return; }
  }
  const toast = document.getElementById('toast');
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3500);
});

document.getElementById('btnCancel').addEventListener('click', () => {
  document.getElementById('regForm').reset();
  document.querySelectorAll('.upload-tile').forEach(t => t.classList.remove('filled'));
});