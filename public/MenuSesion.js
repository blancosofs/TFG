document.addEventListener("DOMContentLoaded", function () {
    
    const perfil = document.querySelector(".fotoPerfil");
    const menu = document.querySelector(".dropdown");

    perfil.addEventListener("click", function (e) {
        e.stopPropagation();
        menu.classList.toggle("show");
    });

    document.addEventListener("click", function () {
        menu.classList.remove("show");
    });

    const elemento1 = document.querySelectorAll(".titulo");

    elemento1.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add("visible");
        }, 200 + index * 200);   // delay base 200 ms
    });

    const elemento2 = document.querySelectorAll(".subtitulo");

    elemento2.forEach((el, index) => {
        setTimeout(() => {
            el.classList.add("visible");
        }, 400 + index * 200);   // después del título
    });

});