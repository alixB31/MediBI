// // script.js - Fichier JS global
// document.addEventListener("DOMContentLoaded", function () {
//     console.log("JavaScript chargé !");
//
//     // Effet sur la navigation
//     const navLinks = document.querySelectorAll("nav ul li a");
//     navLinks.forEach(link => {
//         link.addEventListener("mouseenter", () => {
//             link.style.color = "#ffcc00";
//         });
//         link.addEventListener("mouseleave", () => {
//             link.style.color = "white";
//         });
//     });
//
//     // Animation des boutons
//     const buttons = document.querySelectorAll("button");
//     buttons.forEach(button => {
//         button.addEventListener("mousedown", () => {
//             button.style.transform = "scale(0.95)";
//         });
//         button.addEventListener("mouseup", () => {
//             button.style.transform = "scale(1)";
//         });
//     });
//
//     // Effet de validation sur les formulaires
//     const forms = document.querySelectorAll("form");
//     forms.forEach(form => {
//         form.addEventListener("submit", function (event) {
//             const inputs = form.querySelectorAll("input, select");
//             let valid = true;
//
//             inputs.forEach(input => {
//                 if (input.value.trim() === "") {
//                     input.style.border = "2px solid red";
//                     valid = false;
//                 } else {
//                     input.style.border = "1px solid #ccc";
//                 }
//             });
//
//             if (!valid) {
//                 event.preventDefault();
//                 alert("Veuillez remplir tous les champs requis !");
//             }
//         });
//     });
// });
