/* ==========================================================================
   Olympique Club de Lisala · Script principal (JavaScript natif)
   --------------------------------------------------------------------------
   1. Configuration du site (coordonnées, WhatsApp, réseaux)  ← À COMPLÉTER
   2. Emplacements de l'effectif (joueurs et staff)            ← À COMPLÉTER
   2b. Matchs : calendrier et résultats                         ← À COMPLÉTER
   3. Modules : menu, défilement, équipe, modale, galerie, formulaires…
   ========================================================================== */

/* 1. CONFIGURATION ---------------------------------------------------------
   Remplacer les valeurs vides par les vraies informations du club.
   Tant qu'une valeur est vide, le site affiche « [À compléter] ».
   WordPress : ces réglages deviendront des options du thème (Customizer). */
const SITE_CONFIG = {
  address: "",            // ex. "Avenue …, Lisala"
  city: "Lisala, Mongala, RD Congo",
  phone: "",              // ex. "+243 8X XXX XX XX"
  email: "",              // ex. "contact@…"
  whatsapp: "",           // numéro international sans "+" ni espaces, ex. "2438XXXXXXXX"
  whatsappMessage: "Bonjour, je vous contacte depuis le site de l'Olympique Club de Lisala.",
  socials: {
    facebook: "",         // ex. "https://facebook.com/…"
    instagram: "",
    youtube: "",
    tiktok: ""
  }
};

/* 2. EFFECTIF --------------------------------------------------------------
   Un objet par emplacement. Ajouter "name" (et "photo", "number") quand une
   signature est officielle. Aucun nom n'est inventé ici.
   group : staff | gardien | defenseur | milieu | attaquant
   WordPress : types de contenu "Joueur" et "Staff". */
const TEAM_SLOTS = [
  { group: "staff", role: "Entraîneur principal" },
  { group: "staff", role: "Entraîneur adjoint" },
  { group: "staff", role: "Entraîneur des gardiens" },
  { group: "staff", role: "Préparateur physique" },
  { group: "staff", role: "Médecin / kiné" },
  { group: "staff", role: "Intendant" },
  { group: "gardien", role: "Gardien" },
  { group: "gardien", role: "Gardien" },
  { group: "defenseur", role: "Défenseur" },
  { group: "defenseur", role: "Défenseur" },
  { group: "defenseur", role: "Défenseur" },
  { group: "defenseur", role: "Défenseur" },
  { group: "milieu", role: "Milieu" },
  { group: "milieu", role: "Milieu" },
  { group: "milieu", role: "Milieu" },
  { group: "milieu", role: "Milieu" },
  { group: "attaquant", role: "Attaquant" },
  { group: "attaquant", role: "Attaquant" },
  { group: "attaquant", role: "Attaquant" }
];

/* 2b. MATCHS --------------------------------------------------------------
   Un objet par rencontre, du plus ancien au plus récent. Exemples vides :
   remplacer les valeurs entre crochets par les vraies informations.
   status : "termine" | "en-cours" | "a-venir"
   home   : true si l'OC Lisala reçoit, false s'il se déplace
   score  : { club: 2, opponent: 1 } pour un match joué ou en cours
   Le dernier match "termine" s'affiche dans la grande carte.
   WordPress : type de contenu "Match". */
const MATCHES = [
  { date: "", competition: "", opponent: "", venue: "", home: true,  status: "termine", score: null },
  { date: "", competition: "", opponent: "", venue: "", home: false, status: "termine", score: null },
  { date: "", competition: "", opponent: "", venue: "", home: true,  status: "en-cours", score: null },
  { date: "", competition: "", opponent: "", venue: "", home: false, status: "a-venir", score: null },
  { date: "", competition: "", opponent: "", venue: "", home: true,  status: "a-venir", score: null }
];

