(function($) {
  $.fn.wrapSelected = function(open, close) {
    return this.each(function() {
      var textarea = $(this);
      var value = textarea.val();
      var start = textarea[0].selectionStart;
      var end = textarea[0].selectionEnd;
      textarea.val(
        value.substr(0, start) + 
        open + value.substring(start, end) + close + 
        value.substring(end, value.length)
      );
    });
  };
})(jQuery);