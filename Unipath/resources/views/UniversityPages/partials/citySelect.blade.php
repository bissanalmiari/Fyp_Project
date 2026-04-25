<select name="city" id="city" class="select {{ !empty($selectedCountry) ? 'show' : 'hidden' }}">
    <option value="">City</option>
    @foreach($specificCities as $city)
        <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>
            {{ $city }}
        </option>
    @endforeach
</select>