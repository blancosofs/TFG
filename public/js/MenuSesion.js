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

   // ───────── PERFIL DINÁMICO ─────────

    const rol    = localStorage.getItem("rolUsuario");
    const nombre = localStorage.getItem("nombreUsuario");

    const destinosPerfil = {
        docente:     "perfilDocente.html",
        coordinador: "perfilCoordinador.html",
        familiar:    "perfilFamilia.html",
        admin:       "perfilAdmin.html",
    };

    const etiquetasRol = {
        docente:     "Docente",
        coordinador: "Coordinador",
        familiar:    "Tutor legal",
        admin:       "Administrador",
    };

    const linkPerfil = document.getElementById("linkPerfil");
    if (linkPerfil) linkPerfil.href = destinosPerfil[rol] ?? "login.html";

    const navNombre = document.getElementById("nav-nombre");
    if (navNombre)
        navNombre.textContent = nombre || navNombre.textContent || "—";

    const navRol = document.getElementById("nav-rol");
    if (navRol)
        navRol.textContent = (rol && etiquetasRol[rol]) || navRol.textContent || "—";

});