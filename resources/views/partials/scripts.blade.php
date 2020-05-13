<script src="{{ asset('assets/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-migrate-3.1.0.min.js') }}"></script>
<script src="{{ asset('assets/js/mmenu.min.js') }}"></script>
<script src="{{ asset('assets/js/tippy.all.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/js/snackbar.js') }}"></script>
<script src="{{ asset('assets/js/clipboard.min.js') }}"></script>
<script src="{{ asset('assets/js/counterup.min.js') }}"></script>
<script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/my_custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
@toastr_js
@toastr_render

<!-- Snackbar // documentation: https://www.polonel.com/snackbar/ -->
<script>
// Snackbar for user status switcher
$('#snackbar-user-status label').click(function() { 
    Snackbar.show({
        text: 'Your status has been changed!',
        pos: 'bottom-center',
        showAction: false,
        actionText: "Dismiss",
        duration: 3000,
        textColor: '#fff',
        backgroundColor: '#383838'
    }); 
}); 
$('.status-switch label.user-invisible').on('click', function(){
    // Switch to hirer
    $('#update_role_account_type').val('hirer');
    $('#update_role_form').submit();
});

$('.status-switch label.user-online').on('click', function(){
    // Switch to freelancer
    $('#update_role_account_type').val('freelancer');
    $('#update_role_form').submit();
});
$.ajaxSetup({
   headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});
$(document).on('keyup','.bs-searchbox input',function(e){
   var text =$(this).val();
       if (e.keyCode == 13 ) {
       $.ajax({
           url: "{{route('search.text')}}",
           type: 'post',
           data: {text:text},
           success: function(results)
           {
               var skill_id = results.skills_id;
               if(skill_id)
               {
                   $('#skills').append('<option value ='+results.skills_id+' selected>'+results.text_val+'</option>');
                   $('#skills').selectpicker('destroy');
                   $('#skills').selectpicker();
               }
           }
       });
   }

});
</script>
@stack('custom-scripts')