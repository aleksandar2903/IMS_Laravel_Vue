<div id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalCenterTitle"
    class="modal fade" aria-modal="true">
    <div role="document" class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="m-3 p-0 modal-header flex-column"><button type="button" data-dismiss="modal" aria-hidden="true"
                    class="close">
                    &times;</button></div>
            <div class="modal-body text-center p-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor"
                    class="mb-4 bi bi-x-circle text-danger" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path
                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                </svg>
                <h1 class="text-gray ">{{__('Are you sure?')}}</h1>
                <p class="mx-5 mt-3">{{__('Do you really want to delete this record? This process cannot be undone.')}}
                </p>
            </div>
            <div class="modal-footer pb-5 justify-content-center">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">{{__('Cancel')}}</button>
                <form action="" method="post">
                    @method('delete')
                    @csrf
                    <button type="submit" class="btn btn-danger">{{ __('Delete')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
