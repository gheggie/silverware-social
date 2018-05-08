/* Sharing Item
===================================================================================================================== */

import $ from 'jquery';

$(function() {
  
  // Handle Sharing Item Icons:
  
  $('.sharingitem').each(function() {
    
    var $self  = $(this);
    var $icons = $self.find('.sharingicon');
    
    $icons.on('click', function(e) {
      e.preventDefault();
      return true;
    });
    
    $icons.each(function() {
      var $icon = $(this);
      $icon.popover({
        html: true,
        content: $icon.find('.sharingicon-popover-content')
      });
    });
    
  });
  
});
