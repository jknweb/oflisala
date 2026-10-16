/* OC Lisala : ordre d'affichage par glisser-déposer dans la liste des contenus. */
(function ($) {
  "use strict";
  var $list = $("#the-list");
  if (!$list.length || !$list.find(".ocl-handle").length || !window.oclSort) return;

  var $notice = $('<div class="notice inline ocl-sort-notice" role="status" aria-live="polite" hidden><p></p></div>');
  $(".wp-header-end").after($notice);

  function notify(text, type) {
    $notice.removeClass("notice-success notice-error").addClass(type).prop("hidden", false).find("p").text(text);
  }

  $list.sortable({
    items: "> tr",
    handle: ".ocl-handle",
    axis: "y",
    cursor: "move",
    helper: function (e, tr) {
      var $cells = tr.children();
      return tr.clone().children().each(function (i) { $(this).width($cells.eq(i).width()); }).end();
    },
    update: function () {
      var order = $list.find("> tr").map(function () { return this.id.replace("post-", ""); }).get();
      $.post(oclSort.ajaxUrl, { action: "ocl_save_order", nonce: oclSort.nonce, order: order })
        .done(function (res) {
          if (!res || !res.success) { notify(oclSort.error, "notice-error"); return; }
          $list.find("> tr").each(function (i) { $(this).find(".ocl-order-value").text(i + 1); });
          notify(oclSort.saved, "notice-success");
        })
        .fail(function () { notify(oclSort.error, "notice-error"); });
    }
  });
})(jQuery);
