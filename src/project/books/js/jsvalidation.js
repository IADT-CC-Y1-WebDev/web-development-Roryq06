let submitBtn = document.getElementById('submit_btn');
let bookForm = document.getElementById('book_form');
let errorSummaryTop = document.getElementById('error_summary_top');
 
let titleInput = document.getElementById('title');
let authorInput = document.getElementById('author');
let yearInput = document.getElementById('year');
let publisherIdInput = document.getElementById('publisher_id');
let descriptionInput = document.getElementById('description');
let formatIdsInput = document.getElementsByName('format_id[]');
let coverInput = document.getElementById('cover');
 
let titleError = document.getElementById('title_error');
let authorError = document.getElementById('author_error');
let releaseDateError = document.getElementById('year_error');
let publisherIdError = document.getElementById('publisher_id_error');
let descriptionError = document.getElementById('description_error');
let formatIdsError = document.getElementById('format_ids_error');
let coverError = document.getElementById('cover_error');
 
let errors = {};
 
submitBtn.addEventListener('click', onSubmitForm);
 
function addError(fieldName, message) {
    errors[fieldName] = message;
}
 
function showErrorSummaryTop() {
    const messages = Object.values(errors);
    if (messages.length === 0) {
        errorSummaryTop.style.display = 'none';
        errorSummaryTop.innerHTML = '';
        return;
    }
    errorSummaryTop.innerHTML =
        '<strong>Please fix the following:</strong><ul>' +
        messages
            .map(function (m) {
                return '<li>' + m + '</li>';
            })
            .join('') +
        '</ul>';
    errorSummaryTop.style.display = 'block';
}
 
function showFieldErrors() {
    titleError.innerHTML = errors.title || '';
    authorError.innerHTML = errors.author || '';
    releaseDateError.innerHTML = errors.year || '';
    publisherIdError.innerHTML = errors.publisher_id || '';
    descriptionError.innerHTML = errors.description || '';
    formatIdsError.innerHTML = errors.format_ids || '';
    coverError.innerHTML = errors.cover || '';
}
 
function isRequired(value) {
    return String(value).trim() !== '';
}
 
function isMinLength(value, min) {
    return String(value).trim().length >= min;
}
 
function isMaxLength(value, max) {
    return String(value).trim().length <= max;
}
 
 
 
function onSubmitForm(evt) {
    evt.preventDefault();
 
    errors = {};
 
    let titleMin = Number(titleInput.dataset.minlength) || 3;
    let titleMax = Number(titleInput.dataset.maxlength) || 255;
    let descMin = Number(descriptionInput.dataset.minlength) || 3;
 
 
    if (!isRequired(titleInput.value)) {
        addError('title', 'Title is required.');
    } else if (!isMinLength(titleInput.value, titleMin)) {
        addError('title', 'Title must be at least ' + titleMin + ' characters long.');
    } else if (!isMaxLength(titleInput.value, titleMax)) {
        addError('title', 'Title must be no more than ' + titleMax + ' characters long.');
    }
 
    if (!isRequired(authorInput.value)) {
        addError('author', 'Author is required.');
    }
 
    if (!isRequired(yearInput.value)) {
        addError('year', 'Year is required.');
    }
 
 
    if (!isRequired(publisherIdInput.value)) {
        addError('publisher_id', 'Genre is required.');
    }
 
 
    if (!isRequired(descriptionInput.value)) {
        addError('description', 'Description is required.');
    } else if (!isMinLength(descriptionInput.value, descMin)) {
        addError('description', `Description must be at least ${descMin} characters long.`);
    }
 
 
    let formatChecked = false;
    for (let i = 0; i < formatIdsInput.length; i++) {
        if (formatIdsInput[i].checked) {
            formatChecked = true;
            break;
        }
    }
 
    if (!formatChecked) {
        addError('format_ids', 'At least one format must be selected.');
    }
 
 
    if (coverInput.files.length === 0) {
        addError('cover', 'Image is required.');
    }
 
    showFieldErrors();
    showErrorSummaryTop();
 
    if (Object.keys(errors).length === 0) {
        bookForm.submit();
        console.log('Form submitted successfully!');
    }
}