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
                <div id="{{ requestKey }}" class="widgetWrapper-light" style="width: 100%; margin: 0px; min-height: 42px; white-space: pre-line;">{{ displayedValue }}</div>
            </div>
        </div>
    </div>
</div>
