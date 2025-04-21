<!-- JAVASCRIPT -->
<script src="{{ URL::asset('build/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js')}}"></script>
<script>
    $('#change-password').on('submit',function(event){
        event.preventDefault();
        var Id = $('#data_id').val();
        var current_password = $('#current-password').val();
        var password = $('#password').val();
        var password_confirm = $('#password-confirm').val();
        $('#current_passwordError').text('');
        $('#passwordError').text('');
        $('#password_confirmError').text('');
        $.ajax({
            url: "{{ url('update-password') }}" + "/" + Id,
            type:"POST",
            data:{
                "current_password": current_password,
                "password": password,
                "password_confirmation": password_confirm,
                "_token": "{{ csrf_token() }}",
            },
            success:function(response){
                $('#current_passwordError').text('');
                $('#passwordError').text('');
                $('#password_confirmError').text('');
                if(response.isSuccess == false){
                    $('#current_passwordError').text(response.Message);
                }else if(response.isSuccess == true){
                    setTimeout(function () {
                        window.location.href = "{{ route('login') }}";
                    }, 1000);
                }
            },
            error: function(response) {
                $('#current_passwordError').text(response.responseJSON.errors.current_password);
                $('#passwordError').text(response.responseJSON.errors.password);
                $('#password_confirmError').text(response.responseJSON.errors.password_confirmation);
            }
        });
    });
</script>
<script>
    // Automatically close alert after 5 seconds
    setTimeout(() => {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500); // Remove element after fade out
        });
    }, 5000); // Time in milliseconds




$(document).on('click', '.confirm-btn', function (e) {
    e.preventDefault();  // Prevent form submission

    let form = $(this).closest('form');
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "سيتم تغيير حالة الطلب.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "نعم، تأكيد",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();  // Submit the form if confirmed
        }
    });
});
$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();  // Prevent default action

    let url = $(this).attr('href');  // Get the href of the link
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "سيتم حذف هذا العنصر. لا يمكن التراجع عن هذا الإجراء.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "نعم، حذف",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;  // Redirect to the delete URL
        }
    });
});
$(document).on('click', '.cancel-btn', function (e) {
    e.preventDefault();  // Prevent default action

    let url = $(this).attr('href');  // Get the cancel URL
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: "سيتم إلغاء العمل، هل أنت متأكد؟",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: "نعم، الغاء",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;  // Redirect to the cancel URL
        }
    });
});
</script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('build/js/app.js')}}"></script>

@yield('script-bottom')
