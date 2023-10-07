/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
// any CSS you import will output into a single css file (app.css in this case)
// import $ from 'jquery';

let $ = require('jquery')

import './styles/app.css';

import './bootstrap';

require('select2');


// start the Stimulus application

$(function(){
    $('.select-tags').select2();
});

let $contactButton = $('#contactButton')  // ici on declare une variable avce $ pour indiquer qu'il s'agit d'un objet jQuery.  

$('#contactButton').on('click', function (e) {
    e.preventDefault();
    $('#contactForm').slideDown();
    $contactButton.slideUp();
})

let $successAlert = $('#success-alert')

let $dangerAlert = $('#danger-alert')

if ($successAlert.length > 0) {
    $successAlert.show();

    setTimeout(function() {
        $successAlert.fadeOut();
    }, 5000); 
} else if ($dangerAlert.length > 0) {
    $dangerAlert.show();

    setTimeout(function() {
        $dangerAlert.fadeOut();
    }, 5000); 
}


