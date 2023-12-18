<div class="mb-3">
    <label for="{{ requestKey }}" class="form-label">{{ label }}</label>
    <div class="input-group has-validation">
        <textarea class="form-control inputField{{ isInvalidString }}" name="{{ requestKey }}" id="{{ requestKey }}" 
        placeholder="{{ placeholder }}">{{ displayedValue }}</textarea>
        <div class="invalid-feedback validationMessage" id="{{ requestKey }}-validationMessage">{{ validationMessage }}</div>
    </div>
</div>