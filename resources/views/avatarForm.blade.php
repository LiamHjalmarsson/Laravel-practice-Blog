<x-layout doctitle="Manage Avatar">

    <div class="cntainer container--narrow py-md-5"> 
        <h2 class="text-center mb-3" > Upload new avatar</h2>
        <form method="post" action="/manage-avatar" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar">
                @error("avatar")
                    <p class="alert small alert-danger shadow-sm"> {{ $message }}</p>
                @enderror
                <button class="btn btn-primary"> Save </button>
            </div>
        </form>
    </div>
</x-layout>
