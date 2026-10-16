/* ==========================================================================
   OC Lisala · Script du thème (JavaScript natif)
   Repris du site d'origine. Les contenus viennent désormais de WordPress :
   ce script ne gère plus que les interactions.
   1. Images de secours   2. En-tête et menu mobile   3. Apparition au défilement
   4. Filtres de l'équipe  5. Rotation des publicités 6. Formulaire WhatsApp
   ========================================================================== */
(function () {
  "use strict";

  document.documentElement.classList.remove("no-js");

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  const config = window.oclSite || { adsDelay: 6000, i18n: {} };
  const t = (key, fallback) => (config.i18n && config.i18n[key]) || fallback;

  /* --- 1. Images : fond « terrain » si une photo ne charge pas --- */
  function initImageFallback() {
    $$(".media .photo").forEach((img) => {
      const fail = () => {
        img.classList.add("is-broken");
        const media = img.closest(".media");
        if (media && $$(".photo", media).every((p) => p.classList.contains("is-broken"))) media.classList.add("is-missing");
      };
      if (img.complete && img.naturalWidth === 0 && img.currentSrc) fail();
      img.addEventListener("error", fail, { once: true });
    });
  }

  /* --- 2. En-tête : défilement, menu mobile, sous-menus, lien actif --- */
  function initHeader() {
    const header = $("#site-header");
    const nav = $("#main-nav");
    const toggle = $("#nav-toggle");
    const toTop = $("[data-action='to-top']");
    if (!header || !nav || !toggle) return;

    const setMenu = (open) => {
      nav.classList.toggle("is-open", open);
      header.classList.toggle("is-open", open);
      toggle.setAttribute("aria-expanded", String(open));
      toggle.setAttribute("aria-label", open ? t("closeMenu", "Fermer le menu") : t("openMenu", "Ouvrir le menu"));
      document.body.classList.toggle("no-scroll", open);
    };
    toggle.addEventListener("click", () => setMenu(!nav.classList.contains("is-open")));
    $$("a", nav).forEach((a) => a.addEventListener("click", () => setMenu(false)));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && nav.classList.contains("is-open")) { setMenu(false); toggle.focus(); }
    });
    window.matchMedia("(min-width: 961px)").addEventListener("change", (e) => { if (e.matches) setMenu(false); });

    // Sous-menus (mobile) : bouton d'ouverture à côté du lien parent
    $$(".submenu-toggle", nav).forEach((btn) => {
      btn.addEventListener("click", () => {
        const sub = btn.parentElement.querySelector(".sub-menu");
        if (!sub) return;
        const open = btn.getAttribute("aria-expanded") !== "true";
        btn.setAttribute("aria-expanded", String(open));
        sub.classList.toggle("is-open", open);
      });
    });

    const onScroll = () => {
      const y = window.scrollY;
      header.classList.toggle("is-scrolled", y > 40);
      if (toTop) toTop.classList.toggle("is-visible", y > 700);
    };
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
    if (toTop) toTop.addEventListener("click", () => window.scrollTo({ top: 0, behavior: reduceMotion ? "auto" : "smooth" }));

    // Lien actif selon la section visible (liens vers des ancres de la page en cours)
    const here = location.origin + location.pathname;
    const links = $$(".main-nav__link", nav).filter((l) => l.origin + l.pathname === here);
    const targetOf = (l) => document.getElementById(l.hash ? decodeURIComponent(l.hash.slice(1)) : "accueil");
    const pairs = links.map((l) => [l, targetOf(l)]).filter((p) => p[1]);
    if (!pairs.length || !("IntersectionObserver" in window)) return;
    const spy = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        pairs.forEach(([l, section]) => {
          const active = section === entry.target;
          l.classList.toggle("is-active", active);
          if (active) l.setAttribute("aria-current", "true"); else l.removeAttribute("aria-current");
        });
      });
    }, { rootMargin: "-45% 0px -50% 0px" });
    new Set(pairs.map((p) => p[1])).forEach((s) => spy.observe(s));
  }

  /* --- 3. Apparition progressive au défilement --- */
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

  /* --- 4. Équipe : filtres par groupe --- */
  function initTeam() {
    const grid = $("#team-grid");
    const buttons = $$("[data-filter]");
    if (!grid || !buttons.length) return;
    buttons.forEach((btn) => {
      btn.addEventListener("click", () => {
        const f = btn.dataset.filter;
        buttons.forEach((b) => { b.classList.toggle("is-active", b === btn); b.setAttribute("aria-pressed", String(b === btn)); });
        $$(".player-card", grid).forEach((card) => card.classList.toggle("is-hidden", f !== "all" && card.dataset.group !== f));
      });
    });
  }

  /* --- 5. Publicités : rotation en fondu quand plusieurs sont actives --- */
  function initAds() {
    $$(".ad-banner[data-rotate]").forEach((banner) => {
      const items = $$(".ad-banner__item", banner);
      if (items.length < 2 || reduceMotion) return;
      let index = 0;
      let paused = false;
      const show = (i) => {
        items.forEach((item, n) => {
          const current = n === i;
          item.classList.toggle("is-current", current);
          item.setAttribute("aria-hidden", String(!current));
          const link = $("a", item);
          if (link) { if (current) link.removeAttribute("tabindex"); else link.setAttribute("tabindex", "-1"); }
        });
      };
      banner.addEventListener("mouseenter", () => { paused = true; });
      banner.addEventListener("mouseleave", () => { paused = false; });
      banner.addEventListener("focusin", () => { paused = true; });
      banner.addEventListener("focusout", () => { paused = false; });
      window.setInterval(() => {
        if (paused || document.hidden) return;
        index = (index + 1) % items.length;
        show(index);
      }, Math.max(3000, config.adsDelay || 6000));
    });
  }

  /* --- 6. Formulaire de contact : vérification puis ouverture de WhatsApp ---
     Le formulaire est envoyé à WordPress (admin-post.php), qui revérifie les
     champs et redirige vers https://wa.me/NUMERO?text=MESSAGE. Le visiteur doit
     encore appuyer sur « Envoyer » dans WhatsApp. */
  function initForm() {
    const form = $("#contact-form");
    if (!form) return;
    const fields = $$(".field", form);
    const success = $(".form__success", form);
    const alert = $(".form__alert", form);

    const validate = (field) => {
      const input = $("input, select, textarea", field);
      const error = $(".field__error", field);
      const value = input.value.trim();
      const min = parseInt(input.getAttribute("minlength") || "0", 10);
      let msg = "";
      if (input.required && !value) msg = t("required", "Ce champ est obligatoire.");
      else if (value && input.type === "email" && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value)) msg = t("email", "Merci d'indiquer une adresse e-mail valide.");
      else if (value && input.type === "tel" && !/^\+?[\d\s().-]{6,20}$/.test(value)) msg = t("tel", "Merci d'indiquer un numéro de téléphone valide.");
      else if (value && min > 0 && value.length < min) msg = t("minlength", "Merci d'écrire au moins %d caractères.").replace("%d", min);
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
      const ok = fields.map(validate).every(Boolean);
      if (!ok) {
        e.preventDefault();
        if (success) success.hidden = true;
        const first = $(".has-error input, .has-error select, .has-error textarea", form);
        if (first) first.focus();
        return;
      }
      // WhatsApp s'ouvre dans un nouvel onglet ; le visiteur reste sur le site.
      form.target = "_blank";
      if (alert) alert.hidden = true;
      if (success) success.hidden = false;
      window.setTimeout(() => {
        form.reset();
        form.removeAttribute("target");
      }, 400);
    });
  }

  initImageFallback();
  initHeader();
  initReveal();
  initTeam();
  initAds();
  initForm();
})();
