<div class="mb-3">
    <label for="{{ requestKey }}" class="form-label">{{ label }}</label>
    <div class="input-group has-validation">
        <input type="text" class="form-control inputField{{ isInvalidString }}" name="{{ requestKey }}" id="{{ requestKey }}" 
        maxlength="250" placeholder="{{ placeholder }}" value="{{ displayedValue }}">
        <div class="invalid-feedback validationMessage" id="{{ requestKey }}-validationMessage">{{ validationMessage }}</div>
    </div>
</div>