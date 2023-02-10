const appliApplicationForm = document.getElementById('appli-application-form');
let clearGlobalMessageTimer;

const formSubmitHandler = (event) => {
    event.preventDefault();

    const endpoint = appliApplicationForm.getAttribute('action');
    const formData = new FormData(event.target);
    const nonceValue = document.querySelector('#appli-application-form #_wpnonce').value;

    const { validationResult, errors, generatedData } = applicationFormFrontEndValidation(formData);

    // Clear previous error messages
    if(!validationResult){
        handleFormError(errors);
        return;
    }

    fetch(endpoint, {
        method: 'POST', // or 'PUT'
        headers: {
            'X-WP-Nonce': nonceValue
        },
        body: generatedData,
    })
    .then((response) => {
        if(response.ok){
            return response.json();
        } else {
            return Promise.reject(response.statusText);
        }
        
    })
    .then((data) => {
        if(data.response.errors){
            handleFormError(data.response.errors);
        } else {
            handleFormSuccess(data.response);
        }
    })
    .catch((error) => {
        if(error){
            console.error('Error:', error);
        }
    });


};
appliApplicationForm.addEventListener('submit', formSubmitHandler);


const handleFormSuccess = data => {
    const $globalNoticeEl = document.querySelector(".appli-global-notice");
    $globalNoticeEl.classList.remove('error');
    $globalNoticeEl.classList.add('success');
    $globalNoticeEl.textContent = data;

    const allFields = ['firstname', 'lastname', 'present_address', 'email', 'mobile', 'postname', 'cv'];
    for(const field of allFields){
        var defaultField =  document.getElementById(field);
        if (typeof(defaultField) != 'undefined' && defaultField != null){
            defaultField.value = '';
            defaultField.nextElementSibling.style.display = 'none';
            defaultField.nextElementSibling.textContent = '';
        }
    }

    // Clear global message
    clearTimeout(clearGlobalMessageTimer);
    clearGlobalMessageTimer = setTimeout(function(){
        const $globalNoticeEl = document.querySelector(".appli-global-notice");
        $globalNoticeEl.classList.remove('error', 'success');
        $globalNoticeEl.textContent = '';
    }, 4000);
    
};

const handleFormError = (errors) => {

    const allFields = ['firstname', 'lastname', 'present_address', 'email', 'mobile', 'postname', 'cv'];

    const $globalNoticeEl = document.querySelector(".appli-global-notice");
    $globalNoticeEl.classList.remove('error', 'succuss');

    for(const field of allFields){
        var defaultField =  document.getElementById(field);
        if (typeof(defaultField) != 'undefined' && defaultField != null){
            defaultField.nextElementSibling.style.display = 'none';
            defaultField.nextElementSibling.textContent = '';
        }
    }
   

    if(Object.keys(errors).length !== 0){
        // Throw front end errors
        for (const [key, value] of Object.entries(errors)) {
            var inputField =  document.getElementById(key);

            if (typeof(inputField) != 'undefined' && inputField != null){
                inputField.nextElementSibling.style.display = 'block';
                inputField.nextElementSibling.textContent = value;
            }
        }

        $globalNoticeEl.classList.add('error');
        $globalNoticeEl.textContent = 'Opps! There is some error!';
    }
};

const applicationFormFrontEndValidation = formData => {

    const errors = {};
    const generatedData = new FormData();
    let validationResult = false;

    for (const pair of formData.entries()) {

        let key = pair[0];
        let value = pair[1];
        const allowed_file_types = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

        if(key === 'firstname'){
            if(!value){
                errors.firstname = 'Firstname is required';
            } 
        } 

        if(key === 'lastname'){
            if(!value){
                errors.lastname = 'Lastname is required';
            }
        }

        if(key === 'present_address'){
            if(!value){
                errors.present_address = 'Present address is required';
            }
        }

        if(key === 'email'){
            if(!value || !validateEmail(value)){
                errors.email = 'Please enter a valid email address';
            }
        }

        if(key === 'mobile'){
            if(!value || !validateBangladeshiMobile(value)){
                errors.mobile = 'Please enter a valid Bangladeshi phone number.';
            }
        }

        if(key === 'postname'){
            if(!value){
                errors.postname = 'Post name is required';
            }
        }

        if(key === 'postname'){
            if(!value){
                errors.postname = 'Post name is required';
            }
        }

        if(key === 'cv'){
            if(!value.name){
                errors.cv = 'Please attach a CV';
            } else {
                if(!allowed_file_types.includes(value.type)){
                    errors.cv = 'Please use a valid file. Only images and PDF are allowed';
                } else {
                    if(value.size > 10485760){
                        errors.cv = 'Maximum 10MB file is allowed';
                    }
                }
            }
        }

        if(key === 'cv'){
            const fileInput = document.getElementById('cv');
            generatedData.append(key, fileInput.files[0]);
        } else {
            generatedData.append(key, value);
        }
        
    }

    if(Object.keys(errors).length === 0){
        validationResult = true;
    }

    return {
        validationResult,
        errors,
        generatedData
    };
};


const validateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
};

const validateBangladeshiMobile = (email) => {
    return String(email).toLowerCase().match(/^(?:\+88|88)?(01[3-9]\d{8})$/);
};