@if (session($key ?? 'errors') || session($key ?? 'error'))
{{-- <div role="alert" class="alert position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
    <div id="liveToast" class="toast show">
      <div class="toast-header">
        <i class="text-danger fas fa-times-circle    mr-2"></i>
        <strong class="mr-auto">IMS</strong>
        <small>just now</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
          @if(session($key ?? 'errors'))
        Something went wrong! Please check the form.
          @else
         {!!session($key ?? 'error')!!}
          @endif
      </div>
    </div>
</div> --}}
@push('js')
<script>
    $(document).ready(function(){
        const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  backdrop:false,
  showConfirmButton: false,
  showCloseButton: true,
  timer: 4000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
if({!!json_encode(session($key ?? 'errors'))!!}){
Toast.fire({
  icon: 'error',
  title: "{{__('Something went wrong! Please check the form.')}}"

})
}else{
    Toast.fire({
  icon: 'error',
  title: "{{session($key ?? 'error')}}"
})
}
});
</script>
@endpush
@endif
