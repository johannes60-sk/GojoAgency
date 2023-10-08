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

// Suppression des elements

document.querySelectorAll('[data-delete]').forEach(a => {
    a.addEventListener('click', (e) => {
        e.preventDefault();

        const token = a.dataset.token;

        fetch(a.getAttribute('href'), {
            
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            // body: JSON.stringify({'_token': a.dataset.token})
            
        })
        .then(response =>  response.json())
        .then(data => {

            console.log('data', data)

            if(data.success){
                alert('success')
                a.parentNode.removeChild(a);
                // a.parentNode.parentNode.removeChild(a.parentNode)
            }else{
                alert('erreur ')
            }
        })
        .catch(e => alert(e))
    })
});

// $(function() {

//     $('.delete-button').on('click' ,function(event) {
//         event.preventDefault();  

//         var deleteUrl = $(this).attr('href');
//         var csrfToken = $(this).data('token');

//         console.log('deleteUrl', deleteUrl)
//         console.log('csrfToken', csrfToken)

//         $.ajax({
//             url: deleteUrl,
//             type: 'POST',
//             data: {
//                 'csrf_token': csrfToken,
//                 _method: 'DELETE'
//             },
//             success: function(response) {
//                 // Traitez la réponse si nécessaire
//                 console.log('Suppression réussie :', response);
//             },
//             error: function(error) {
//                 // Traitez les erreurs si nécessaire
//                 console.error('Erreur lors de la suppression :', error);
//             }
//         });
//     });
// });
