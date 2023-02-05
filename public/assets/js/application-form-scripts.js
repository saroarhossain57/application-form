const appliApplicationForm = document.getElementById('appli-application-form');

const formSubmitHandler = (event) => {
    event.preventDefault();

    const endpoint = appliApplicationForm.getAttribute('action');
    const formData = new FormData(event.target);

    applicationFormFrontEndValidation(formData);

    // const { validationResult, generatedData } = applicationFormFrontEndValidation(formData);
return;
    if(!validationResult){
        // Throw front end errors
    }

    fetch(endpoint, {
        method: 'POST', // or 'PUT'
        headers: {},
        body: generatedFormData,
    })
    .then((response) => response.json())
    .then((data) => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });


}
appliApplicationForm.addEventListener('submit', formSubmitHandler);


const applicationFormFrontEndValidation = formData => {

    for (const pair of formData.entries()) {

        let key = pair[0];
        let value = pair[1];
        const allowed_file_types = ['image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

        const errors = {};

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
                errors.email = 'Please enter a valid Bangladeshi phone number.';
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

        console.log(`${pair[0]}, ${pair[1]}`);
    }

    // return {
    //     validationResult,
    //     generatedData
    // }
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