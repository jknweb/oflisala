/* ==========================================================================
   FC Lisala · Script principal (JavaScript natif)
   --------------------------------------------------------------------------
   1. SITE_CONFIG : coordonnées, WhatsApp, réseaux sociaux   ← À COMPLÉTER
   2. TEAM : joueurs, entraîneur et staff                     ← À COMPLÉTER
   3. Modules : en-tête, menu mobile, animations, équipe, articles, formulaire
   ========================================================================== */

/* 1. CONFIGURATION ---------------------------------------------------------
   Laisser une valeur vide tant que l'information officielle n'est pas connue.
   WordPress : ces réglages deviendront des options du thème (Customizer). */
const SITE_CONFIG = {
  address: "",      // ex. "Avenue …, Lisala"
  phone: "",        // ex. "+243 8X XXX XX XX"
  email: "",        // ex. "contact@…"
  whatsapp: "",     // numéro international, chiffres uniquement, ex. "2438XXXXXXXX"
  whatsappMessage: "Bonjour, je vous contacte depuis le site du FC Lisala.",
  socials: {
    facebook: "",   // ex. "https://facebook.com/…"
    instagram: "",
    youtube: "",
    tiktok: ""
  }
};

/* 2. ÉQUIPE ----------------------------------------------------------------
   Une entrée par fiche. Compléter name, number et photo avec les données
   officielles. Aucun nom ni numéro n'est inventé ici.
   group : staff | gardien | defenseur | milieu | attaquant
   WordPress : types de contenu "Joueur" et "Staff". */
const TEAM = [
  { group: "staff", role: "Entraîneur principal", name: "", photo: "" },
  { group: "staff", role: "Entraîneur adjoint", name: "", photo: "" },
  { group: "staff", role: "Entraîneur des gardiens", name: "", photo: "" },
  { group: "staff", role: "Préparateur physique", name: "", photo: "" },
  { group: "gardien", role: "Gardien", name: "", number: "", photo: "" },
  { group: "gardien", role: "Gardien", name: "", number: "", photo: "" },
  { group: "defenseur", role: "Défenseur", name: "", number: "", photo: "" },
  { group: "defenseur", role: "Défenseur", name: "", number: "", photo: "" },
  { group: "defenseur", role: "Défenseur", name: "", number: "", photo: "" },
  { group: "defenseur", role: "Défenseur", name: "", number: "", photo: "" },
  { group: "milieu", role: "Milieu", name: "", number: "", photo: "" },
  { group: "milieu", role: "Milieu", name: "", number: "", photo: "" },
  { group: "milieu", role: "Milieu", name: "", number: "", photo: "" },
  { group: "attaquant", role: "Attaquant", name: "", number: "", photo: "" },
  { group: "attaquant", role: "Attaquant", name: "", number: "", photo: "" }
];

