$(document).ready(function() {
  // Multiple files image uploader button 
  $('.images_uploader').click(function() {
      $(this).next().click();
  });

  // Image preview 
  $('.imageInput').change(function(){
      const file = this.files[0];
      console.log(file);
      if (file){
          let reader = new FileReader();
          reader.onload = function(event){
              console.log(event.target.result);
              $('.imagePreview').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
      }
  });

  // Delete button functionality
  $('.deleteButton').click(function() {
      // Reset the image to the default image source
      $('.imagePreview').attr('src', '/images/user-default-image.jpeg');

      // Clear the input file value to allow selecting the same file again
      $('.imageInput').val('');
  });
});
