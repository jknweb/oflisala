/* OC Lisala · Pages « Gestion du site » : médiathèque, listes répétables, type de lien. */
(function ($) {
  "use strict";
  var l10n = window.oclAdmin || {};

  /* --- Sélecteur d'images de la médiathèque --- */
  $(document).on("click", ".ocl-media__select", function (e) {
    e.preventDefault();
    var $wrap = $(this).closest(".ocl-media");
    var multiple = $wrap.data("multiple") === 1 || $wrap.data("multiple") === "1";
    var max = parseInt($wrap.data("max"), 10) || 0;
    var $input = $wrap.find(".ocl-media__input");
    var frame = wp.media({
      title: multiple ? l10n.chooseImages : l10n.chooseImage,
      button: { text: multiple ? l10n.useImages : l10n.useImage },
      library: { type: "image" },
      multiple: multiple ? "add" : false
    });
    frame.on("open", function () {
      var selection = frame.state().get("selection");
      String($input.val() || "").split(",").forEach(function (id) {
        id = parseInt(id, 10);
        if (id) { var att = wp.media.attachment(id); att.fetch(); selection.add(att); }
      });
    });
    frame.on("select", function () {
      var items = frame.state().get("selection").toJSON();
      if (max && items.length > max) {
        window.alert((l10n.maxImages || "").replace("%d", max));
        items = items.slice(0, max);
      }
      var ids = [], html = "";
      items.forEach(function (item) {
        ids.push(item.id);
        var src = item.sizes && item.sizes.thumbnail ? item.sizes.thumbnail.url : item.url;
        html += '<img src="' + _.escape(src) + '" alt="">';
      });
      $input.val(ids.join(",")).trigger("change");
      $wrap.find(".ocl-media__preview").html(html);
      $wrap.find(".ocl-media__remove").prop("hidden", !ids.length);
    });
    frame.open();
  });

  $(document).on("click", ".ocl-media__remove", function (e) {
    e.preventDefault();
    var $wrap = $(this).closest(".ocl-media");
    $wrap.find(".ocl-media__input").val("").trigger("change");
    $wrap.find(".ocl-media__preview").empty();
    $(this).prop("hidden", true);
  });

  /* --- Listes répétables : ajout, suppression, glisser-déposer --- */
  function renumber($repeater) {
    var $rows = $repeater.find("> .ocl-repeater__rows > .ocl-repeater__row");
    $rows.each(function (i) { $(this).find("> .ocl-repeater__bar .ocl-repeater__num").text(String(i + 1).padStart(2, "0")); });
    var max = parseInt($repeater.data("max"), 10) || 0;
    $repeater.find("> .ocl-repeater__add").prop("disabled", max > 0 && $rows.length >= max);
  }

  $(".ocl-repeater").each(function () {
    var $repeater = $(this);
    renumber($repeater);
    $repeater.find("> .ocl-repeater__rows").sortable({
      handle: ".ocl-repeater__handle",
      axis: "y",
      placeholder: "ocl-repeater__placeholder",
      forcePlaceholderSize: true,
      update: function () { renumber($repeater); }
    });
  });

  $(document).on("click", ".ocl-repeater__add", function (e) {
    e.preventDefault();
    var $repeater = $(this).closest(".ocl-repeater");
    var max = parseInt($repeater.data("max"), 10) || 0;
    var $rows = $repeater.find("> .ocl-repeater__rows");
    if (max && $rows.children().length >= max) { window.alert(l10n.maxReached); return; }
    var next = parseInt($repeater.data("next"), 10) || 0;
    var html = $repeater.find("> .ocl-repeater__template").html().replace(/__i__/g, String(next));
    $repeater.data("next", next + 1);
    var $row = $(html);
    $rows.append($row);
    renumber($repeater);
    $row.find("input, textarea, select").first().trigger("focus");
  });

  $(document).on("click", ".ocl-repeater__remove", function (e) {
    e.preventDefault();
    if (!window.confirm(l10n.confirmRemove)) return;
    var $repeater = $(this).closest(".ocl-repeater");
    $(this).closest(".ocl-repeater__row").remove();
    renumber($repeater);
  });

  /* --- Champ « lien » : WhatsApp ou adresse --- */
  $(document).on("change", ".ocl-link__type", function () {
    $(this).closest(".ocl-link").attr("data-type", this.value);
  });
})(jQuery);
