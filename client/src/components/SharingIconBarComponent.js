/* Sharing Icon Bar Component
===================================================================================================================== */

import $ from 'jquery';

$(function() {
  
  // Handle Icon Bar Links:
  
  $('.sharingiconbarcomponent').each(function() {
    
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