/* 3. MODULES ------------------------------------------------------------- */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const escapeHTML = (s) => String(s).replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));

  /* --- Images : fond de secours si une photo externe ne charge pas --- */
  function initImageFallback() {
    $$(".media .photo").forEach((img) => {
      const fail = () => img.closest(".media").classList.add("is-missing");
      if (img.complete && img.naturalWidth === 0 && img.src) fail();
      img.addEventListener("error", fail, { once: true });
    });
  }

  /* --- Coordonnées, WhatsApp, réseaux sociaux --- */
  function applyConfig() {
    $$("[data-config]").forEach((el) => {
      const key = el.dataset.config;
      const value = SITE_CONFIG[key];
      if (!value) { el.classList.add("is-placeholder"); return; }
      if (key === "phone") el.innerHTML = `<a href="tel:${escapeHTML(value.replace(/\s/g, ""))}">${escapeHTML(value)}</a>`;
      else if (key === "email") el.innerHTML = `<a href="mailto:${escapeHTML(value)}">${escapeHTML(value)}</a>`;
      else if (key === "address") el.textContent = `${value}, Lisala, Mongala, RD Congo`;
      else el.textContent = value;
    });

    // WhatsApp : les boutons n'apparaissent que si le numéro est renseigné
    const wa = SITE_CONFIG.whatsapp.replace(/\D/g, "");
    if (wa) {
      $$("[data-whatsapp]").forEach((link) => {
        link.href = `https://wa.me/${wa}?text=${encodeURIComponent(SITE_CONFIG.whatsappMessage)}`;
        link.target = "_blank";
        link.rel = "noopener";
        link.hidden = false;
      });
    }

    // Réseaux : un lien vide reste visible mais renvoie vers le contact
    let missing = 0;
    $$("[data-social]").forEach((link) => {
      const url = SITE_CONFIG.socials[link.dataset.social];
      if (url) { link.href = url; link.target = "_blank"; link.rel = "noopener"; }
      else { missing++; link.title = "Lien à venir"; }
    });
    const note = $("[data-socials-note]");
    if (note && !missing && wa) note.hidden = true;

    $$("[data-year]").forEach((el) => { el.textContent = new Date().getFullYear(); });
  }

  /* --- En-tête : transformation au défilement, menu mobile, lien actif --- */
  function initHeader() {
    const header = $("#site-header");
    const nav = $("#main-nav");
    const toggle = $("#nav-toggle");
    const toTop = $("[data-action='to-top']");

    const setMenu = (open) => {
      nav.classList.toggle("is-open", open);
      header.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
      document.body.classList.toggle("no-scroll", open);
    };
    toggle.addEventListener("click", () => setMenu(!nav.classList.contains("is-open")));
    $$("a", nav).forEach((a) => a.addEventListener("click", () => setMenu(false)));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("is-open")) { setMenu(false); toggle.focus(); }
    });
    window.matchMedia("(min-width: 961px)").addEventListener("change", (e) => { if (e.matches) setMenu(false); });

    const onScroll = () => {
      const y = window.scrollY;
      header.classList.toggle("is-scrolled", y > 40);
      toTop.classList.toggle("is-visible", y > 700);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    toTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }));

    // Lien actif selon la section visible
    const links = $$(".main-nav__link");
    const sections = links.map((l) => document.getElementById(l.hash.slice(1))).filter(Boolean);
    if ("IntersectionObserver" in window) {
      const spy = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          links.forEach((l) => {
            const active = l.hash === `#${entry.target.id}`;
            l.classList.toggle("is-active", active);
            if (active) l.setAttribute("aria-current", "true"); else l.removeAttribute("aria-current");
          });
        });
      }, { rootMargin: "-45% 0px -50% 0px" });
      sections.forEach((s) => spy.observe(s));
    }
  }

  /* --- Apparition progressive au défilement --- */
  function initReveal() {
    const items = $$(".reveal");
    if (reduceMotion || !("IntersectionObserver" in window)) { items.forEach((el) => el.classList.add("is-visible")); return; }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) { entry.target.classList.add("is-visible"); io.unobserve(entry.target); }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    items.forEach((el) => io.observe(el));
  }

  /* --- Équipe : cartes et filtres --- */
  function initTeam() {
    const grid = $("#team-grid");
    if (!grid) return;

    grid.innerHTML = TEAM.map((p) => {
      const isStaff = p.group === "staff";
      const number = isStaff ? "" : p.number
        ? `<span class="player-card__number">${escapeHTML(p.number)}</span>`
        : `<span class="player-card__number is-placeholder" aria-label="Numéro à compléter">N°</span>`;
      const photo = p.photo ? `<img src="${escapeHTML(p.photo)}" alt="${escapeHTML(p.name || p.role)}" loading="lazy">` : "";
      return `
        <li class="player-card${isStaff ? " player-card--staff" : ""}" data-group="${p.group}">
          <div class="player-card__photo">${photo}${number}</div>
          <div class="player-card__body">
            <span class="player-card__role">${escapeHTML(p.role)}</span>
            <span class="player-card__name${p.name ? "" : " is-placeholder"}">${p.name ? escapeHTML(p.name) : "Nom à compléter"}</span>
          </div>
        </li>`;
    }).join("");

    const buttons = $$("[data-filter]");
    buttons.forEach((btn) => {
      btn.setAttribute("aria-pressed", String(btn.classList.contains("is-active")));
      btn.addEventListener("click", () => {
        const f = btn.dataset.filter;
        buttons.forEach((b) => { b.classList.toggle("is-active", b === btn); b.setAttribute("aria-pressed", String(b === btn)); });
        $$(".player-card", grid).forEach((card) => card.classList.toggle("is-hidden", f !== "all" && card.dataset.group !== f));
      });
    });
  }

  /* --- Articles : ouverture dans une fenêtre modale --- */
  function initArticles() {
    const modal = $("#modal");
    const content = $("#modal-content");
    let lastFocus = null;

    const open = (html) => {
      lastFocus = document.activeElement;
      content.innerHTML = html;
      modal.hidden = false;
      document.body.classList.add("no-scroll");
      $(".modal__close", modal).focus();
    };
    const close = () => {
      if (modal.hidden) return;
      modal.hidden = true;
      content.innerHTML = "";
      document.body.classList.remove("no-scroll");
      if (lastFocus) lastFocus.focus();
    };

    $$("[data-open-article]").forEach((link) => {
      link.addEventListener("click", (e) => {
        const tpl = document.getElementById(`article-${link.dataset.openArticle}`);
        if (!tpl) return;
        e.preventDefault();
        const wrap = document.createElement("div");
        wrap.appendChild(tpl.content.cloneNode(true));
        open(wrap.innerHTML);
      });
    });
    modal.addEventListener("click", (e) => { if (e.target.closest("[data-action='close-modal']")) close(); });
    document.addEventListener("keydown", (e) => {
      if (modal.hidden) return;
      if (e.key === "Escape") close();
      if (e.key === "Tab") {
        const f = $$("a[href], button", $(".modal__dialog", modal));
        const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
  }

  /* --- Liens « Devenir partenaire » : présélection du sujet --- */
  function initSubjectLinks() {
    const select = $("#c-subject");
    $$("[data-subject]").forEach((link) => link.addEventListener("click", () => { if (select) select.value = link.dataset.subject; }));
  }

  /* --- Formulaire de contact : validation (démonstration, aucun envoi) --- */
  function initForm() {
    const form = $("#contact-form");
    if (!form) return;
    const fields = $$(".field", form);
    const success = $(".form__success", form);

    const validate = (field) => {
      const input = $("input, select, textarea", field);
      const error = $(".field__error", field);
      const value = input.value.trim();
      let msg = "";
      if (input.required && !value) msg = "Ce champ est obligatoire.";
      else if (input.type === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) msg = "Merci d'indiquer une adresse e-mail valide.";
      else if (input.minLength > 0 && value.length < input.minLength) msg = `Merci d'écrire au moins ${input.minLength} caractères.`;
      error.textContent = msg;
      field.classList.toggle("has-error", Boolean(msg));
      input.setAttribute("aria-invalid", String(Boolean(msg)));
      return !msg;
    };

    fields.forEach((field) => {
      const input = $("input, select, textarea", field);
      input.addEventListener("blur", () => validate(field));
      input.addEventListener("input", () => { if (field.classList.contains("has-error")) validate(field); });
      input.addEventListener("change", () => { if (field.classList.contains("has-error")) validate(field); });
    });

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const ok = fields.map(validate).every(Boolean);
      success.hidden = !ok;
      if (ok) {
        // WordPress : remplacer par l'envoi réel (Contact Form 7, WPForms…).
        form.reset();
      } else {
        $(".has-error input, .has-error select, .has-error textarea", form).focus();
      }
    });
  }

  initImageFallback();
  applyConfig();
  initHeader();
  initReveal();
  initTeam();
  initArticles();
  initSubjectLinks();
  initForm();
})();
