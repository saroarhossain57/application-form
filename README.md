# Application Form

It is a simple WordPress plugin for an Job Application form. You can use it for collecting applications from applications with their details and CV in super easy way. **All the details and it's features will be mentioned below.**

![Application Form](https://github.com/saroarhossain57/application-form/blob/main/public/github-images/screenshot-1.png?raw=true)

## Core Features.
- Create Job Application Form.
- See the applications in dashboad.
- Download CV fromt the list.
- View the application details in a single page.
- A admin dashboard to show latest 5 application submissions.


## Installation.
Open terminal and go to your plugin directory.

> Clone the repo into your plugin direcotory
```bash
$ git clone https://github.com/saroarhossain57/application-form.git
```

### Or you can simply download the repository and extract it into your plugin folder.
<br>

> If you want to use this plugin in live server then just download the repo and upload it as a zip file as wp plugin.

### Now reload the plugin page and active the plugin from WP admin dashboard.

## How to use it.
**Application Form** render the form by just using a shortcode ` [applicant_form]`. Go to any page and put the shortcode. That's it. it will render the form at frontend.
![Application Form Backend](https://github.com/saroarhossain57/application-form/blob/main/public/github-images/screenshot-3.png?raw=true)

You will find all the application submissions in the dashboard. Please see a new WP admin menu "Submissions". 

![Application Submissions](https://github.com/saroarhossain57/application-form/blob/main/public/github-images/screenshot-4.png?raw=true)

### You can see the application details by clicking on view button in the submission list.
![Application Details](https://github.com/saroarhossain57/application-form/blob/main/public/github-images/screenshot-5.png?raw=true)


### On the admin dashboard page you will see five latest applications in a dashboard widget.
![Dashbaord Widget](https://github.com/saroarhossain57/application-form/blob/main/public/github-images/screenshot-6.png?raw=true)


## Developer Prespectives.
That project has both front end and backend validations. The form is being submitted through a custom rest endpoints. The endpoint is public but nonce verified.

The form submission handled using fetch API. I have created the submission list table using WP List Table class and it has search and order option for postname and submit date.

By default the list will show 10 items ( Because for testing the pagination we need to keep it lower number ) but you can increase or decrease it from screen option.

The search option works perfectly with custom ordering here. Also there was no requirements for single application view but I had created it because it was super easy.

### I have tested the project with JSHint and PHPCS. I have added config file for both PHPCS and JSHint.


## My Info:
| Name: | Saroar Hossain |
|---|---|
| Email: | limonhossain57@gmail.com |
| Phone: | +8801742560972 |