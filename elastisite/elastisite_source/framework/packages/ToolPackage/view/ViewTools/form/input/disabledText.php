<div class="row">
    <div class="col-xs-12 col-sm-{{ labelRate }} col-md-{{ labelRate }} col-lg-{{ labelRate }}">
        <div class="form-group formLabel{{ labelAdditionalClass }}">
            <label for="{{ requestKey }}">
                <b>{{ label }}</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-{{ inputRate }} col-md-{{ inputRate }} col-lg-{{ inputRate }}">
        <div class="form-group">
            <div class="input-group">
                <input disabled name="{{ requestKey }}" id="{{ requestKey }}" type="text"
                    class="inputField form-control"
                    value="{{ displayedValue }}"
                    aria-describedby="" placeholder="{{ placeholder }}">

            </div>
            <div class="validationMessage error" id="{{ requestKey }}-validationMessage" style="padding-top:4px;">{{ validationMessage }}</div>
        </div>
    </div>
</div>