/* 3. MODULES ------------------------------------------------------------- */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* --- Configuration : coordonnées, WhatsApp, réseaux --- */
  function applyConfig() {
    const labels = { address: "[Adresse à compléter]", phone: "[Numéro à compléter]", email: "[E-mail à compléter]" };

    $$("[data-config]").forEach((el) => {
      const key = el.dataset.config;
      const value = SITE_CONFIG[key];
      if (!value) {
        if (labels[key]) el.classList.add("is-placeholder");
        return;
      }
      el.classList.remove("is-placeholder");
      if (key === "phone") {
        el.innerHTML = `<a href="tel:${value.replace(/\s/g, "")}">${value}</a>`;
      } else if (key === "email") {
        el.innerHTML = `<a href="mailto:${value}">${value}</a>`;
      } else if (key === "address") {
        el.textContent = `${value}, ${SITE_CONFIG.city}`;
      } else {
        el.textContent = value;
      }
    });

    // WhatsApp : lien direct si le numéro est configuré, sinon message d'information
    const wa = SITE_CONFIG.whatsapp.replace(/\D/g, "");
    $$("[data-whatsapp]").forEach((link) => {
      if (wa) {
        link.href = `https://wa.me/${wa}?text=${encodeURIComponent(SITE_CONFIG.whatsappMessage)}`;
        link.target = "_blank";
        link.rel = "noopener";
      } else {
        link.addEventListener("click", (e) => {
          e.preventDefault();
          openModal(`<h2 id="modal-title" class="modal__title">WhatsApp</h2>
            <p>Le numéro WhatsApp du club n'est pas encore configuré.</p>
            <p class="is-placeholder">Pour l'activer : renseigner <code>SITE_CONFIG.whatsapp</code> dans <code>assets/js/script.js</code>.</p>`);
        });
      }
    });

    // Réseaux sociaux
    $$("[data-social]").forEach((link) => {
      const url = SITE_CONFIG.socials[link.dataset.social];
      if (url) {
        link.href = url;
        link.target = "_blank";
        link.rel = "noopener";
      } else {
        link.title = "Lien à configurer";
        link.addEventListener("click", (e) => {
          e.preventDefault();
          openModal(`<h2 id="modal-title" class="modal__title">Réseaux sociaux</h2>
            <p>Le lien ${link.getAttribute("aria-label")} du club sera bientôt disponible.</p>`);
        });
      }
    });

    $$("[data-year]").forEach((el) => { el.textContent = new Date().getFullYear(); });
  }

  /* --- Bandeau maquette --- */
  function initDemoBar() {
    const bar = $("#demo-bar");
    if (!bar) return;
    $("[data-action='close-demo-bar']", bar).addEventListener("click", () => bar.remove());
    const toggle = $("[data-action='toggle-placeholders']", bar);
    toggle.addEventListener("click", () => {
      const on = document.body.classList.toggle("show-placeholders");
      toggle.setAttribute("aria-pressed", String(on));
    });
  }

  /* --- En-tête : menu mobile, ombre au défilement, lien actif --- */
  function initHeader() {
    const header = $("#site-header");
    const nav = $("#main-nav");
    const toggle = $("#nav-toggle");

    const setMenu = (open) => {
      if (open) nav.style.setProperty("--nav-top", `${header.getBoundingClientRect().bottom}px`);
      nav.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? "Fermer le menu" : "Ouvrir le menu");
      document.body.classList.toggle("modal-open", open);
    };

    toggle.addEventListener("click", () => setMenu(!nav.classList.contains("is-open")));
    $$("a", nav).forEach((a) => a.addEventListener("click", () => setMenu(false)));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("is-open")) { setMenu(false); toggle.focus(); }
    });
    window.matchMedia("(min-width: 961px)").addEventListener("change", (e) => { if (e.matches) setMenu(false); });

    // Ombre de l'en-tête et bouton "haut de page"
    const toTop = $("[data-action='to-top']");
    const onScroll = () => {
      const y = window.scrollY;
      header.classList.toggle("is-scrolled", y > 10);
      toTop.classList.toggle("is-visible", y > 600);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    toTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }));

    // Lien actif selon la section visible
    const links = $$(".main-nav__link");
    const sections = links.map((l) => $(l.getAttribute("href"))).filter(Boolean);
    if ("IntersectionObserver" in window) {
      const spy = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          links.forEach((l) => l.classList.toggle("is-active", l.getAttribute("href") === `#${entry.target.id}`));
        });
      }, { rootMargin: "-45% 0px -50% 0px" });
      sections.forEach((s) => spy.observe(s));
    }
  }

  /* --- Apparition progressive des blocs --- */
  function initReveal() {
    const items = $$(".reveal");
    if (reduceMotion || !("IntersectionObserver" in window)) {
      items.forEach((el) => el.classList.add("is-visible"));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    items.forEach((el) => io.observe(el));
  }

  /* --- Équipe : génération des cartes et filtres --- */
  function initTeam() {
    const grid = $("#team-grid");
    if (!grid) return;

    const escape = (s) => String(s).replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));

    grid.innerHTML = TEAM_SLOTS.map((p) => `
      <li class="person-card" data-group="${p.group}">
        <div class="person-card__photo">
          ${p.photo ? `<img src="${escape(p.photo)}" alt="${escape(p.name || p.role)}" loading="lazy">` : ""}
          ${p.number ? `<span class="person-card__number">${escape(p.number)}</span>` : ""}
        </div>
        <div class="person-card__body">
          <span class="person-card__role">${escape(p.role)}</span>
          <span class="person-card__name${p.name ? "" : " is-placeholder"}">${p.name ? escape(p.name) : "[Nom à compléter]"}</span>
        </div>
      </li>`).join("");

    const buttons = $$("[data-filter]");
    buttons.forEach((btn) => {
      btn.setAttribute("aria-pressed", String(btn.classList.contains("is-active")));
      btn.addEventListener("click", () => {
        const f = btn.dataset.filter;
        buttons.forEach((b) => {
          b.classList.toggle("is-active", b === btn);
          b.setAttribute("aria-pressed", String(b === btn));
        });
        $$(".person-card", grid).forEach((card) => {
          const show = f === "all" || card.dataset.group === f;
          card.classList.toggle("is-hidden", !show);
          if (show) { card.style.animation = "none"; void card.offsetWidth; card.style.animation = ""; }
        });
      });
    });
  }

  /* --- Matchs : dernier match et tableau --- */
  function initMatches() {
    const body = $("#fixtures-body");
    const card = $("#last-match");
    if (!body || !card) return;

    const STATUS = { "termine": "Terminé", "en-cours": "En cours", "a-venir": "À venir" };
    const esc = (s) => String(s).replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
    const val = (v, label) => (v ? esc(v) : `<span class="is-placeholder">[${label}]</span>`);
    const hasScore = (m) => m.score && Number.isFinite(m.score.club) && Number.isFinite(m.score.opponent);
    const scoreText = (m) => {
      if (!hasScore(m)) return m.status === "a-venir" ? "–" : `<span class="is-placeholder">– : –</span>`;
      const [a, b] = m.home ? [m.score.club, m.score.opponent] : [m.score.opponent, m.score.club];
      return `${a} : ${b}`;
    };
    const teams = (m) => {
      const club = "<strong>OC Lisala</strong>";
      const opp = val(m.opponent, "Adversaire");
      return m.home ? `${club} – ${opp}` : `${opp} – ${club}`;
    };

    // Tableau
    body.innerHTML = MATCHES.map((m) => `
      <tr data-status="${m.status}">
        <td>${val(m.date, "Date")}</td>
        <td>${val(m.competition, "Compétition")}</td>
        <td class="fixtures__match">${teams(m)}</td>
        <td class="fixtures__score">${scoreText(m)}</td>
        <td><span class="status status--${m.status}">${STATUS[m.status] || ""}</span></td>
      </tr>`).join("") + `<tr class="fixtures__empty" hidden><td colspan="5">Aucun match dans cette catégorie.</td></tr>`;

    const buttons = $$("[data-match-filter]");
    buttons.forEach((btn) => {
      btn.setAttribute("aria-pressed", String(btn.classList.contains("is-active")));
      btn.addEventListener("click", () => {
        const f = btn.dataset.matchFilter;
        buttons.forEach((b) => {
          b.classList.toggle("is-active", b === btn);
          b.setAttribute("aria-pressed", String(b === btn));
        });
        let shown = 0;
        $$("tr[data-status]", body).forEach((row) => {
          const show = f === "all" || row.dataset.status === f;
          row.classList.toggle("is-hidden", !show);
          if (show) shown++;
        });
        $(".fixtures__empty", body).hidden = shown > 0;
      });
    });

    // Carte "dernier match"
    const last = MATCHES.filter((m) => m.status === "termine").pop();
    if (!last) return;
    const club = `<div class="last-match__team">
        <img src="assets/img/logo/logo-badge.svg" alt="" width="88" height="92">
        <span class="last-match__name">OC Lisala</span>
      </div>`;
    const opp = `<div class="last-match__team">
        <span class="last-match__crest" aria-hidden="true">${last.opponentLogo ? `<img src="${esc(last.opponentLogo)}" alt="">` : "?"}</span>
        <span class="last-match__name">${val(last.opponent, "Adversaire")}</span>
      </div>`;
    let result = "";
    if (hasScore(last)) {
      const d = last.score.club - last.score.opponent;
      result = `<p class="last-match__result">${d > 0 ? "Victoire" : d < 0 ? "Défaite" : "Match nul"}</p>`;
    }
    const [a, b] = hasScore(last) ? (last.home ? [last.score.club, last.score.opponent] : [last.score.opponent, last.score.club]) : [null, null];
    const n = (x) => (x === null ? `<span class="is-placeholder">–</span>` : x);
    card.innerHTML = `
      <div class="last-match__meta">
        <span>${val(last.competition, "Compétition")}</span>
        <span>${val(last.date, "Date")} · ${val(last.venue, "Stade")}</span>
      </div>
      <div class="last-match__board">
        ${last.home ? club : opp}
        <div class="last-match__score">${n(a)}<span class="last-match__sep">:</span>${n(b)}</div>
        ${last.home ? opp : club}
      </div>${result}`;
  }

  /* --- Modale réutilisable --- */
  const modal = $("#modal");
  const modalContent = $("#modal-content");
  let lastFocus = null;

  function openModal(html, variant) {
    lastFocus = document.activeElement;
    modalContent.innerHTML = html;
    modal.classList.toggle("modal--image", variant === "image");
    modal.hidden = false;
    document.body.classList.add("modal-open");
    $(".modal__close", modal).focus();
  }

  function closeModal() {
    if (modal.hidden) return;
    modal.hidden = true;
    modalContent.innerHTML = "";
    document.body.classList.remove("modal-open");
    if (lastFocus) lastFocus.focus();
  }

  function initModal() {
    modal.addEventListener("click", (e) => {
      const closer = e.target.closest("[data-action='close-modal']");
      if (!closer) return;
      // Un lien dans la modale ferme la modale puis suit l'ancre
      if (closer.tagName !== "A") e.preventDefault();
      closeModal();
    });
    document.addEventListener("keydown", (e) => {
      if (modal.hidden) return;
      if (e.key === "Escape") closeModal();
      // Garder le focus dans la modale
      if (e.key === "Tab") {
        const f = $$("a[href], button, input, select, textarea", modal.querySelector(".modal__dialog"));
        if (!f.length) return;
        const first = f[0], last = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
      }
    });
  }

  /* --- Articles d'actualité (contenu dans <template id="article-N">) --- */
  function initNews() {
    $$("[data-open-article]").forEach((link) => {
      link.addEventListener("click", (e) => {
        const tpl = document.getElementById(`article-${link.dataset.openArticle}`);
        if (!tpl) return;
        e.preventDefault();
        const wrap = document.createElement("div");
        wrap.appendChild(tpl.content.cloneNode(true));
        openModal(wrap.innerHTML);
      });
    });
  }

  /* --- Galerie : visionneuse avec navigation clavier --- */
  function initGallery() {
    const items = $$(".gallery__btn");
    if (!items.length) return;
    let index = 0;

    const show = (i) => {
      index = (i + items.length) % items.length;
      const btn = items[index];
      const caption = btn.dataset.caption || "";
      const html = `<h2 id="modal-title" class="visually-hidden">${caption}</h2>
        <img src="${btn.dataset.full}" alt="${caption}">
        <div class="modal__bar">
          <p class="modal__caption">${index + 1} / ${items.length} · ${caption}</p>
          <span class="modal__nav">
            <button type="button" class="btn btn--outline-light btn--sm" data-gallery="prev" aria-label="Photo précédente">←</button>
            <button type="button" class="btn btn--outline-light btn--sm" data-gallery="next" aria-label="Photo suivante">→</button>
          </span>
        </div>`;
      if (modal.hidden) openModal(html, "image");
      else modalContent.innerHTML = html;
    };

    items.forEach((btn, i) => btn.addEventListener("click", () => show(i)));
    modal.addEventListener("click", (e) => {
      const nav = e.target.closest("[data-gallery]");
      if (nav) show(index + (nav.dataset.gallery === "next" ? 1 : -1));
    });
    document.addEventListener("keydown", (e) => {
      if (modal.hidden || !modal.classList.contains("modal--image")) return;
      if (e.key === "ArrowRight") show(index + 1);
      if (e.key === "ArrowLeft") show(index - 1);
    });
  }

  /* --- Formules de partenariat : préremplir le formulaire --- */
  function initOffers() {
    const select = $("#p-offer");
    $$("[data-offer]").forEach((btn) => {
      btn.addEventListener("click", () => {
        if (select) select.value = btn.dataset.offer;
      });
    });
  }

  /* --- Formulaires : validation (démonstration, aucun envoi) --- */
  const MESSAGES = {
    required: "Ce champ est obligatoire.",
    email: "Merci d'indiquer une adresse e-mail valide.",
    phone: "Numéro invalide (chiffres, espaces et + uniquement).",
    minlength: (n) => `Merci d'écrire au moins ${n} caractères.`,
    consent: "Merci de cocher cette case pour continuer."
  };

  function validateField(field) {
    const input = $("input, select, textarea", field);
    const error = $(".field__error", field);
    if (!input || !error) return true;
    const value = input.type === "checkbox" ? input.checked : input.value.trim();
    let msg = "";

    if (input.required && !value) {
      msg = input.type === "checkbox" ? MESSAGES.consent : MESSAGES.required;
    } else if (value && input.type === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) {
      msg = MESSAGES.email;
    } else if (value && input.dataset.validate === "phone" && !/^\+?[\d\s.-]{8,20}$/.test(value)) {
      msg = MESSAGES.phone;
    } else if (value && input.minLength > 0 && value.length < input.minLength) {
      msg = MESSAGES.minlength(input.minLength);
    }

    error.textContent = msg;
    field.classList.toggle("has-error", Boolean(msg));
    input.setAttribute("aria-invalid", String(Boolean(msg)));
    return !msg;
  }

  function initForms() {
    $$("form[data-demo-form]").forEach((form) => {
      const fields = $$(".field", form);
      const success = $(".form__success", form);

      fields.forEach((field) => {
        const input = $("input, select, textarea", field);
        if (!input) return;
        input.addEventListener("blur", () => validateField(field));
        input.addEventListener("input", () => { if (field.classList.contains("has-error")) validateField(field); });
        input.addEventListener("change", () => { if (field.classList.contains("has-error")) validateField(field); });
      });

      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const results = fields.map(validateField);
        if (results.every(Boolean)) {
          // WordPress : remplacer par l'envoi réel (Contact Form 7, WPForms ou admin-ajax).
          form.reset();
          success.hidden = false;
          setTimeout(() => { success.hidden = true; }, 8000);
        } else {
          success.hidden = true;
          const firstError = $(".has-error input, .has-error select, .has-error textarea", form);
          if (firstError) firstError.focus();
        }
      });
    });
  }

  /* --- Démarrage --- */
  applyConfig();
  initDemoBar();
  initHeader();
  initReveal();
  initTeam();
  initMatches();
  initModal();
  initNews();
  initGallery();
  initOffers();
  initForms();
})();
