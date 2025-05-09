/******/ (function() { // webpackBootstrap
/*!*******************************************!*\
  !*** ./resources/js/pages/rating-init.js ***!
  \*******************************************/
$(function () {
  $("input.check").on("change", function () {
    alert("Rating: " + $(this).val());
  }), $(".rating-tooltip").rating({
    extendSymbol: function extendSymbol(t) {
      $(this).tooltip({
        container: "body",
        placement: "bottom",
        title: "Rate " + t
      });
    }
  }), $(".rating-tooltip-manual").rating({
    extendSymbol: function extendSymbol() {
      var i;
      $(this).tooltip({
        container: "body",
        placement: "bottom",
        trigger: "manual",
        title: function title() {
          return i;
        }
      }), $(this).on("rating.rateenter", function (t, n) {
        i = n, $(this).tooltip("show");
      }).on("rating.rateleave", function () {
        $(this).tooltip("hide");
      });
    }
  }), $(".rating").each(function () {
    $('<span class="badge bg-info"></span>').text($(this).val() || "").insertAfter(this);
  }), $(".rating").on("change", function () {
    $(this).next(".badge").text($(this).val());
  });
});
/******/ })()
;