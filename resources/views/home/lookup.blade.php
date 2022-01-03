@if (session('s_message'))
    <div class="alert alert-danger" role="alert">
        {{ session('s_message') }}
    </div>
@endif
<form action="{{ route('charitySearch') }}" id="search">
    <div class="row g-3">
        <div class="col-sm-12 col-md-1">
            <label><strong>Lookup:</strong> </label>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="input-group has-validation">
                <span class="input-group-text">@</span>
                <input type="email" name="s_email" class="form-control @error('s_email') is-invalid @enderror"
                    value="{{ old('s_email') }}" placeholder="Email" aria-label="Email" required>
                @error('s_email')
                    @include('shared.error',['message'=>$message])
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="input-group has-validation">
                <span class="input-group-text">#</span>
                <input type="password" name="s_password" class="form-control @error('s_password') is-invalid @enderror"
                     placeholder="Password" aria-label="Password" required>
                @error('s_password')
                    @include('shared.error',['message'=>$message])
                @enderror
            </div>
        </div>
        <div class="col-sd-12 col-md-2">
            <div class="input-group has-validation">
                <span class="input-group-text">#</span>
                <input type="text" name="s_code" class="form-control @error('s_code') is-invalid @enderror"
                    value="{{ old('s_code') }}" placeholder="Code" aria-label="Code" required>
                @error('s_code')
                    @include('shared.error',['message'=>$message])
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>
