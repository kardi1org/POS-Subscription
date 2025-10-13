@extends('layouts.app')

@section('content')
    <div class="form-container">
        <h2>Sign Up for a Plan</h2>
        <form action="{{ route('pricing.store') }}" method="post">
            @csrf
            <div class="form-group">
                <input type="hidden" id="email" name="email" value="{{ Auth::user()->email }}" required />
            </div>

            <div class="form-group">
                <input type="hidden" id="codepaket" name="codepaket" value="{{ $id }}" required readonly />
            </div>
            <div class="form-group">
                <label for="plan">Plan</label>
                <input type="text" id="namapaket" name="namapaket" value="{{ $namapaket }}" required readonly />
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Any comments or requests..."></textarea>
            </div>

            <button type="submit" class="button">Submit</button>
        </form>
    </div>
@endsection
