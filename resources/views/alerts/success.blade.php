@if (session($key ?? 'status'))
{{-- <div role="alert" class="alert position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
    <div id="liveToast" class="toast show">
      <div class="toast-header">
        <i class="text-success fas fa-check-circle   mr-2 "></i>
        <strong class="mr-auto">IMS</strong>
        <small>just now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        {!!session($key ?? 'status')!!}
      </div>
    </div>
  </div> --}}
  @push('js')
  <script>
     $(document).ready(function(){
        const Toast = Swal.mixin({
  toast: true,
  backdrop: false,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

Toast.fire({
  icon: 'success',
  title: "{{session($key ?? 'status')}}"
})
});
  </script>
  @endpush
@endif
